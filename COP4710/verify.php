<?php
  require_once 'functions.php';
  require_once 'header.php';
?>

<h1 align='center'>UniversityEvents Account Verification</h1><br>

<?php

  if(isset($_GET['email']) && !empty($_GET['email']) AND
    isset($_GET['hash']) && !empty($_GET['hash']) AND
    isset($_GET['type']) && !empty($_GET['type']))
  {
    $email = sanitizeString($_GET['email']);
    $hash = sanitizeString($_GET['hash']);
    $type = sanitizeString($_GET['type']);
    $result = queryMysql("SELECT email, hash, active FROM $type WHERE email='$email' AND hash='$hash' AND active='0'") or die($result->error);

    if($result->num_rows)
    {
      queryMysql("UPDATE $type SET active='1' WHERE email='$email' AND hash='$hash' AND active='0'") or die($result->error);
      echo "<div class='statusmsg'><h2 align='center'>Your account has been activated, you can now login</h2></div>";
    }
    else
    {
      echo "<div class='statusmsg'><h2 align='center'>The url is either invalid or you have already activated your account.</h2></div>";
    }
  }
  else
  {
    echo "<div class='statusmsg'><h2 align='center'>Invalid approach, please use the link that was sent to your email.</h2></div>";
  }

  echo "</div><br><br>";
  require_once 'footer.php';

?>

</body>
</html>
