DROP DATABASE IF EXISTS MyDB;
CREATE DATABASE IF NOT EXISTS MyDB;

USE MyDB;

CREATE TABLE IF NOT EXISTS qcm(
	qcmid INT not null PRIMARY KEY,
    title varchar(100)
);

CREATE TABLE IF NOT EXISTS question(
	questid INT not null PRIMARY KEY,
    qcmid INT not null,
    text varchar(100),
    FOREIGN KEY (qcmid)
        REFERENCES MyDB.qcm (qcmid)
);

CREATE TABLE IF NOT EXISTS answer(
	answid INT not null PRIMARY KEY,
    questid INT not null,
    text varchar(100),
    FOREIGN KEY (questid)
        REFERENCES MyDB.question (questid)
);

ALTER TABLE `answer` ADD `correct_answ` TINYINT(1) NOT NULL AFTER `text`;

CREATE TABLE IF NOT EXISTS users(
	userid INT not null PRIMARY KEY,
    name varchar(30) not null,
    pwd varchar(30) not null,
    level INT not null
);

CREATE TABLE IF NOT EXISTS exam(
	examid INT not null PRIMARY KEY AUTO_INCREMENT,
    userid INT not null,
    qcmid INT not null,
    status varchar(10) not null,
    result INT not null,
    FOREIGN KEY (userid)
        REFERENCES MyDB.users (userid), 
	FOREIGN KEY (qcmid)
        REFERENCES MyDB.qcm (qcmid)
);

CREATE TABLE IF NOT EXISTS exam_line(
	examlineid INT not null PRIMARY KEY,
    examid INT not null,
    questid INT not null,
    answid INT not null,
    result INT not null,
    FOREIGN KEY (examid)
        REFERENCES MyDB.exam (examid),
	FOREIGN KEY (questid)
        REFERENCES MyDB.question (questid),
	FOREIGN KEY (answid)
        REFERENCES MyDB.answer (answid)
);