<?php

  require_once 'functions.php';

  if(isset($_GET['event']) && !empty($_GET['event']) AND
    isset($_GET['user']) && !empty($_GET['user']))
  {

    $user = sanitizeString($_GET['user']);
    $event = sanitizeString($_GET['event']);

    $result = queryMysql("SELECT * FROM Event WHERE eid='$event'");
    if (!$result->num_rows)
    {
      echo "<span class='taken'> &#x2718 Event does not exist.</span>";
      die();
    }
    if (($result = queryMysql("SELECT * FROM Student WHERE user='$user'")) && !$result->num_rows)
    {
      echo "<span class='taken'> &#x2718 Only Students may follow events.</span>";
      die();
    }
    elseif (($result = queryMysql("SELECT * FROM Follows_event WHERE user='$user' AND eid='$event'")) && $result->num_rows)
    {
      echo "<span class='taken'> &#x2718 You are already following this Event.</span>";
      die();
    }

    queryMysql("INSERT INTO Follows_event VALUES('$user', '$event')");
    queryMysql("UPDATE Event SET availability = availability - 1 WHERE eid='$event'");

    echo "<span class='available'> &#x2714 You are now following this event.</span>";
  }
  else
    echo "<span> class='taken'> &#x2718 You cannot follow this event.</span>";
?>
