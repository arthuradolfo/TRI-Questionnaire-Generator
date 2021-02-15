library(mirt)
args = commandArgs(trailingOnly=TRUE)

con <- DBI::dbConnect(odbc::odbc(),
                 Driver = "MariaDB ODBC 3.0 Driver",
                 Server = "mariadb",
                 Database = "my_database",
                 UID = "my_user",
                 PWD = "my_password",
                 Port = 3306)
questions <- DBI::dbGetQuery(con, paste0("SELECT qt1.* FROM questions qt1
                                          LEFT JOIN categories ct1
                                          ON ct1.id = qt1.category_id AND (ct1.id = '", args[2], "'
                                          OR ct1.category_id = '", args[2], "')
                                          LEFT JOIN categories ct2
                                          ON ct2.category_id = ct1.id OR ct2.id = ct1.id
                                          WHERE qt1.category_id = ct2.id ORDER BY id ASC"))
students <- DBI::dbGetQuery(con, paste0("SELECT * FROM students WHERE user_id = '", args[1], "' ORDER BY id DESC"))

questions_length = length(questions[,1])
students_length = length(students[,1])

dat <- array(dim = c(students_length,questions_length))

grades <- DBI::dbGetQuery(con, paste0("SELECT st1.* FROM student_grades st1
                                       JOIN (SELECT qt1.id FROM questions qt1
                                       LEFT JOIN categories ct1
                                       ON ct1.id = qt1.category_id AND (ct1.id = '", args[2], "'
                                       OR ct1.category_id = '", args[2], "')
                                       LEFT JOIN categories ct2
                                       ON ct2.category_id = ct1.id OR ct2.id = ct1.id
                                       WHERE qt1.category_id = ct2.id) sel_qt
                                       ON sel_qt.id = st1.question_id ORDER BY question_id ASC, student_id DESC"))
grades_offset <- 0

for (i in 1:questions_length) {
	grades_length = sum(grades[,4] == questions[i,1])
	if(grades_length != 0)
	{
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
}
dat <- provideDimnames(dat, sep = "_", base = list('student','item'))

removed <- 0
for (i in 1:questions_length)
{
    i <- i - removed
    if(removed + 1 == questions_length)
    {
        if(is.na(match(0, dat)) || is.na(match(1, dat)))
        {
                dat <- NULL
                questions <- NULL
        }
    }
    else
    {
        if( is.na(match(0, dat[,i])) || is.na(match(1, dat[,i])) )
        {
            dat <- dat[,-c(i)]
            questions <- questions[-c(i),]
            removed <- removed + 1
        }
    }
}

(mmod <- mirt(dat, 1, '3PL', SE=TRUE, verbose=FALSE, technical = list(removeEmptyRows=TRUE)))
items <- coef(mmod, simplify=TRUE)[[1]]

max_ability <- max(items[,1])
min_ability <- min(items[,1])

for (i in 1:length(items[,1])) {
  items[i,1] <- ((items[i,1]-min_ability) / (max_ability-min_ability))*6 - 3
  query <- paste0("UPDATE questions SET ability =",items[i,1],", discrimination=",items[i,2],", guess=",items[i,3]," WHERE id='",questions[i,1],"'")
  DBI::dbExecute(con, query)
  query <- paste0("INSERT INTO question_ability_logs (user_id, question_id, ability, discrimination, guess, time) VALUES ('",args[1],"', '",questions[i,1],"', ",items[i,1],", ",items[i,2],", ",items[i,3],", CURRENT_TIMESTAMP)")
  DBI::dbExecute(con, query)
}

students_ability <- fscores(mmod, theta_lim = c(-3,3))
for (i in 1:length(students[,1])) {
  query <- paste0("UPDATE students SET ability =",students_ability[i]," WHERE id='",students[i,1],"'")
  DBI::dbExecute(con, query)
  query <- paste0("INSERT INTO student_ability_logs (user_id, student_id, ability, time) VALUES ('",args[1],"', '",students[i,1],"', ",items[i,1],", CURRENT_TIMESTAMP)")
  DBI::dbExecute(con, query)
}
