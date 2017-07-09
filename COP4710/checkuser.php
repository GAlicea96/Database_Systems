<?php
  require_once 'functions.php';

  if (isset($_POST['user']))
  {
    $user   = sanitizeString($_POST['user']);
    if (($result = queryMysql("SELECT * FROM Student WHERE user='$user'")) && $result->num_rows);
    elseif (($result = queryMysql("SELECT * FROM Super_admin WHERE user='$user'")) && $result->num_rows);
    else (($result = queryMysql("SELECT * FROM Admin WHERE user='$user'")) && $result->num_rows);

    if ($result->num_rows)
      echo  "<span class='taken'>&nbsp;&#x2718; " .
            "This username is taken</span>";
    else
      echo "<span class='available'>&nbsp;&#x2714; " .
           "This username is available</span>";
  }
?>
