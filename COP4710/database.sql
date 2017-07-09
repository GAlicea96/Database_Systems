
DROP DATABASE IF EXISTS Unievents;

CREATE DATABASE Unievents;

CREATE USER 'COP4710'@'localhost' IDENTIFIED BY 'Password1212';

GRANT ALL PRIVILEGES ON Unievents.* TO 'COP4710'@'localhost';

FLUSH PRIVILEGES;

USE Unievents;

CREATE TABLE Super_admin(
             user VARCHAR(16) PRIMARY KEY,
             pass VARCHAR(32),
             email VARCHAR(50),
             uni VARCHAR(50),
             hash VARCHAR(32),
             active INT UNSIGNED,
             INDEX(user(6)));

CREATE TABLE Admin(
             user VARCHAR(16) PRIMARY KEY,
             pass VARCHAR(32),
             email VARCHAR(50),
             uni VARCHAR(50),
             hash VARCHAR(32),
             active INT UNSIGNED,
             INDEX(user(6)));

CREATE TABLE Student(
             user VARCHAR(16) PRIMARY KEY,
             pass VARCHAR(32),
             email VARCHAR(50),
             uni VARCHAR(50),
             hash VARCHAR(32),
             active INT UNSIGNED,
             INDEX(user(6)));

CREATE TABLE Profile(
             user VARCHAR(16) PRIMARY KEY,
             about VARCHAR(4096),
             uni VARCHAR(50),
             age INT UNSIGNED,
             sex VARCHAR(10),
             grade VARCHAR(10),
             grad VARCHAR(10),
             major VARCHAR(100),
             minor VARCHAR(100),
             INDEX(user(6)));

CREATE TABLE University(
             university_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
             name VARCHAR(50),
             location VARCHAR(50),
             description VARCHAR(4096),
             num_students INT UNSIGNED,
             INDEX(name(6)),
             INDEX(university_id));

CREATE TABLE Creates_uni(
             university_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
             user VARCHAR(16),
             FOREIGN KEY (university_id) REFERENCES University(university_id),
             FOREIGN KEY (user) REFERENCES Super_admin(user));

CREATE TABLE Affiliated_uni(
             university_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
             user VARCHAR(16) PRIMARY KEY,
             FOREIGN KEY (university_id) REFERENCES University(university_id),
             FOREIGN KEY (user) REFERENCES Admin(user));

CREATE TABLE Event(
             eid INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
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
             FOREIGN KEY (user) REFERENCES Admin (user) ON DELETE CASCADE);

CREATE TABLE Comments(
             user VARCHAR(16),
             eid INT UNSIGNED AUTO_INCREMENT,
             star_count VARCHAR(1),
             text VARCHAR(4096),
             date_time VARCHAR(50) PRIMARY KEY,
             FOREIGN KEY (user) REFERENCES Student (user),
             FOREIGN KEY (eid) REFERENCES Event (eid));

CREATE TABLE Follows_event(
             user VARCHAR(16),
             eid INT UNSIGNED AUTO_INCREMENT,
             FOREIGN KEY (user) REFERENCES Student (user),
             FOREIGN KEY (eid) REFERENCES Event (eid));

CREATE TABLE Rso(
             name VARCHAR(25) PRIMARY KEY,
             user VARCHAR(16),
             description VARCHAR(4096),
             type VARCHAR(25),
             num_students INT UNSIGNED,
             domain VARCHAR(20),
             FOREIGN KEY (user) REFERENCES Admin (user) ON DELETE CASCADE);

CREATE TABLE Follows_Rso(
             user VARCHAR(16),
             name VARCHAR(25),
             FOREIGN KEY (user) REFERENCES Student (user) ON DELETE CASCADE,
             FOREIGN KEY (name) REFERENCES Rso (name));

CREATE TABLE Messages(
             id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
             auth VARCHAR(16),
             recip VARCHAR(16),
             pm CHAR(1),
             time INT UNSIGNED,
             message VARCHAR(4096),
             INDEX(auth(6)),
             INDEX(recip(6)));

CREATE TABLE Friends(
             user VARCHAR(16),
             friend VARCHAR(16),
             INDEX(user(6)),
             INDEX(friend(6)));

