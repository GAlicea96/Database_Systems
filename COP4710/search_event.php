<?php

  require_once 'header.php';

  if (!$loggedin)
  {
    echo "<h3>You are not currently logged in.</h3>";
    die();
  }

  $result = queryMysql("SELECT * FROM Student WHERE user='$user'");
  if (!$result->num_rows)
  {
    echo "<h3>Only students may search for events.</h3>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }
  $result = $result->fetch_array();

  echo "<form method='post' action='search_event.php'>$error" .
  "<span class='fieldname'>University</span>" .
  "<input type='text' maxlength='50' name='university' value='$university' placeholder='Do not leave blank'>$university<br>" .
  "<span class='fieldname'>Location</span>" .
  "<input type='text' maxlength='50' name='location' value='$location' placeholder='Leave blank for full list'>$location<br>" .
  "<span class='fieldname'>&nbsp;</span><input class='btn-primary' type='submit'" .
  "value='Search'></form>";

  if (isset($_POST['location']) && !empty($_POST['location']))
    $location = sanitizeString($_POST['location']);
  if (isset($_POST['university']) && !empty($_POST['university']))
    $university = sanitizeString($_POST['university']);

  if ($university == "")
  {
    echo "<h3>You must at least fill out the university field.</h3>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }

  $uni = strtolower($university);

  if ($location != "")
    $event = queryMysql("SELECT * FROM Event WHERE (rso_event='1' AND associated_uni='$uni' AND location='$location')
                         OR (approved_by_super='1' AND associated_uni='$uni' AND location='$location')");
  else
    $event = queryMysql("SELECT * FROM Event WHERE (rso_event='1' AND associated_uni='$uni')
                         OR (approved_by_super='1' AND associated_uni='$uni')");

  if ($location != "")
    echo "<h3>Events in $uni at $location";
  else
    echo "<h3>Events in $uni</h3>";

  while ($eventi = $event->fetch_array())
  {
    $rso_event = $eventi['rso_event'];
    $scope = $eventi['scope'];

    if ($rso_event)
    {
      $admin = $eventi['user'];
      $rso = queryMysql("SELECT * FROM Rso WHERE user='$admin'");
      $rso = $rso->fetch_array();
      $rso = $rso['name'];
      $follows = queryMysql("SELECT * FROM Follows_Rso WHERE user='$user' AND name='$rso'");
      if (!$follows->num_rows)
        continue;
    }
    elseif ($scope == "Private")
    {
      $user_uni = $result['uni'];
      if ($user_uni != $uni)
        continue;
    }

    $name = $eventi['name'];
    if ($previous != $name)
    {
      $previous = $name;
      $location = $eventi['location'];
      $date = $eventi['date'];
      $id = $eventi['eid'];

      if( strtotime($date) < strtotime('now') )
      {
        queryMysql("DELETE FROM Follows_event WHERE eid='$id'");
        queryMysql("DELETE FROM Comments WHERE eid='$id'");
        queryMysql("DELETE FROM Event WHERE eid='$id'");
        continue;
      }

      $start_time = $eventi['start_time'];
      $start_pm = $eventi['start_pm'];

      if ($start_pm)
        $pm = "PM";
      else
        $pm = "AM";

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

</body></html>
