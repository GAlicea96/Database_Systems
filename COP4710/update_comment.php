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

  $comment = queryMysql("SELECT * FROM Comments WHERE eid='$eid' AND user='$user' AND date_time='$date_time'");

  if (!$comment->num_rows)
  {
    echo "<h3>You cannot update a comment that has not already been created.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }
  else
  {
    $comment = $comment->fetch_array();
    echo "<h2>Original Comment</h2>";
    $name = $comment['user'];
    $text = $comment['text'];
    $date_time = $comment['date_time'];
    $author = $comment['user'];
    $star_count = $comment['star_count'];
    echo "<div id='upevent'>";
    echo "<h3>$name on $date_time ($star_count stars)</h3>" .
    "<p>$text</p></div><br>";
  }

  echo "<form method='post' action='update_comment.php?event=$eid&date_time=$date_time'>$error" .
  "<span class='fieldname'>Rating</span>" .
  "<select name='rating'><option value='1'>1 Stars</option><option value='2'>2 Stars</option><option value='3'>3 Stars</option>" .
  "<option value='4'>4 Stars</option><option value='5'>5 Stars</option></select>" .
  "<h4>Comment:</h4><textarea name='text' cols='50' rows='3'>$text</textarea><br>" .
  "<span class='fieldname'>&nbsp;</span><input class='btn-primary' type='submit'" .
  "value='Submit Comment'></form></div>";

  if (isset($_POST['rating']) && !empty($_POST['rating']) &&
      isset($_POST['text']) && !empty($_POST['text']))
  {
    $rating = sanitizeString($_POST['rating']);
    $text = sanitizeString($_POST['text']);

  if ($rating == "" || $text == "")
  {
    echo "<h3>You must fill out the rating and comment fields to submit your comment.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }

  $date = date('jS \of F Y h:i:s A');

  queryMysql("DELETE FROM Comments WHERE eid='$eid' AND user='$user' AND date_time='$date_time'");
  queryMysql("INSERT INTO Comments VALUES('$user', '$eid', '$rating', '$text', '$date')");

  header('Location: event_profile.php'."?event=".$eid.'#comment');

}

  echo "<br><br>";
  require_once 'footer.php';

?>
