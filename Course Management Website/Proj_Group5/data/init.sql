DROP DATABASE IF EXISTS dev_cga;

CREATE DATABASE IF NOT EXISTS dev_cga;

use dev_cga;

CREATE TABLE IF NOT EXISTS users (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	first_name VARCHAR(30) NOT NULL,
	last_name VARCHAR(30) NOT NULL,
	email VARCHAR(50) NOT NULL,
	password_digest VARCHAR(60) NOT NULL,
	student_id INT UNSIGNED unique,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	privilege INT DEFAULT 3,
	UNIQUE (email)
);
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (11,'Student','Test','student@concordia.ca','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',11,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (12,'Luca','Jones','luca_jj@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',12,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (13,'Ezra','Evans','ezevans@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',13,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (14,'Kayden','Lewis','kay@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',14,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (15,'River','White','whiteriver@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',15,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (16,'Alex','Williams','alex_will@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',16,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (17,'Remi','Gong','gong@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',17,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (18,'Elias','Huang','huang@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',18,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (19,'Rohan','Roberts','rr@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',19,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (20,'Jude','Jing','jingjude@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',20,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (21,'Kai','Kong','kongk@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',21,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (22,'Quinn','Liu','quinn@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',22,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (23,'Jayden','Walker','jay@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',23,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (24,'Elliot','Thomas','elliot@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',24,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (25,'Xavier','Green','xa@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',25,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (26,'Andrea','Harris','harris@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',26,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (27,'Rowan','Dong','dong@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',27,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (28,'Hayden','Cooper','haycoo@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',28,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (29,'Kyle','King','king@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',29,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`) VALUES (30,'Shia','Lee','leeshia@gmail.com','$2y$10$8n8BAadiDcMOgJH2PYJFKuZAaG2usLjnu0Yphhsqj.tNfwHW7zfDi',30,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`,`privilege`) VALUES (31,'Dwight','Moore','dwight@gmail.com','$2y$10$ieNuESkLtU.2OwejF3rstuwkcIW0nd.yEN8/QRjqir6Zlbi887056',31,'2022-03-25 00:30:00','2022-03-25 00:30:00',1);
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`,`privilege`) VALUES (32,'Instructor','Test','instructor@concordia.ca','$2y$10$ieNuESkLtU.2OwejF3rstuwkcIW0nd.yEN8/QRjqir6Zlbi887056',32,'2022-03-25 00:30:00','2022-03-25 00:30:00',1);
INSERT INTO `users` (`id`,`first_name`,`last_name`,`email`,`password_digest`,`student_id`,`created_at`,`updated_at`,`privilege`) VALUES (33,'TA','Test','ta@concordia.ca','$2y$10$ieNuESkLtU.2OwejF3rstuwkcIW0nd.yEN8/QRjqir6Zlbi887056',33,'2022-03-25 00:30:00','2022-03-25 00:30:00',2);

CREATE TABLE IF NOT EXISTS comment (
  `comment_id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `page_id` int NOT NULL,
  `parent_id` int NOT NULL DEFAULT '-1',
  `name` varchar(255) NOT NULL,
  `comment_text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `comment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `group_id` int NOT NULL,
  `course_id` int NOT NULL,
  file_index int NOT NULL DEFAULT -1
);

CREATE TABLE IF NOT EXISTS loggedin (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_digest VARCHAR(32) NOT NULL,
	user_id INT(11) UNSIGNED,
	created_at TIMESTAMP default current_timestamp,
	updated_at TIMESTAMP default current_timestamp,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS discussions (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) NOT NULL,
    me_id INT UNSIGNED NOT NULL,
	title VARCHAR(60) NOT NULL,
	topic_content TEXT NOT NULL,
    instructor_post INT DEFAULT 0,
	created_at TIMESTAMP,
	updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS attachments (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	file_id VARCHAR(60),
	file_filename VARCHAR(60),
	file_size INT(10),
	attachable_id INT(11),
	attachable_type VARCHAR(60),
	created_at TIMESTAMP default current_timestamp,
	updated_at TIMESTAMP default current_timestamp
);


CREATE TABLE IF NOT EXISTS meetings (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	group_id INT(11),
	user_id INT(11),
	title VARCHAR(60),
	agenda VARCHAR(1000),
	minutes VARCHAR(10000),
	planned_date DATE, 
	planned_time TIME,
	has_passed BOOLEAN DEFAULT false,
	start_at TIMESTAMP default current_timestamp,
	end_at TIMESTAMP default current_timestamp,
	created_at TIMESTAMP default current_timestamp,
	updated_at TIMESTAMP default current_timestamp
	-- TO DO add group_id as foreign key when table Groups is created (from course implementation)
	-- FOREIGN KEY (group_id) REFERENCES groups(id) 
);

CREATE TABLE IF NOT EXISTS feedbacks(
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) UNSIGNED,
	content TEXT,
	identity_id INT(11) UNSIGNED,
	identity_type VARCHAR(50),
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY(user_id) REFERENCES users(id)
);
CREATE UNIQUE INDEX uni_feedback ON feedbacks (user_id, identity_id,identity_type);


CREATE TABLE courses (
    course_num VARCHAR(8) NOT NULL PRIMARY KEY,
    course_name VARCHAR(20) NOT NULL,
    semester VARCHAR(8) NOT NULL,
    year INT unsigned NOT NULL,
    credit_hours INT(2) unsigned,
    room VARCHAR(8),
    offering_dept VARCHAR(20),
    created_at TIMESTAMP default current_timestamp,
    updated_at TIMESTAMP default current_timestamp
);

insert into courses (course_num,course_name,semester,year,credit_hours,room,offering_dept)values('comp5531','database', 'fall', 2022, 4, 'room1', 'computer');
insert into courses (course_num,course_name,semester,year,credit_hours,room,offering_dept)values('comp5541','tools&tech', 'fall', 2022, 4, 'room2', 'computer');
insert into courses (course_num,course_name,semester,year,credit_hours,room,offering_dept)values('comp5461','operating system', 'fall', 2022, 4, 'room3', 'computer');

-- new 
CREATE TABLE course_section (
    section_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    course_num VARCHAR(8) NOT NULL,
    user_id INT(11) UNSIGNED,
    section VARCHAR(4) NOT NULL,
    semester VARCHAR(4) NOT NULL,
    year INT NOT NULL,
    created_at TIMESTAMP default current_timestamp,
    updated_at TIMESTAMP default current_timestamp,
    FOREIGN KEY (course_num)
        REFERENCES courses (course_num)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id)
        REFERENCES users (id)
);

insert into course_section values (1, 'comp5531',32, 'NN', 'fall', 2020, null,null);
insert into course_section values (2, 'comp5531',31, 'NI', 'spri', 2020, null,null);
insert into course_section values (3, 'comp5541',32, 'DD', 'fall', 2021, null,null);
insert into course_section values (4, 'comp5541',32, 'DI', 'fall', 2021, null,null);
insert into course_section values (5, 'comp5541',31, 'NN', 'fall', 2020, null,null);
insert into course_section values (6, 'comp5461',31, 'NN', 'wint', 2020, null,null);
/*insert into course_section values (7, 'comp5461',28, 'NN', 'wint', 2020, null,null);
*/


CREATE TABLE enrollment (
    user_id INT(11) unsigned,
    section_id INT NOT NULL,
    grade VARCHAR(1),
    created_at TIMESTAMP default current_timestamp,
    updated_at TIMESTAMP default current_timestamp,
    PRIMARY KEY (user_id , section_id),
    FOREIGN KEY (user_id)
        REFERENCES users (id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (section_id)
        REFERENCES course_section (section_id)
        ON UPDATE CASCADE ON DELETE CASCADE
);   

insert into enrollment values ('11', 1, null,null,null);
insert into enrollment values ('12', 1, null,null,null);
insert into enrollment values ('13', 1, null,null,null);
insert into enrollment values ('14', 2, null,null,null);
insert into enrollment values ('15', 2, null,null,null);
insert into enrollment values ('16', 2, null,null,null);
insert into enrollment values ('17', 2, null,null,null);
insert into enrollment values ('18', 3, null,null,null);
insert into enrollment values ('19', 3, null,null,null);
insert into enrollment values ('20', 3, null,null,null);
insert into enrollment values ('21', 3, null,null,null);
insert into enrollment values ('22', 3, null,null,null);
insert into enrollment values ('23', 4, null,null,null);
insert into enrollment values ('24', 4, null,null,null);
insert into enrollment values ('25', 4, null,null,null);
insert into enrollment values ('26', 4, null,null,null);
insert into enrollment values ('27', 4, null,null,null);
insert into enrollment values ('28', 4, null,null,null);
insert into enrollment values ('29', 5, null,null,null);
insert into enrollment values ('30', 5, null,null,null);
insert into enrollment values ('31', 1, null,null,null);



CREATE TABLE course_group (
    group_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    group_name VARCHAR(20) NOT NULL,
    leader_id INT(11) unsigned,
    created_at TIMESTAMP default current_timestamp,
    updated_at TIMESTAMP default current_timestamp,
    FOREIGN KEY (section_id)
        REFERENCES course_section (section_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (leader_id)
        REFERENCES users (id)
);

-- inserted
insert into course_group values(1,1,'a', 11,null,null);
insert into course_group values(2,2,'b', 14,null,null);
insert into course_group values(3,2,'c', 15,null,null);
insert into course_group values(4,3,'a', 18,null,null);
insert into course_group values(5,4,'b', 23,null,null);
insert into course_group values(6,4,'e', 25,null,null);

/*-- unused
insert into course_group values(7,'comp5541','c',null,null);
insert into course_group values(8,'comp5541','d',null,null);
insert into course_group values(9,'comp5541','e',null,null);
insert into course_group values(10,'comp5461','ab',null,null);
insert into course_group values(11,'comp5461','cd',null,null);
*/

CREATE TABLE user_group (
    user_group_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT unsigned not null,
    group_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id)
        REFERENCES course_group (group_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (user_id)
        REFERENCES users (id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (1,11,1,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (2,12,1,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (3,13,1,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (4,14,2,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (5,15,3,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (6,16,3,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (7,17,3,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (8,18,4,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (9,19,4,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (10,20,4,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (11,21,4,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (12,22,4,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (13,23,5,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (14,24,5,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (15,25,6,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (16,26,6,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (17,27,6,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (18,28,6,'2022-03-25 00:30:00','2022-03-25 00:30:00');
/*
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (19,29,6,'2022-03-25 00:30:00','2022-03-25 00:30:00');
INSERT INTO `user_group` (`user_group_id`,`user_id`,`group_id`,`created_at`,`updated_at`) VALUES (20,30,6,'2022-03-25 00:30:00','2022-03-25 00:30:00');
*/

-- drop trigger if exists noDupSection;

CREATE TRIGGER noDupSection
BEFORE INSERT ON course_section 
FOR EACH ROW
BEGIN 
	DECLARE dupCount INT;
    SELECT COUNT(section) INTO dupCount
    FROM course_section
    WHERE course_num = NEW.course_num 
    and section=new.section;
    IF (dupCount > 0) THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'this section already exist';
    END IF;
END;

CREATE TABLE IF NOT EXISTS mails (
	id INT(32) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	receiver_id VARCHAR(50) NOT NULL,
    sender_id VARCHAR(50) NOT NULL,
	title VARCHAR(60),
	content VARCHAR(1000),
    delete_flag INT(32),
    read_flag BOOLEAN DEFAULT false,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
    FOREIGN KEY (receiver_id)
        REFERENCES users (email),
	FOREIGN KEY (sender_id)
        REFERENCES users (email)
    );
INSERT INTO `mails` (`id`,`receiver_id`,`sender_id`, `title`,`content`,`delete_flag`, `read_flag`, `created_at`,`updated_at`) 
VALUES ('1', 'instructor@concordia.ca', 'ta@concordia.ca', 'A self-introduction.', 'Dear Professor,\r\n\r\n I am Fan. I can be contacted by 514514514.\r\n\r\nBest,\r\nFan           ', '3', '0', '2022-04-23 18:59:32', '2022-04-23 18:59:32');

CREATE TRIGGER noDupGroup
BEFORE INSERT ON user_group
FOR EACH ROW
BEGIN 
	DECLARE dupCount INT;
	SELECT COUNT(group_id) INTO dupCount
    FROM user_group
    WHERE user_id = NEW.user_id 
    and group_id=new.group_id;
    IF (dupCount > 0) THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'you already in this group';
    END IF;
END;

-- drop table leftmarkentity;
create table leftMarkEntity(
id int auto_increment primary key,
user_id int,
group_id int,
me_id int,
section_id int,
left_time timestamp not null  default current_timestamp
);

CREATE TABLE IF NOT EXISTS marked_entities (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(50),
	description TEXT,
	sid INT NOT NULL,
	is_group_work BOOLEAN,
	due_at TIMESTAMP,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (sid) REFERENCES course_section(section_id) on delete cascade
);


CREATE TABLE IF NOT EXISTS marked_entity_files (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	entity_id INT(11) UNSIGNED NOT NULL,
	user_id INT(11) NOT NULL,
	title VARCHAR(50),
	description TEXT,
	created_at TIMESTAMP,
	updated_at TIMESTAMP,
	FOREIGN KEY (entity_id) REFERENCES marked_entities(id)
);


CREATE TABLE file_actions(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    me_id INT UNSIGNED NOT NULL,
    fid INT UNSIGNED NOT NULL,
    action text,
    filename varchar(60) NOT NULL,
    action_at timestamp default CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (me_id) REFERENCES marked_entities(id));

CREATE TRIGGER `DeleteAttachment` AFTER DELETE ON `marked_entity_files`
 FOR EACH ROW BEGIN
DELETE FROM attachments WHERE attachable_id = old.id AND attachable_type = "MarkedEntityFile";
END;

CREATE VIEW student_course_info AS(
    SELECT
        cs.section_id,
        cs.course_num,
        cs.section,
        cg.group_id,
        cg.group_name,
        u.first_name,
        u.last_name,
        u.id AS user_id
    FROM
        course_section cs
    LEFT OUTER JOIN course_group cg ON
        cs.section_id = cg.section_id
    LEFT OUTER JOIN user_group ug ON
        cg.group_id = ug.group_id
    RIGHT OUTER JOIN users u ON
        ug.user_id = u.id);

CREATE VIEW me_info AS(
    SELECT
        me.*,
        cs.user_id AS instructor_id,
        cs.course_num,
		cs.section,
		cs.semester,
		cs.year
    FROM
        `marked_entities` AS me
    LEFT OUTER JOIN course_section AS cs
    ON
        me.sid = cs.section_id
);

CREATE VIEW student_me_info AS(
    SELECT
        sci.*,
        mi.id AS me_id
    FROM
        `student_course_info` AS sci
    LEFT OUTER JOIN me_info AS mi
    ON
        sci.section_id = mi.sid
);

CREATE VIEW student_file_info AS(
    SELECT
        mef.id AS mef_id,
        mef.entity_id,
        mef.user_id,
        mef.title,
        mef.description,
        smi.section_id,
        smi.group_id,
        atta.id AS fid,
        atta.file_id AS fid_string,
        atta.file_filename AS filename,
        atta.file_size,
        atta.attachable_id,
        atta.attachable_type,
        atta.created_at,
        atta.updated_at
    FROM
        `marked_entity_files` AS mef
    LEFT OUTER JOIN student_me_info AS smi
    ON
        mef.entity_id = smi.me_id AND mef.user_id = smi.user_id
    LEFT OUTER JOIN attachments AS atta
    ON
        atta.attachable_id = mef.id AND atta.attachable_type = 'MarkedEntityFile'
);

create view student_group_info as(SELECT
        u.id,
        u.first_name,
        u.last_name,
        e.section_id,
        cs2.course_num,
        cs2.section,
        cg.group_id,
        cg.group_name
    FROM
        course_group cg 
            LEFT OUTER JOIN
        user_group ug ON ug.group_id = cg.group_id
        RIGHT OUTER JOIN course_section cs ON cs.section_id = cg.section_id
        RIGHT OUTER JOIN enrollment e on e.section_id = cs.section_id AND e.user_id=ug.user_id
        RIGHT OUTER JOIN users u ON u.id = e.user_id
        LEFT OUTER JOIN course_section cs2 ON e.section_id = cs2.section_id);

CREATE VIEW discussions_info AS(
    SELECT
        discussions.*,
        T.course_num,
        T.sid,
        T.title as me_title,
        sgf.group_id,
        sgf.group_name
    FROM
        discussions
    INNER JOIN(
        SELECT
            marked_entities.*,
            course_section.course_num
        FROM
            marked_entities
        INNER JOIN course_section ON marked_entities.sid = course_section.section_id
    ) AS T
ON
    discussions.me_id = T.id
LEFT OUTER JOIN student_group_info AS sgf
ON
    sgf.id = user_id AND T.sid = sgf.section_id
ORDER BY
    id
);

CREATE VIEW comment_file_info AS(
    SELECT
        *
    FROM
        `comment` AS c
    LEFT OUTER JOIN attachments AS a
    ON
        c.file_index = a.id AND a.attachable_type = 'Comment');

/*drop trigger transDel;
drop trigger transUpdate;
*/

CREATE TRIGGER `trans_update` BEFORE UPDATE
ON
    `user_group` FOR EACH ROW
BEGIN
    INSERT INTO leftMarkEntity(
        user_id,
        group_id,
        me_id,
        section_id
    )
SELECT
    mi.user_id,
    mi.group_id,
    mi.me_id,
    mi.section_id
FROM
    student_me_info AS mi
WHERE
    mi.user_id = old.user_id AND mi.group_id = old.group_id;
END;

CREATE TRIGGER `trans_delete` BEFORE DELETE
ON
    `user_group` FOR EACH ROW
BEGIN
    INSERT INTO leftMarkEntity(
        user_id,
        group_id,
        me_id,
        section_id
    )
SELECT
    mi.user_id,
    mi.group_id,
    mi.me_id,
    mi.section_id
FROM
    student_me_info AS mi
WHERE
    mi.user_id = old.user_id AND mi.group_id = old.group_id;
END;



CREATE TRIGGER `add_attach` AFTER INSERT ON `attachments` FOR EACH ROW BEGIN
INSERT INTO file_actions(
    user_id,
    me_id,
    fid,
    action,
    filename)
SELECT sfi.user_id, sfi.entity_id, sfi.fid, "UPLOAD", sfi.filename FROM student_file_info AS sfi

WHERE new.id = sfi.fid;
END;

CREATE TRIGGER `update_attach` AFTER UPDATE ON `attachments` FOR EACH ROW BEGIN
INSERT INTO file_actions(
    user_id,
    me_id,
    fid,
    action,
    filename)
SELECT sfi.user_id, sfi.entity_id, sfi.fid, "UPDATE", sfi.filename FROM student_file_info AS sfi

WHERE new.id = sfi.fid;

END;

INSERT INTO `marked_entities` (`id`, `title`, `description`, `sid`, `is_group_work`, `due_at`, `created_at`, `updated_at`) VALUES
(1, 'abc', '555', 1, 0, '2022-03-15 19:15:48', '2022-03-15 19:15:48', '2022-03-15 19:15:48'),
(2, '123', 'test', 5, 1, '2022-04-28 04:00:00', '2022-04-18 01:02:09', '2022-04-18 01:02:09'),
(3, '345', 'test', 5, 1, '2022-04-30 04:00:00', '2022-04-18 02:48:57', '2022-04-18 02:48:57'),
(4, 'assignment 1', 'test 1', 2, 1, '2022-04-26 04:00:00', '2022-04-18 02:49:53', '2022-04-18 02:49:53'),
(5, '5555', '5555', 4, 1, '2022-04-30 04:00:00', '2022-04-18 11:39:37', '2022-04-18 11:39:37'),
(6, 'COMP5531 NN A1', 'COMP5531 NN A1', 1, 1, '2022-04-30 02:00:13', '2022-04-19 02:00:13', '2022-04-19 02:00:13'),
(7, 'COMP5531 NN A2', 'COMP5531 NN A2', 1, 1, '2022-04-30 02:00:13', '2022-04-19 02:00:13', '2022-04-19 02:00:13'),
(8, 'COMP5531 NI A1', 'COMP5531 NI A1', 2, 1, '2022-04-30 02:00:13', '2022-04-19 02:00:13', '2022-04-19 02:00:13'),
(9, 'COMP5541 DD A1', 'COMP5531 DD A1', 3, 1, '2022-04-30 02:00:13', '2022-04-19 02:00:13', '2022-04-19 02:00:13'),
(10, 'COMP5541 DD A2', 'COMP5531 DD A2', 3, 1, '2022-04-30 02:00:13', '2022-04-19 02:00:13', '2022-04-19 02:00:13'),
(11, 'COMP5541 DD A3', 'COMP5531 DD A3', 3, 1, '2022-04-30 02:00:13', '2022-04-19 02:00:13', '2022-04-19 02:00:13'),
(12, 'COMP5541 DI A1', 'COMP5531 DI A1', 4, 1, '2022-04-30 02:00:13', '2022-04-19 02:00:13', '2022-04-19 02:00:13');

INSERT INTO `attachments` (`id`,`file_id`,`file_filename`,`file_size`,`attachable_id`,`attachable_type`,`created_at`,`updated_at`) 
VALUES ('1', '62648944b6855COMP 5531 Week 4 Lab.pdf', 'COMP 5531 Week 4 Lab.pdf', '132487', '1', 'MarkedEntityFile', '2022-04-23 19:18:28', '2022-04-23 19:18:28');

INSERT INTO `marked_entity_files` (`id`,`entity_id`,`user_id`, `title`,`description`, `created_at`,`updated_at`)
VALUES ('1', '6', '11', 'ER Diagram', 'The ER Diagram of the final project.', '2022-04-23 19:18:28', '2022-04-23 19:18:28');