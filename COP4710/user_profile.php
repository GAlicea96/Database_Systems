<?php

  $profile = "active";

  require_once 'header.php';

  if (!$loggedin) die();

  if(isset($_GET['user']) && !empty($_GET['user']))
    $user = sanitizeString($_GET['user']);

  showUserProfile($user);
  echo "</div><br><br>";
  require_once 'footer.php';
?>

  <br>
</body>
</html>
