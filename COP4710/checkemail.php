<?php
  require_once 'functions.php';

  if (isset($_POST['email']))
  {
    $email   = sanitizeString($_POST['email']);
    if (($result = queryMysql("SELECT * FROM Student WHERE email='$email'")) && $result->num_rows);
    elseif (($result = queryMysql("SELECT * FROM Super_admin WHERE email='$email'")) && $result->num_rows);
    else (($result = queryMysql("SELECT * FROM Admin WHERE email='$email'")) && $result->num_rows);

    if ($result->num_rows)
      echo  "<span class='taken'>&nbsp;&#x2718; " .
            "This email is taken</span>";
    else
      echo "<span class='available'>&nbsp;&#x2714; " .
           "This email is available</span>";
  }
?>