/* SQL Statements relating to RSOs:
**
** --Queries once a student has initially petitioned an RSO--
** queryMysql("UPDATE Admin SET active = 0 WHERE user='$user'");
** queryMysql("INSERT INTO Rso VALUES('$name', '$user', '$description', '$type', '$num_students', '$domain')");
** queryMysql("INSERT INTO Follows_Rso VALUES('$user', '$name')");
**
** --Queries for each subsequent student that petitions RSO (num_students < 5)--
** queryMysql("UPDATE Rso SET num_students = num_students + 1 WHERE name='$name'");
** queryMysql("INSERT INTO Follows_Rso VALUES('$user', '$name')");
**
** --Queries once the 5th student has petitioned RSO (num_students == 5)--
** queryMysql("DELETE FROM Follows_Rso WHERE user='$admin'");
** queryMysql("DELETE FROM Student WHERE user='$admin'");
** queryMysql("UPDATE Admin SET active = 1 WHERE user='$admin'");
**
** --Queries for a student to follow an active RSO--
** queryMysql("INSERT INTO Follows_Rso VALUES('$user', '$rso')");
** queryMysql("UPDATE Rso SET num_students = num_students + 1 WHERE name='$rso'");
**
** --Queries when an active RSO drops to below 5 students--
** queryMysql("UPDATE Admin SET active = '0' WHERE user='$admin'");
** queryMysql("INSERT INTO Student(user, pass, email, uni, hash, active) SELECT * FROM Admin WHERE user = '$admin'");
** queryMysql("INSERT INTO Follows_Rso VALUES('$admin', '$rso')");
*/

/* SQL Statements relating to Events:
**
** --Queries to create new Event (a unique event is added for every hr block that an event takes up)--
** for ($i = 0; $i < $chunk; $i+=100)
**{
**  $time = $start_time + $i;
**  queryMysql("INSERT INTO Event VALUES( NULL, '$user', '$description', '$name', '$category',
**             '$location', '$longitude', '$latitude', '$date','$time', '$end_time', '$start_pm',
**             '$end_pm', '$contact_name', '$contact_phone', '$contact_email', FALSE, '$associated_uni', '$scope', '$rso_event', '$max_occupancy', '$max_occupancy')");
**}
**
** --Queries to follow an existing event (assuming they meet all constraints to do so)--
** queryMysql("INSERT INTO Follows_event VALUES('$user', '$event')");
** queryMysql("UPDATE Event SET availability = availability - 1 WHERE eid='$event'");
*/

/* SQL Statements relating to Comments (assuming constraints have passed):
**
** --Queries to create a comment--
** queryMysql("INSERT INTO Comments VALUES('$user', '$eid', '$rating', '$text', '$date')");
**
** --Queries to modify a comment--
** queryMysql("DELETE FROM Comments WHERE eid='$eid' AND user='$user' AND date_time='$date_time'");
** queryMysql("INSERT INTO Comments VALUES('$user', '$eid', '$rating', '$text', '$date')");
**
** --Queries to delete a comment--
** queryMysql("DELETE FROM Comments WHERE eid='$eid' AND user='$user' AND date_time='$date_time'");
*/

/* SQL Statements relating to searches:
**
** --Queries to search for an event by University only--
** $event = queryMysql("SELECT * FROM Event WHERE (rso_event='1' AND associated_uni='$uni' AND location='$location')
**                   OR (approved_by_super='1' AND associated_uni='$uni' AND location='$location')
**                   OR (scope='Public' AND associated_uni='$uni' AND location='$location')");
**
** --Queries to search for an event by University and Location--
** $event = queryMysql("SELECT * FROM Event WHERE (rso_event='1' AND associated_uni='$uni')
**                   OR (approved_by_super='1' AND associated_uni='$uni')
**                   OR (scope='Public' AND associated_uni='$uni')");
**
** --Queries to check for RSO Constraint--
** if ($rso_event)
** {
**   $admin = $eventi['user'];
**   $rso = queryMysql("SELECT * FROM Rso WHERE user='$admin'");
**   $rso = $rso->fetch_array();
**   $rso = $rso['name'];
**   $follows = queryMysql("SELECT * FROM Follows_Rso WHERE user='$user' AND name='$rso'");
**   if (!$follows->num_rows)
**     continue;
** }
**
** --Check if student is in University if scope is private--
** elseif ($scope == "Private")
** {
**   $user_uni = $result['uni'];
**   if ($user_uni != $uni)
**     continue;
** }
**
** --Queries to search for an RSO by University only--
** $rso = queryMysql("SELECT * FROM Rso WHERE user=(SELECT user FROM Admin WHERE uni='$uni')");
**
** --Queries to search for an RSO by University and Location--
** $rso = queryMysql("SELECT * FROM Rso WHERE user=(SELECT user FROM Admin WHERE uni='$uni') AND type='$category'");
**
** --Query to ensure that student attempting to follow RSO attends RSO's university--
** queryMysql("SELECT * FROM Student WHERE user='$user' AND
**          uni=(SELECT uni FROM Admin WHERE
**          user=(SELECT user FROM Rso WHERE name='$rso'))");
*/
