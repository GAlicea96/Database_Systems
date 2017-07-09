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
    echo "<h3>Only students may search for RSOs.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }

  echo "<form method='post' action='search_rso.php'>$error" .
  "<span class='fieldname'>University</span>" .
  "<input type='text' maxlength='50' name='university' value='$university' placeholder='Do not leave blank'>$university<br>" .
  "<span class='fieldname'>Category</span>" .
  "<input type='text' maxlength='50' name='category' value='$category' placeholder='Leave blank for full list'>$category<br>" .
  "<span class='fieldname'>&nbsp;</span><input class='btn-primary' type='submit'" .
  "value='Search'></form></div>";

  if (isset($_POST['category']) && !empty($_POST['category']))
    $category = sanitizeString($_POST['category']);
  if (isset($_POST['university']) && !empty($_POST['university']))
    $university = sanitizeString($_POST['university']);

  if ($university == "")
  {
    echo "<h3>You must at least fill out the university field.</h3>";
    echo "<br><br>";
    require_once 'footer.php';
    die();
  }

  $uni = strtolower($university);

  if ($category != "")
    $rso = queryMysql("SELECT * FROM Rso JOIN Admin on Rso.user = Admin.user WHERE type='$category' AND Admin.uni = '$uni'");
  else
    $rso = queryMysql("SELECT * FROM Rso JOIN Admin on Rso.user = Admin.user WHERE Admin.uni = '$uni'");

  if ($category != "")
    echo "<h3>RSOs in University ($uni) with category: $category";
  else
    echo "<h3>RSOs in University ($uni)</h3>";

  while ($rsoi = $rso->fetch_array())
  {
    $name = $rsoi['name'];
    $admin = $rsoi['user'];
    $num_students = $rsoi['num_students'];
    $category = $rsoi['type'];

    echo "<div class='row'><div id='upevent' class='col-xs-12'>";
    echo "<h2><font color='blue'>RSO:</font> $name</h4>";
    echo "<h3><font color='blue'>Category:</font> $category</h2>";
    echo "<h3><font color='blue'>Description:</font> $description</h3>";
    echo "<h3><font color='blue'>Number of members:</font> $num_students</h2>";
    echo "<h3><font color='blue'>Owner:</font> $admin</h3>";
    echo "<a href='rso_profile.php?rso=$name'><font color='blue'>Link to RSO Page</font></a>";
    echo "</div></div><br>";
  }
  echo "<br><br>";
  require_once 'footer.php';

?>
