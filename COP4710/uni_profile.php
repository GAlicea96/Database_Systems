<?php

  $search = "active";

  require_once 'header.php';

  if (!$loggedin) die();

  if(isset($_GET['uni']) && !empty($_GET['uni']))
  {
    $uni = sanitizeString($_GET['uni']);
    showUniProfile($uni);
  }
  echo "</div><br><br>";
  require_once 'footer.php';
?>

  <br>
</body>
</html>
