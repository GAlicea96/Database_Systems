<!DOCTYPE html>
<html>
  <head>
    <title>Setting up database</title>
  </head>
  <body>

    <h1><u>Database Setup</u></h1>

<?php

  echo "<h4>Connecting to MySQL..<h4>";

  $connection = new mysqli('localhost', 'root', 'misty971');
  if ($connection->connect_error) die($connection->connect_error);

  echo "<h4>Connected Successfully.<h4>";

  echo "<h4>Creating Database Unievents..<h4>";

  $result = $connection->query('CREATE DATABASE IF NOT EXISTS Unievents');
  if (!$result) die($connection->error);

  echo "<h4>Database Unievents created successfully.<h4>";

  require_once 'functions.php';

  echo "<h4>Creating tables..<h4>";

  //create tables here
  createTable('Super_admin',
              'user VARCHAR(16) PRIMARY KEY,
               pass VARCHAR(32),
               email VARCHAR(50),
               uni VARCHAR(50),
               hash VARCHAR(32),
               active INT UNSIGNED,
               INDEX(user(6))');

  createTable('Admin',
              'user VARCHAR(16) PRIMARY KEY,
               pass VARCHAR(32),
               email VARCHAR(50),
               uni VARCHAR(50),
               hash VARCHAR(32),
               active INT UNSIGNED,
               INDEX(user(6))');

  createTable('Student',
              'user VARCHAR(16) PRIMARY KEY,
               pass VARCHAR(32),
               email VARCHAR(50),
               uni VARCHAR(50),
               hash VARCHAR(32),
               active INT UNSIGNED,
               INDEX(user(6))');

  createTable('Profile',
              'user VARCHAR(16) PRIMARY KEY,
               about VARCHAR(4096),
               uni VARCHAR(50),
               age INT UNSIGNED,
               sex VARCHAR(10),
               grade VARCHAR(10),
               grad VARCHAR(10),
               major VARCHAR(100),
               minor VARCHAR(100),
               INDEX(user(6))');

  createTable('University',
              'university_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
               name VARCHAR(50),
               location VARCHAR(50),
               description VARCHAR(4096),
               num_students INT UNSIGNED,
               INDEX(name(6)),
               INDEX(university_id)');

  createTable('Creates_uni',
              'university_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
               user VARCHAR(16),
               FOREIGN KEY (university_id) REFERENCES University(university_id),
               FOREIGN KEY (user) REFERENCES Super_admin(user)');

  createTable('Affiliated_uni',
              'university_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
               user VARCHAR(16) PRIMARY KEY,
               FOREIGN KEY (university_id) REFERENCES University(university_id),
               FOREIGN KEY (user) REFERENCES Admin(user)');

  createTable('Event',
              'eid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
               user VARCHAR(16),
               description VARCHAR(4096),
               name VARCHAR(30),
               category VARCHAR(30),
               location VARCHAR(50),
               longitude VARCHAR(10),
               latitude VARCHAR(10),
               date VARCHAR(15),
               start_time VARCHAR(20),
               end_time VARCHAR(20),
               start_pm BOOLEAN,
               end_pm BOOLEAN,
               contact_name VARCHAR(25),
               contact_phone VARCHAR(25),
               contact_email VARCHAR(30),
               approved_by_super BOOLEAN,
               associated_uni VARCHAR(50),
               scope VARCHAR(10),
               rso_event BOOLEAN,
               max_occupancy INT,
               availability INT,
               FOREIGN KEY (user) REFERENCES Admin (user) ON DELETE CASCADE');

  createTable('Comments',
              'user VARCHAR(16),
               eid INT UNSIGNED AUTO_INCREMENT,
               star_count VARCHAR(1),
               text VARCHAR(4096),
               date_time VARCHAR(50) PRIMARY KEY,
               FOREIGN KEY (user) REFERENCES Student (user),
               FOREIGN KEY (eid) REFERENCES Event (eid)');

  createTable('Follows_event',
              'user VARCHAR(16),
               eid INT UNSIGNED AUTO_INCREMENT,
               FOREIGN KEY (user) REFERENCES Student (user),
               FOREIGN KEY (eid) REFERENCES Event (eid)');

  createTable('Rso',
              'name VARCHAR(25) PRIMARY KEY,
               user VARCHAR(16),
               description VARCHAR(4096),
               type VARCHAR(25),
               num_students INT UNSIGNED,
               domain VARCHAR(20),
               FOREIGN KEY (user) REFERENCES Admin (user) ON DELETE CASCADE');

  createTable('Follows_Rso',
              'user VARCHAR(16),
               name VARCHAR(25),
               FOREIGN KEY (user) REFERENCES Student (user) ON DELETE CASCADE,
               FOREIGN KEY (name) REFERENCES Rso (name)');

  createTable('Messages',
              'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
               auth VARCHAR(16),
               recip VARCHAR(16),
               pm CHAR(1),
               time INT UNSIGNED,
               message VARCHAR(4096),
               INDEX(auth(6)),
               INDEX(recip(6))');

  createTable('Friends',
              'user VARCHAR(16),
               friend VARCHAR(16),
               INDEX(user(6)),
               INDEX(friend(6))');

  echo "<h4>Tables created successfully.<h4>";
//
  queryMysql("INSERT INTO Super_admin VALUES(
             'Superadmin1',
             '5f4dcc3b5aa765d61d8327deb882cf99',
             'Superadmin1@ucf.edu',
             'university of Central florida',
             'hash',
             '1')");

  queryMysql("INSERT INTO Profile VALUES('Superadmin1', NULL, 'university of central florida', NULL, NULL, NULL, NULL, NULL, NULL)");

  queryMysql("INSERT INTO University VALUES(NULL, 'university of central florida', NULL, NULL, NULL)");

  queryMysql("INSERT INTO Creates_uni VALUES('1', 'Superadmin1')");
//
  queryMysql("INSERT INTO Super_admin VALUES(
             'Superadmin2',
             '5f4dcc3b5aa765d61d8327deb882cf99',
             'Superadmin2@uf.edu',
             'university of florida',
             'hash',
             '1')");

  queryMysql("INSERT INTO Profile VALUES('Superadmin2', NULL, 'university of florida', NULL, NULL, NULL, NULL, NULL, NULL)");

  queryMysql("INSERT INTO University VALUES(NULL, 'university of florida', NULL, NULL, NULL)");

  queryMysql("INSERT INTO Creates_uni VALUES('2', 'Superadmin2')");
//
  queryMysql("INSERT INTO Super_admin VALUES(
             'Superadmin3',
             '5f4dcc3b5aa765d61d8327deb882cf99',
             'Superadmin3@fsu.edu',
             'florida state university',
             'hash',
             '1')");

  queryMysql("INSERT INTO Profile VALUES('Superadmin3', NULL, 'florida state university', NULL, NULL, NULL, NULL, NULL, NULL)");

  queryMysql("INSERT INTO University VALUES(NULL, 'florida state university', NULL, NULL, NULL)");

  queryMysql("INSERT INTO Creates_uni VALUES('3', 'Superadmin3')");
//
  for ($i = 1; $i <= 10; $i++)
  {
    queryMysql("INSERT INTO Student VALUES('student$i', '5f4dcc3b5aa765d61d8327deb882cf99', 'student$i@knights.ucf.edu', 'university of central florida', 'hash', '1')");
    queryMysql("INSERT INTO Profile VALUES('student$i', NULL, 'university of central florida', NULL, NULL, NULL, NULL, NULL, NULL)");
  }
  for ($i = 11; $i <= 15; $i++)
  {
    queryMysql("INSERT INTO Student VALUES('student$i', '5f4dcc3b5aa765d61d8327deb882cf99', 'student$i@uf.edu', 'university of florida', 'hash', '1')");
    queryMysql("INSERT INTO Profile VALUES('student$i', NULL, 'university of florida', NULL, NULL, NULL, NULL, NULL, NULL)");
  }
  for ($i = 16; $i <= 20; $i++)
  {
    queryMysql("INSERT INTO Student VALUES('student$i', '5f4dcc3b5aa765d61d8327deb882cf99', 'student$i@fsu.edu', 'florida state university', 'hash', '1')");
    queryMysql("INSERT INTO Profile VALUES('student$i', NULL, 'florida state university', NULL, NULL, NULL, NULL, NULL, NULL)");
  }
//
  queryMysql("INSERT INTO Admin VALUES('student1', '5f4dcc3b5aa765d61d8327deb882cf99', 'student1@knights.ucf.edu', 'university of central florida', 'hash', '0')");

  queryMysql("INSERT INTO Rso VALUES('ucfRSO', 'student1', 'RSO for UCF', 'type1', '1', 'knights.ucf.edu')");

  queryMysql("INSERT INTO Follows_Rso VALUES('student1', 'ucfRSO')");

  for ($i = 2; $i <= 5; $i++)
  {
    queryMysql("UPDATE Rso SET num_students = num_students + 1 WHERE name='ucfRSO'");
    queryMysql("INSERT INTO Follows_Rso VALUES('student$i', 'ucfRSO')");
  }

  queryMysql("DELETE FROM Follows_Rso WHERE user='student1'");
  queryMysql("DELETE FROM Student WHERE user='student1'");
  queryMysql("UPDATE Admin SET active = 1 WHERE user='student1'");
//
  queryMysql("INSERT INTO Admin VALUES('student11', '5f4dcc3b5aa765d61d8327deb882cf99', 'student11@uf.edu', 'university of florida', 'hash', '0')");

  queryMysql("INSERT INTO Rso VALUES('ufRSO', 'student11', 'RSO for UF', 'type2', '1', 'uf.edu')");

  queryMysql("INSERT INTO Follows_Rso VALUES('student11', 'ufRSO')");

  for ($i = 12; $i <= 15; $i++)
  {
    queryMysql("UPDATE Rso SET num_students = num_students + 1 WHERE name='ufRSO'");
    queryMysql("INSERT INTO Follows_Rso VALUES('student$i', 'ufRSO')");
  }

  queryMysql("DELETE FROM Follows_Rso WHERE user='student11'");
  queryMysql("DELETE FROM Student WHERE user='student11'");
  queryMysql("UPDATE Admin SET active = 1 WHERE user='student11'");
//
  queryMysql("INSERT INTO Admin VALUES('student16', '5f4dcc3b5aa765d61d8327deb882cf99', 'student16@fsu.edu', 'florida state university', 'hash', '0')");

  queryMysql("INSERT INTO Rso VALUES('fsuRSO', 'student16', 'RSO for FSU', 'type3', '1', 'fsu.edu')");

  queryMysql("INSERT INTO Follows_Rso VALUES('student16', 'fsuRSO')");

  for ($i = 17; $i <= 20; $i++)
  {
    queryMysql("UPDATE Rso SET num_students = num_students + 1 WHERE name='fsuRSO'");
    queryMysql("INSERT INTO Follows_Rso VALUES('student$i', 'fsuRSO')");
  }

  queryMysql("DELETE FROM Follows_Rso WHERE user='student16'");
  queryMysql("DELETE FROM Student WHERE user='student16'");
  queryMysql("UPDATE Admin SET active = 1 WHERE user='student16'");
//
queryMysql("INSERT INTO Event VALUES( NULL, 'student1', 'description of event1', 'event1', 'category1',
          'Student Union', '-81.2004', '28.6018', '04/18/17','1400', '1500', '1',
          '1', 'Guillermo', '1234512345', 'student1@knights.ucf.edu', FALSE, 'university of central florida',
          'Public', '0', '100', '100')");

queryMysql("INSERT INTO Event VALUES( NULL, 'student1', 'description of event1', 'event1', 'category1',
          'Student Union', '-81.2004', '28.6018', '04/18/17','1500', '1600', '1',
          '1', 'Guillermo', '1234512345', 'student1@knights.ucf.edu', FALSE, 'university of central florida',
          'Public', '0', '100', '100')");

queryMysql("INSERT INTO Event VALUES( NULL, 'student1', 'description of event1', 'event1', 'category1',
          'Student Union', '-81.2004', '28.6018', '04/18/17','1600', '1700', '1',
          '1', 'Guillermo', '1234512345', 'student1@knights.ucf.edu', FALSE, 'university of central florida',
          'Public', '0', '100', '100')");
//
queryMysql("INSERT INTO Event VALUES( NULL, 'student1', 'description of event2', 'event2', 'category2',
          'Student Union', '-81.2004', '28.6018', '04/20/17','0800', '0900', '0',
          '0', 'Guillermo', '1234512345', 'student1@knights.ucf.edu', FALSE, 'university of central florida',
          'RSO', '1', '200', '200')");
//
queryMysql("INSERT INTO Event VALUES( NULL, 'student1', 'description of event3', 'event3', 'category3',
          'HEC', '-82.3449', '29.6476', '04/20/17','1000', '1100', '0',
          '0', 'Guillermo', '1234512345', 'student1@knights.ucf.edu', FALSE, 'university of central florida',
          'Private', '0', '50', '50')");
// uf
queryMysql("INSERT INTO Event VALUES( NULL, 'student11', 'description of event4', 'event4', 'category4',
          'Science Library', '-81.1974', '28.6005', '04/20/17','1000', '1100', '0',
          '0', 'Guillermo2', '1234512345', 'student11@uf.edu', FALSE, 'university of florida',
          'Private', '0', '50', '50')");
// fsu
queryMysql("INSERT INTO Event VALUES( NULL, 'student16', 'description of event5', 'event5', 'category5',
          'Ruby Diamond Concert Hall', '-84.2918', '30.4405', '04/20/17','1000', '1100', '0',
          '0', 'Guillermo3', '1234512345', 'student1@knights.ucf.edu', FALSE, 'university of central florida',
          'Public', '0', '50', '50')");
//
$date = date('jS \of F Y h:i:s A');
queryMysql("INSERT INTO Comments VALUES('student3', '1', '5', 'This will be an awesome event!', '$date')");
sleep(2);
$date = date('jS \of F Y h:i:s A');
queryMysql("INSERT INTO Comments VALUES('student7', '1', '1', 'This is going to be terrible.', '$date')");
sleep(2);
$date = date('jS \of F Y h:i:s A');
queryMysql("INSERT INTO Comments VALUES('student13', '4', '3', 'This will be an ok event.', '$date')");
sleep(2);
$date = date('jS \of F Y h:i:s A');
queryMysql("INSERT INTO Comments VALUES('student17', '5', '5', 'My opinion will change shortly.', '$date')");
//
sleep(2);
queryMysql("DELETE FROM Comments WHERE eid='5' AND user='student16' AND date_time='$date'");
$date2 = date('jS \of F Y h:i:s A');
queryMysql("INSERT INTO Comments VALUES('student17', '5', '1', 'My opinion has changed!', '$date2')");
//
queryMysql("DELETE FROM Comments WHERE eid='5' AND user='student17' AND date_time='$date'");

?>

    <br>
  </body>
</html>
