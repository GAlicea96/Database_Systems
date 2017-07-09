<?php

  require_once 'header.php';

  if (!$loggedin)
  {
    echo "<h3>You are not currently logged in.</h3>";
    die();
  }

  if (isset($_GET['event']) && !empty($_GET['event']))
    $eid = $_GET['event'];
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

  echo "<form method='post' action='create_comment.php?event=$eid'>$error" .
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

  queryMysql("INSERT INTO Comments VALUES('$user', '$eid', '$rating', '$text', '$date')");

  header('Location: event_profile.php'."?event=".$eid.'#comment');

}

  echo "<br><br>";
  require_once 'footer.php';

?>
