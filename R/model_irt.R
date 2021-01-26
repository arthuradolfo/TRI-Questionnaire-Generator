library(mirt)
args = commandArgs(trailingOnly=TRUE)

con <- DBI::dbConnect(odbc::odbc(),
                 Driver = "MariaDB ODBC 3.0 Driver",
                 Server = "mariadb",
                 Database = "my_database",
                 UID = "my_user",
                 PWD = "my_password",
                 Port = 3306)

questions <- DBI::dbGetQuery(con, paste0("SELECT * FROM questions WHERE user_id = '", args[1], "' ORDER BY id ASC"))
students <- DBI::dbGetQuery(con, paste0("SELECT * FROM students WHERE user_id = '", args[1], "' ORDER BY id DESC"))

questions_length = length(questions[,1])
students_length = length(students[,1])

dat <- array(dim = c(students_length,questions_length))

grades <- DBI::dbGetQuery(con, paste0("SELECT * FROM student_grades WHERE user_id = '", args[1], "' ORDER BY question_id ASC, student_id DESC"))
grades_offset <- 0

for (i in 1:questions_length) {
	grades_length = sum(grades[,4] == questions[i,1])
	students_length = length(students[,1])
	for (j in 1:grades_length)
	{
		dat_length <- length(dat[,i])
		student_number = match(grades[j+grades_offset,3], students[,1])
		if(!is.na(student_number))
		{
			dat[student_number,i] = grades[j+grades_offset,5]
		}
		else
		{
			if(students_length+1 > dat_length)
			{
				dat <- rbind(dat, c(NA))
				dat_length <- dat_length + 1
			}
			if(is.na(dat[students_length+1,i]))
			{
				dat[students_length+1,i] <- grades[j+grades_offset,5]
			}
			else
			{
				students_length <- students_length + 1
				if(students_length+1 > dat_length)
				{
					dat <- rbind(dat, c(NA))
					dat_length <- dat_length + 1
				}
				dat[students_length+1,i] <- grades[j+grades_offset,5]
			}
		}
	}
      grades_offset <- grades_offset + grades_length
}
dat <- provideDimnames(dat, sep = "_", base = list('student','item'))

for (i in 1:questions_length)
{
	if( is.na(match(0, dat[,i])) || is.na(match(1, dat[,i])) )
	{
		dat <- dat[,-c(i)]
		questions <- questions[-c(i),]
		i <- i - 1
	}
}

(mmod <- mirt(dat, 1, '3PL', SE=TRUE, verbose=FALSE))
items <- coef(mmod, simplify=TRUE)[[1]]

max_ability <- max(items[,1])
min_ability <- min(items[,1])

for (i in 1:length(items[,1])) {
  items[i,1] <- ((items[i,1]-min_ability) / (max_ability-min_ability))*6 - 3
  query <- paste0("UPDATE questions SET ability =",items[i,1],", discrimination=",items[i,2],", guess=",items[i,3]," WHERE id='",questions[i,1],"'")
  DBI::dbExecute(con, query)
}

students_ability <- fscores(mmod, theta_lim = c(-3,3))
for (i in 1:length(students[,1])) {
  query <- paste0("UPDATE students SET ability =",students_ability[i]," WHERE id='",students[i,1],"'")
  DBI::dbExecute(con, query)
}
