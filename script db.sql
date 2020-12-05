DROP DATABASE techniques_web;
CREATE DATABASE IF NOT EXISTS Techniques_web;

USE Techniques_web;

CREATE TABLE IF NOT EXISTS qcm(
	qcmid INT not null PRIMARY KEY,
    title varchar(100)
);

CREATE TABLE IF NOT EXISTS question(
	questid INT not null PRIMARY KEY,
    qcmid INT not null,
    text varchar(100),
    FOREIGN KEY (qcmid)
        REFERENCES Techniques_web.qcm (qcmid)
);

CREATE TABLE IF NOT EXISTS answer(
	answid INT not null PRIMARY KEY,
    questid INT not null,
    text varchar(100),
    FOREIGN KEY (questid)
        REFERENCES Techniques_web.question (questid)
);

CREATE TABLE IF NOT EXISTS user(
	userid INT not null PRIMARY KEY,
    name varchar(30) not null,
    pwd varchar(30) not null,
    level INT not null
);

CREATE TABLE IF NOT EXISTS exam(
	examid INT not null PRIMARY KEY,
    userid INT not null,
    qcmid INT not null,
    status varchar(10) not null,
    result INT not null,
    FOREIGN KEY (userid)
        REFERENCES Techniques_web.user (userid), 
	FOREIGN KEY (qcmid)
        REFERENCES Techniques_web.qcm (qcmid)
);

CREATE TABLE IF NOT EXISTS exam_line(
	examlineid INT not null PRIMARY KEY,
    examid INT not null,
    questid INT not null,
    answid INT not null,
    result INT not null,
    FOREIGN KEY (examid)
        REFERENCES Techniques_web.exam (examid),
	FOREIGN KEY (questid)
        REFERENCES Techniques_web.question (questid),
	FOREIGN KEY (answid)
        REFERENCES Techniques_web.answer (answid)
);