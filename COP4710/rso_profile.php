<?php

  $view_rso = "active";

  require_once 'header.php';

  echo "<script src='javascript/follow_rso.js'></script>";
  echo "<script src='javascript/unfollow_rso.js'></script>";

  if (!$loggedin)
  {
    echo "<h3>You must be logged in to view this page</h3>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }

  if(isset($_GET['rso']) && !empty($_GET['rso']))
    $rso = sanitizeString($_GET['rso']);

  $result = queryMysql("SELECT * FROM Rso WHERE name='$rso'");

  if ($result->num_rows)
  {
    $result = $result->fetch_array();
    $num = $result['num_students'];

    if ($num < 5)
    {
      echo "<h4 align='center'>This RSO is still unofficial, as it does not yet have 5 follows with the same email domain. " .
           "Check back again later.<br>";

      $name = $result['name'];
      $domain = $result['domain'];

      echo "<br><a href='petition_page.php?name=$name&domain=$domain'>Click here to petition RSO or use the url to share with others.</a>";
      echo "</div><br><br>";
      require_once 'footer.php';
      die();
    }

    showRsoProfile($rso);

    $result = queryMysql("SELECT * FROM Follows_Rso WHERE user='$user' AND name='$rso'");

    if ($result->num_rows)
      echo "<button onclick='unfollow_rso(\"$rso\", \"$user\")' class='btn-primary'>Leave RSO</button><span id='unfollowrso'></span><br><br>";
    else
      echo "<button onclick='follow_rso(\"$rso\", \"$user\")' class='btn-primary'>Join RSO</button><span id='followrso'></span><br><br>";
  }
  else
    echo "<h4 align='center'>RSO $rso does not exist</h4></div>";

  echo "<br><br>";
  require_once 'footer.php';

?>

  <br>
</body>
</html>
