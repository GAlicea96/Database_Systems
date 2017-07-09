<?php

  require_once 'functions.php';

  if(isset($_GET['rso']) && !empty($_GET['rso']) AND
    isset($_GET['user']) && !empty($_GET['user']))
  {
    $user = sanitizeString($_GET['user']);
    $rso = sanitizeString($_GET['rso']);

    if (($result = queryMysql("SELECT * FROM Student WHERE user='$user'")) && !$result->num_rows)
    {
      echo "<span class='taken'> &#x2718 Only students may unfollow RSOs.</span>";
      die();
    }
    elseif (($result = queryMysql("SELECT * FROM Follows_Rso WHERE user='$user' AND name='$rso'")) && !$result->num_rows)
    {
      echo "<span class='taken'> &#x2718 You are not currently following this RSO.</span>";
      die();
    }

    queryMysql("DELETE FROM Follows_Rso WHERE user='$user' AND name='$rso'");
    queryMysql("UPDATE Rso SET num_students = num_students - 1 WHERE name='$rso'");

    $result = queryMysql("SELECT * FROM Rso WHERE name='$rso'");
    $row = $result->fetch_array();
    $num_students = $row['num_students'];
    $admin = $row['user'];

    if ($num_students < 5)
    {
      queryMysql("UPDATE Admin SET active = '0' WHERE user='$admin'");
      queryMysql("INSERT INTO Student(user, pass, email, uni, hash, active) SELECT * FROM Admin WHERE user = '$admin'");
      queryMysql("INSERT INTO Follows_Rso VALUES('$admin', '$rso')");
    }

    echo "<span class='available'> &#x2714 You are no longer following this RSO.</span>";
  }
  else
    echo "<span class='taken'> &#x2718 Failed to unfollow RSO.</span>";
?>
