<?php

  require_once 'header.php';

  if (!$loggedin)
  {
    echo "<h4>You cannot petition a RSO if you are not logged in. <br>Please" .
         " login and then return.</h4>";
    die();
  }

  if(isset($_GET['name']) && !empty($_GET['name']) AND
     isset($_GET['domain']) && !empty($_GET['domain']))
  {
    $name = sanitizeString($_GET['name']);
    $domain = sanitizeString($_GET['domain']);

    echo "<h1 align='center'>RSO Petition Page For $name</h1><br>";

    if (($result = queryMysql("SELECT * FROM Rso WHERE name='$name'")) && $result->num_rows)
    {
      $row = $result->fetch_array();
      $num_students = $row['num_students'];
    }

    if($result->num_rows && $num_students < 5)
    {
      if (($result = queryMysql("SELECT * FROM Student WHERE user='$user'")) && $result->num_rows);
      elseif (($result = queryMysql("SELECT * FROM Super_admin WHERE user='$user'")) && $result->num_rows);
      else (($result = queryMysql("SELECT * FROM Admin WHERE user='$user'")) && $result->num_rows);

      $result = $result->fetch_array();
      $domain_student = $result['email'];
      $domain_student = getDomain($domain_student);

      if (strtolower($domain) != strtolower($domain_student))
      {echo "$domain and $domain_student";
        echo "<div class='statusmsg'><h2 align='center'>You must have the same email domain as the founder to petition this RSO.</h2></div>";
        echo "<br><br>";
        require_once 'footer.php';
        die();
      }

      if (($result = queryMysql("SELECT * FROM Follows_Rso WHERE user='$user'")) && $result->num_rows)
      {
        echo "<div class='statusmsg'><h2 align='center'>You have already petitioned this RSO. You may only do so once.</h2></div>";
        echo "<br><br>";
        require_once 'footer.php';
        die();
      }
      queryMysql("UPDATE Rso SET num_students = num_students + 1 WHERE name='$name'");
      queryMysql("INSERT INTO Follows_Rso VALUES('$user', '$name')");

      $result = queryMysql("SELECT * FROM Rso WHERE name='$name'");
      $row = $result->fetch_array();
      $num_students = $row['num_students'];
      $admin = $row['user'];

      if ($num_students >= 5)
      {
        $active = "<br>Congratulations, this RSO has now been made official, as it has reached the minimum number of students required to do so.";
        queryMysql("DELETE FROM Follows_Rso WHERE user='$admin'");
        queryMysql("DELETE FROM Student WHERE user='$admin'");
        queryMysql("UPDATE Admin SET active = 1 WHERE user='$admin'");
      }

      echo "<div class='statusmsg'><h2 align='center'>You have successfully petitioned the RSO.$active</h2></div>";
    }
    else
    {
      echo "<div class='statusmsg'><h2 align='center'>The url is either invalid or you may not petition this RSO at this time.</h2></div>";
    }
  }
  else
  {
    echo "<div class='statusmsg'><h2 align='center'>Invalid approach, please use the link that was provided to the RSO's founder.</h2></div>";
  }

  echo "<br><br>";
  require_once 'footer.php';
?>

</body>
</html>
