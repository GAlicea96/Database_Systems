<?php

  require_once 'header.php';

  if (!$loggedin)
  {
    echo "<h3>You must be logged in to view this page.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }

  if (isset($_GET['eid']) && !empty($_GET['eid']))
    $eid = sanitizeString($_GET['eid']);
  else
  {
    echo "<h3>Invalid URL entered.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }

  $result = queryMysql("SELECT * FROM Super_admin WHERE user='$user'");

  if ($result->num_rows)
  {
    $result = $result->fetch_array();
    $event = queryMysql("SELECT * FROM Event WHERE eid='$eid' AND approved_by_super='0' AND rso_event = '0'");
    $event = $event->fetch_array();
    $event_uni = strtolower($event['associated_uni']);
    $uni = strtolower($result['uni']);
    if ($event_uni == $uni)
    {
      queryMysql("UPDATE Event SET approved_by_super='1' WHERE eid='$eid'");
      echo "<h3>You have successfully approved this event. It is now active.</h3>";
    }
    else
    {
      echo "<h3>You must be the Superadmin of the event's university in order to approve it, or this event has already been approved.</h3>";
      echo "<br><br>";
      require_once 'footer.php';
      die();
    }
  }
  else
  {
    echo "<h3>You must be a Superadmin to approve events.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }

  echo "<br><br>";
  require_once 'footer.php';

?>
