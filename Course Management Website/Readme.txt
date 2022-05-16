COMP 5531 / Winter 2022
Section NN
Instructor: Dr. Bipin C. Desai
Project
Group:COMP 5511_group_5
Leader:40216952/xiangchen ZHU/x_zhu202@encs.concordia.ca
Members:40206157/xiaofan XING/x_xiaof@encs.concordia.ca
40207381/jessicaCHEN/c_jess@encs.concordia.ca
40216773/zongquan MAO/zo_mao@encs.concordia.ca

Changes since Demo: 
- Refined ER diagram  
- Refined User Interface: Took away unnecessary white space 
 	- Fixed spelling mistakes 
 	- Created Quick Navigation Menu for easier access to certain locations 
 	- Put more restrictions on user input and implemented error messages if input is invalid 
- Ensured Instructors cannot assign Students to two groups at the same time 
- Completed discussion forum with attachment uploads 
- When assigning Students to course sections or groups, admin will use Student ID instead of User ID. 

Installation Guide: 
1. Download the zip file and extract the contents into a folder on your desktop
2. Open the folder Proj_Group5
3. Please change the password credentials in the following files: 
 	- config.php 
	- public -> sub_forum -> config.php
4. Save the file and open a terminal in the COMP-5531-w22 folder
5. Type "php -S localhost:4000"
6. To set up the database and tables: http://localhost:4000/install.php
7. To go to the go to the main page or to login: http://localhost:4000/public/index.php


Login Credentials to our CGA application: 
Administrator: 
	admin@concordia.ca
	root 

Instructor:
	instructor@concordia.ca 
	0000

Student:
	student@concordia.ca
	123456