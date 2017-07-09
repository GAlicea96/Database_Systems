<?php

  require_once 'header.php';

  if (!$loggedin)
  {
    echo "<h3>You are not currently logged in.</h3>";
    die();
  }

  if (isset($_GET['event']) && !empty($_GET['event']) &&
      isset($_GET['date_time']) && !empty($_GET['date_time']))
  {
    $eid = sanitizeString($_GET['event']);
    $date_time = sanitizeString($_GET['date_time']);
  }
  else
  {
    echo "<h3>Invalid URL path passed.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }

  $result = queryMysql("SELECT * FROM Student WHERE user='$user'");
  if (!$result->num_rows)
  {
    echo "<h3>Only students may comment on events.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }

  if ((queryMysql("DELETE FROM Comments WHERE eid='$eid' AND user='$user' AND date_time='$date_time'")))
    header('Location: event_profile.php'."?event=".$eid.'#comment');
  else
    echo "<h3>Comment could not be deleted.</h3>";

  echo "<br><br>";
  require_once 'footer.php';

?>
