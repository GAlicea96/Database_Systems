<?php

  require_once 'header.php';

  if (!$loggedin)
  {
    echo "<h3>You are not currently logged in.</h3>";
    die();
  }

  if (($result = queryMysql("SELECT * FROM Super_admin WHERE user='$user'")) && $result->num_rows)
  {
    $result = $result->fetch_array();
    echo "<h3>Super admins do not have followed or owned events.</h3>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }
  elseif (($result = queryMysql("SELECT * FROM Admin WHERE user='$user'")) && $result->num_rows)
  {
    $result = $result->fetch_array();
    echo "<h3>Your Owned events</h3>";

    $event = queryMysql("SELECT * FROM Event WHERE user='$user'");

    while ($eventi = $event->fetch_array())
    {
      $name = $eventi['name'];
      $location = $eventi['location'];
      $date = $eventi['date'];
      $id = $eventi['eid'];
      $start_time = $eventi['start_time'];
      $start_pm = $eventi['start_pm'];
      if ($start_pm) $pm = "PM";
      else $pm = "AM";
      $eid = $eventi['eid'];
      $stars = (findRating($eid) == 0) ? '-' : findRating($eid);
      $description = $eventi['description'];
      $contact_person = $eventi['contact_name'];
      echo "<div class='row'><div id='upevent' class='col-xs-12'>";
      echo "<h2><font color='blue'>Event Title:</font> $name ($stars stars)</h4>";
      echo "<h3><font color='blue'>Date/Time:</font> $date, $start_time $pm</h2>";
      echo "<h3><font color='blue'>Location:</font> $location</h2>";
      echo "<h3><font color='blue'>Description:</font> $description</h3>";
      echo "<h3><font color='blue'>Contact Person:</font> $contact_person</h3>";
      echo "<a href='event_profile.php?event=$eid'><font color='blue'>Link to Event Page</font></a>";
      echo "</div></div><br>";
    }
  }
  elseif (($result = queryMysql("SELECT * FROM Student WHERE (user='$user' AND rso_event='1') OR (user='$user' AND approved_by_super='1')")) && $result->num_rows)
  {
    $result = $result->fetch_array();
    echo "<h3>Your Followed events</h3>";

    $event = queryMysql("SELECT * FROM Follows_event WHERE user='$user'");

    while ($eventi = $event->fetch_array())
    {
      $name = $eventi['name'];
      $location = $eventi['location'];
      $date = $eventi['date'];
      $id = $eventi['eid'];
      $start_time = $eventi['start_time'];
      $start_pm = $eventi['start_pm'];
      if ($start_pm) $pm = "PM";
      else $pm = "AM";
      $eid = $eventi['eid'];
      $stars = (findRating($eid) == 0) ? '-' : findRating($eid);
      $description = $eventi['description'];
      $contact_person = $eventi['contact_name'];
      echo "<div class='row'><div id='upevent' class='col-xs-12'>";
      echo "<h2><font color='blue'>Event Title:</font> $name ($stars stars)</h4>";
      echo "<h3><font color='blue'>Date/Time:</font> $date, $start_time $pm</h2>";
      echo "<h3><font color='blue'>Location:</font> $location</h2>";
      echo "<h3><font color='blue'>Description:</font> $description</h3>";
      echo "<h3><font color='blue'>Contact Person:</font> $contact_person</h3>";
      echo "<a href='event_profile.php?event=$eid'><font color='blue'>Link to Event Page</font></a>";
      echo "</div></div><br>";
    }
  }

  echo "</div><br><br>";
  require_once 'footer.php';

?>
