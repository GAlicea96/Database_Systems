<?php

  require_once 'functions.php';

  if(isset($_GET['rso']) && !empty($_GET['rso']) AND
    isset($_GET['user']) && !empty($_GET['user']))
  {
    $user = sanitizeString($_GET['user']);
    $rso = sanitizeString($_GET['rso']);

    if (($result = queryMysql("SELECT * FROM Student WHERE user='$user' AND
                uni=(SELECT uni FROM Admin WHERE
                user=(SELECT user FROM Rso WHERE name='$rso'))")) && !$result->num_rows)
    {
      echo "<span class='taken'> &#x2718 Only students of this RSO's University may follow RSOs.</span>";
      die();
    }
    elseif (($result = queryMysql("SELECT * FROM Follows_Rso WHERE user='$user' AND name='$rso'")) && $result->num_rows)
    {
      echo "<span class='taken'> &#x2718 You are already following this RSO.</span>";
      die();
    }

    queryMysql("INSERT INTO Follows_Rso VALUES('$user', '$rso')");
    queryMysql("UPDATE Rso SET num_students = num_students + 1 WHERE name='$rso'");

    echo "<span class='available'> &#x2714 You are now following this RSO.</span>";
  }
  else
    echo "<span class='taken'> &#x2718 Failed to follow RSO.</span>";
?>
