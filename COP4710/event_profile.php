<?php

  $search = "active";

  require_once 'header.php';

  echo "<script src='javascript/follow_event.js'></script>";
  echo "<script src='javascript/unfollow_event.js'></script>";

  if (!$loggedin)
  {
    echo "<h3>You must be logged in to view this page.</h3>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }

  if (isset($_GET['event']) && !empty($_GET['event']))
    $event = sanitizeString($_GET['event']);

  $eresult = queryMysql("SELECT * FROM Event WHERE eid='$event'");
  $eresult = $eresult->fetch_array();
  $scope = $eresult['scope'];

  if (($result = queryMysql("SELECT * FROM Student WHERE user='$user'")) && $result->num_rows);
  elseif (($result = queryMysql("SELECT * FROM Super_admin WHERE user='$user'")) && $result->num_rows);
  else (($result = queryMysql("SELECT * FROM Admin WHERE user='$user'")) && $result->num_rows);

  $row = $result->fetch_array();

  if ($scope == 'Private')
  {
    $uni = $row['uni'];
    $unievent = $eresult['associated_uni'];
    if ($uni != $unievent)
    {
      echo "<h3>You do not have permission to view this page.</h3>";
      echo "</div><br><br>";
      require_once 'footer.php';
      die();
    }
  }
  elseif ($scope == 'RSO')
  {
    $admin = $eresult['user'];
    $rso = queryMysql("SELECT * FROM Rso WHERE user='$admin'");
    $rso = $rso->fetch_array();
    $rso = $rso['name'];
    $rsoresult = queryMysql("SELECT * FROM Follows_Rso WHERE user='$user' AND name='$rso'");
    if (!$rsoresult->num_rows)
      $rsoresult = queryMysql("SELECT * FROM Rso WHERE user='$user' AND name='$rso'");

    if (!$rsoresult->num_rows)
    {
      echo "<h3>You do not have permission to view this page.</h3>";
      echo "</div><br><br>";
      require_once 'footer.php';
      die();
    }
  }

  showEventProfile($event, $user);

  $result = queryMysql("SELECT * FROM Follows_event WHERE user='$user' AND eid='$event'");

  if ($result->num_rows)
    echo "<button onclick='unfollow_event(\"$event\", \"$user\")' class='btn-primary'>Unfollow Event</button><span id='unfollowevent'></span><br><br>";
  else
  echo "<button onclick='follow_event(\"$event\", \"$user\")' class='btn-primary'>Follow Event</button><span id='followevent'></span>";

  echo "</div><br><br>";
  require_once 'footer.php';
?>

  <br>
</body>
</html>
