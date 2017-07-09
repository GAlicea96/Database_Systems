<?php

  $home = "class='active'";

  require_once 'header.php';

  if (isset($_GET['l']))
  {
    $l = sanitizeString($_GET['l']);

    if ($l == 'true')
      echo "<br><h2><font color='red'>You have been successfully logged out. We hope you return soon!</font></h2>";
  }
  if ($loggedin)
    echo "<a href='petition_rso.php' class='btn-primary'>Create your own RSO</a>" .
         "<br><a href='create_event.php' class='btn-primary'>Create an event</a>";

  $result = queryMysql("SELECT * FROM Super_admin WHERE user='$user'");

  if ($result->num_rows)
  {
    $result = $result->fetch_array();
    $uni = $result['uni'];
    $event = queryMysql("SELECT * FROM Event WHERE associated_uni='$uni' AND approved_by_super='0' AND rso_event = '0'");
    echo "<h3>Events needing your approval:</h3>";
    while ($eventi = $event->fetch_array())
    {
      $name = $eventi['name'];
      $id = $eventi['eid'];
      echo "<a href='approve_event.php?eid=$id' class='btn-primary'>$name</a><br>";
    }
  }


  echo "<br><div id='intro' class='container-fluid jumbotron'><p id='intro_text'>Welcome to $appname, ";

  if ($loggedin)
  {
    echo " $user - you are logged in. Thank you for returning - we hope you enjoy your stay!</p>";
  }
  else
  {
    echo 'please sign up and/or log in to join in and find ' .
         'an event near you!</p>';
  }

  echo "<a href='#events'><button type='button' class='btn-outline-primary'>See Upcoming Events</button></a>";
  echo "</div>";

  //echo "<div class='container col-md-12'><img src='img/background/intro.jpg'></img></div>"
?>
    <h2 id='event_head'>Next 5 Upcoming Official UCF Events</h4>
    <div id='events' class='container-fluid'>

<?php

  $upcoming_events = simplexml_load_file("http://events.ucf.edu/upcoming/feed.xml");
  $i = 0;

  foreach($upcoming_events->event as $event):
     echo "<div class='row'><div id='upevent' class='col-xs-12'>";
     echo "<h2><font color='blue'>Event Title:</font> $event->title</h4>";
     echo "<h3><font color='blue'>Date/Time:</font> $event->start_date</h2>";
     echo "<h3><font color='blue'>Location:</font> $event->location</h2>";
     echo "<h3><font color='blue'>Description:</font> $event->description</h3>";
     echo "<h3><font color='blue'>Contact Person:</font> $event->contact_person</h3>";
     //echo "LINK TO EVENT ON OUR SITE";
     echo "</div></div>";
     if (++$i == 5) break;
  endforeach;
  echo "</div><br><br>";
  require_once 'footer.php';
?>
    </div>
    </span><br><br>
  </body>
</html>
