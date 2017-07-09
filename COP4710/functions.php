<?php

  $dbhost  = 'localhost';
  $dbname  = 'Unievents';
  $dbuser  = 'COP4710';
  $dbpass  = '';
  $appname = "UniversityEvents.com";

  $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  if ($connection->connect_error) die($connection->connect_error);

  function createTable($name, $query)
  {
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
  }

  function queryMysql($query)
  {
    global $connection;
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    return $result;
  }

  function destroySession()
  {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
  }

  function sanitizeString($var)
  {
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
  }

  function getDomain($email)
  {

    $domain = substr(strstr($email, '@'), 1);
    return $domain;
  }

  function showUserProfile($user)
  {
    echo "<div class='main'><h2>$user's Profile</h2>";

    $img = "img/users/$user.jpg";
    if (file_exists("$img"))
      echo "<div class='container-fluid'><img src='$img' style='float:left;'></div><br>";

    echo "<a href='user_profile.php?user=$user#rso' class='btn-primary'>RSOs</a>" .
         "<br><a href='user_profile.php?user=$user#event' class='btn-primary'>Events</a><br>";
         //your friends
         //view messages

    $result = queryMysql("SELECT * FROM Profile WHERE user='$user'");

    if ($result->num_rows)
    {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $uni = $row['uni'];
      $age = $row['age'];
      $sex = $row['sex'];
      $grade = $row['grade'];
      $grad = $row['grad'];
      $major = $row['major'];
      $minor = $row['minor'];
      echo "<h4><font color='blue'>About me:</font><h4><div id='profile' class='container-fluid'>";
      echo stripslashes($row['about']) . "<br style='clear:left;'></div><br>" .
           "<br><h4><font color='blue'>University:</font></h4><div id='profile' class='container-fluid'>$uni</div><br>" .
           "<br><h4><font color='blue'>Age:</font></h4><div id='profile' class='container-fluid'>$age</div><br>" .
           "<h4><font color='blue'>Sex:</font></h4><div id='profile' class='container-fluid'>$sex</div><br>" .
           "<h4><font color='blue'>Grade:</font></h4><div id='profile' class='container-fluid'>$grade</div><br>" .
           "<h4><font color='blue'>Grad Year:</font></h4><div id='profile' class='container-fluid'>$grad</div><br>" .
           "<h4><font color='blue'>Major:</font></h4><div id='profile' class='container-fluid'>$major</div><br>" .
           "<h4><font color='blue'>Minor (if any):</font></h4><div id='profile' class='container-fluid'>$minor</div><br>";

      if (($result = queryMysql("SELECT * FROM Student WHERE user='$user'")) && $result->num_rows)
      {           //LINKS
        echo "<div id='rso'>";
        echo "<h2>Followed RSOs</h2>";

        $rso = queryMysql("SELECT * FROM Follows_Rso WHERE user='$user'");

        while ($rsoi = $rso->fetch_array())
        {
          $name = $rsoi['name'];
          $num_students = $rsoi['num_students'];

          echo "<h3><a href='rso_profile.php?rso=$name'>$name</a></h3>";
        }

        echo "</div><div id='event'><h2>Followed Events</h2>";

        $event = queryMysql("SELECT * FROM Follows_event WHERE user='$user'");

        while ($eventi = $event->fetch_array())
        {
          $eid = $eventi['eid'];
          $eventi = queryMysql("SELECT * FROM Event WHERE eid='$eid'");
          $eventi = $eventi->fetch_array();
          $name = $eventi['name'];
          $location = $eventi['location'];
          $date = $eventi['date'];

          echo "<h3><a href='event_profile.php?event=$eid'$>$name</a> at $location on $date</h3>";
        }
        echo "</div>";
      }
    }
  }

  function showUniProfile($uni)
  {
    $result = queryMysql("SELECT * FROM University WHERE university_id='$uni'");
    if ($result->num_rows)
    {
      $row = $result->fetch_array();

      $name = $row['name'];
      $name = strtoupper($name);
      $namel = strtolower($name);
      echo "<div class='main'><h2>$name's Profile</h2>";

      $img = "img/unis/$namel.jpg";
      if (file_exists("$img"))
        echo "<div class='container-fluid'><img src='$img' style='float:left;'></div><br>";

      echo "<a href='user_profile.php?uni=$uni#rso' class='btn-primary'>University RSOs</a>" .
           "<br><a href='user_profile.php?uni=$uni#event' class='btn-primary'>University Events</a>";

      if ($result->num_rows)
      {
        $description = $row['description'];
        $location = $row['location'];
        $num_students = $row['num_students'];

        echo "<br><h4><font color='blue'>Description:</font><h4><div id='profile' class='container-fluid'>";
        echo stripslashes($row['description']) . "<br style='clear:left;'></div><br>" .
             "<br><h4><font color='blue'>Location:</font></h4><div id='profile' class='container-fluid'>$location</div><br>" .
             "<br><h4><font color='blue'>Population:</font></h4><div id='profile' class='container-fluid'>$num_students</div><br>";
      }
//LINKS
      echo "<div id='rso'>";
      echo "<h2>University RSOs</h2>";

      $rso = queryMysql("SELECT * FROM Rso WHERE user=
                        (SELECT user FROM Admin WHERE uni='$name')");

      while ($rsoi = $rso->fetch_array())
      {
        $name = $rsoi['name'];
        $num_students = $rsoi['num_students'];
        if ($num_students < 5)
        {
          //link to petition page,not actual page
          echo "<h3>$name</h3>";
        }
        echo "<h3>$name</h3>";
      }

      echo "</div><div id='event'><h2>University Events</h2>";

      $event = queryMysql("SELECT * FROM Event WHERE associated_uni='$name'");

      while ($eventi = $event->fetch_array())
      {
        $name = $eventi['name'];
        $location = $eventi['location'];
        $date = $eventi['date'];
        echo "<h3>$name at $location on $date</h3>";
      }
      echo "</div>";
    }
  }

  function showRsoProfile($rso)
  {
    echo "<div class='main'><h2>$rso's Profile</h2>";

    echo "<a href='rso_profile.php?rso=$rso#member' class='btn-primary'>Members List</a>" .
         "<br><a href='rso_profile.php?rso=$rso#event' class='btn-primary'>RSO events</a>";

    $img = "img/rsos/$rso.jpg";
    if (file_exists("$img"))
      echo "<div class='container-fluid'><img src='$img' style='float:left;'></div>";

    $result = queryMysql("SELECT * FROM Rso WHERE name='$rso'");

    if ($result->num_rows)
    {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      $description = $row['description'];
      $type = $row['type'];
      $owner = $row['user'];
      $num_students = $row['num_students'];

      echo "<br><h4><font color='blue'>Description:</font><h4><div id='profile' class='container-fluid'>";
      echo stripslashes($row['description']) . "<br style='clear:left;'></div><br>";
      echo "<br><h4><font color='blue'>Owner:</font></h4><div id='profile' class='container-fluid'>$owner</div><br>" .
           "<br><h4><font color='blue'>Type:</font></h4><div id='profile' class='container-fluid'>$type</div><br>" .
           "<br><h4><font color='blue'>Population:</font></h4><div id='profile' class='container-fluid'>$num_students</div><br>";
//LINKS
     echo "<div id='member'>";
     echo "<h2>RSO Members</h2>";

     $members = queryMysql("SELECT * FROM Follows_Rso WHERE name='$rso'");

     while ($membersi = $members->fetch_array())
     {
       $name = $membersi['user'];
       echo "<h3><a href='user_profile.php?user=$name'>$name</a></h3>";
     }

     echo "</div><div id='event'><h2>RSO Events</h2>";

     $event = queryMysql("SELECT * FROM Event WHERE rso_event AND user=
                         (SELECT user FROM Rso WHERE name='$rso')");

     while ($eventi = $event->fetch_array())
     {
       $name = $eventi['name'];
       $name = $eventi['name'];
       if ($previous != $name)
       {
         $previous = $name;
         $location = $eventi['location'];
         $date = $eventi['date'];
         $id = $eventi['eid'];
         echo "<h3><a href='event_profile.php?eid=$id'>$name at $location on $date</a></h3>";
       }
     }
     echo "<br></div>";
    }
  }
    function showEventProfile($event, $user)
    {

      $result = queryMysql("SELECT * FROM Event WHERE (eid='$event' AND rso_event='1')
                            OR (eid='$event' AND approved_by_super='1')
                            OR (eid='$event' AND scope='Public')");

      if ($result->num_rows)
        $row = $result->fetch_array();
      else
      {
        echo "<h3>This event is not an RSO event, and has not yet been approved by a Superadmin. Check back later.</h3>";
        echo "</div><br><br>";
        require_once 'footer.php';
        die();
      }

      $stars = findRating($event);
      $name = $row['name'];

      echo "<div class='main'><h2>$name (Rating = $stars stars)</h2>";

    echo "<a href='event_profile.php?event=$event#attendee' class='btn-primary'>View Attendees</a>" .
         "<br><a href='event_profile.php?event=$event#comment' class='btn-primary'>View Comments</a>";

    if ($result->num_rows)
    {
      $name = $row['name'];
      $description = $row['description'];
      $category = $row['category'];
      $location = $row['location'];
      $longitude = $row['longitude'];
      $latitude = $row['latitude'];
      $date = $row['date'];
      $start_time = $row['start_time'];
      $end_time = $row['end_time'];

      $start_pm = $row['start_pm'];
      if ($start_pm) $start_pm = 'PM';
      else $start_pm = 'AM';
      $end_pm = $row['end_pm'];
      if ($end_pm) $end_pm = 'PM';
      else $end_pm = 'AM';

      $contact_name = $row['contact_name'];
      $contact_phone = $row['contact_phone'];
      $contact_email = $row['contact_email'];
      $associated_uni = $row['associated_uni'];
      $max_occupancy = $row['max_occupancy'];
      $availability = $row['availability'];
      $scope = $row['scope'];
      $lat = $row['latitude'];
      $lng = $row['longitude'];

      echo "<br><h4><font color='blue'>Category:</font></h4><div id='profile' class='container-fluid'>$category</div><br>" .
           "<br><h4><font color='blue'>Scope of event:</font></h4><div id='profile' class='container-fluid'>$scope (Max Occupancy: $max_occupancy, Availability: $availability)</div><br>";
      echo "<br><h4><font color='blue'>Description:</font><h4><div id='profile' class='container-fluid'>";
      echo stripslashes($description) . "<br style='clear:left;'></div><br>" .
           "<br><h4><font color='blue'>Location:</font></h4><div id='profile' class='container-fluid'>$location at $associated_uni</div><br>" .
           //insert google maps here
           "<br><h4><font color='blue'>Date/Time:</font></h4><div id='profile' class='container-fluid'>On $date, From $start_time $start_pm to $end_time $end_pm</div><br>" .
           "<br><h4><font color='blue'>Contact:</font></h4><div id='profile' class='container-fluid'>$contact_name, reachable at #: $contact_phone or email: $contact_email</div><br>";

     echo "<a href='event_location.php?lat=$lat&lng=$lng' class='btn-primary'>View Location on Map</a>";

     echo "</div><div id='comment'><h2>Event Comments</h2>";

     $comments = queryMysql("SELECT * FROM Comments WHERE eid='$event'");

     echo "<a href='create_comment.php?event=$event'>Create New Comment</a><br>";

     while ($commentsi = $comments->fetch_array())
     {
       $name = $commentsi['user'];
       $text = $commentsi['text'];
       $date_time = $commentsi['date_time'];
       $author = $commentsi['user'];
       $star_count = $commentsi['star_count'];
       echo "<div id='upevent'>";
       echo "<h3>$name on $date_time ($star_count stars)</h3>" .
       "<p>$text</p>";

       if ($user == $author)
       {
         echo "<a href='delete_comment.php?event=$event&date_time=$date_time'>Delete Comment</a><br>" .
              "<a href='update_comment.php?event=$event&date_time=$date_time'>Update Comment</a>";
       }
       echo "</div><br>";
     }

     echo "<div id='attendee'>";
     echo "<h2>Event attendees</h2>";

     $attendees = queryMysql("SELECT * FROM Follows_event WHERE eid='$event'");
//LINKS
     while ($attendeesi = $attendees->fetch_array())
     {
       $name = $attendeesi['user'];
       echo "<h3>$name</h3>";
     }

     echo "</div>";

    }
  }

  function findRating($event)
  {
    $rating = queryMysql("SELECT * FROM Comments WHERE eid='$event'");
    if ($rating->num_rows)
    {
      $count = $total = 0;
      while ($ratingi = $rating->fetch_array())
      {
        $total += $ratingi['star_count'];
        $count++;
      }
      $stars = $total / $count;
    }
    else
      $stars = 0;
    return $stars;
  }
?>
