library(mirt)
args = commandArgs(trailingOnly=TRUE)
con <- DBI::dbConnect(odbc::odbc(),
                 Driver = "MariaDB ODBC 3.0 Driver",
                 Server = "mariadb",
                 Database = "my_database",
                 UID = "my_user",
                 PWD = "my_password",
                 Port = 3306)
grades <- DBI::dbGetQuery(con, paste0("SELECT * FROM student_grades WHERE user_id = '", args[1], "'"))
questions <- DBI::dbGetQuery(con, paste0("SELECT * FROM questions WHERE user_id = '", args[1], "'"))
students <- DBI::dbGetQuery(con, paste0("SELECT * FROM students WHERE user_id = '", args[1], "'"))
dat <- array(grades[,5], dim = c(30,length(questions[,1])))
dat <- provideDimnames(dat, sep = "_", base = list('student','item'))
(mmod <- mirt(dat, 1, '3PL', SE=TRUE, verbose=FALSE))
items <- coef(mmod, simplify=TRUE)[[1]]
for (i in 1:length(items[,2])) {
  query <- paste0("UPDATE questions SET ability =",items[i,2],", discrimination=",items[i,3],", guess=",items[i,4]," WHERE id='",questions[i,1],"'")
  DBI::dbExecute(con, query)
}
students_ability <- fscores(mmod, theta_lim = c(-3,3))
for (i in 1:length(students_ability)) {
  query <- paste0("UPDATE students SET ability =",students_ability[i]," WHERE id='",students[i,1],"'")
  DBI::dbExecute(con, query)
}
