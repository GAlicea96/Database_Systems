<?php

  $event = "class='active'";

  require_once 'header.php';

  if (!$loggedin)
  {
    echo "<h2>You must be logged in to view this page (and petition to create RSOs).</h2>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }

  $result = queryMysql("SELECT * FROM Admin WHERE user='$user'");
  if (!$result->num_rows)
  {
    echo "<h2>You must be an Admin of an RSO to create events.</h2>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }

  if (isset($_GET['lat']) && !empty($_GET['lat']) && isset($_GET['lng']) && !empty($_GET['lng'])
  && is_numeric($_GET['lat']) && is_numeric($_GET['lng']))
  {
    $latitude = round(sanitizeString($_GET['lat']), 4);
    $longitude = round(sanitizeString($_GET['lng']), 4);
  }
  else
  {
    echo "<h3>You must specify a google maps location before creating an event.</h3>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }

  echo "<script src='javascript/signup.js'></script>";
?>

  <div class='main'><h3>Please enter the details of the Event you wish to create</h3>

<?php

  $error = $name = $category = $description = $location = $date = $start_time = "";
  $end_time = $start_pm = $end_pm = $contact_name = $contact_phone = $contact_email = "";
  $associated_uni = $scope = $max_occupancy = "";

  if (isset($_POST['name']) && isset($_POST['category']) && isset($_POST['description']) &&
      isset($_POST['location']) && isset($_POST['date']) && isset($_POST['start_time']) &&
      isset($_POST['end_time']) && isset($_POST['start_pm']) && isset($_POST['end_pm']) &&
      isset($_POST['contact_name']) && isset($_POST['contact_phone']) && isset($_POST['contact_email']) &&
      isset($_POST['associated_uni']) && isset($_POST['scope']) && isset($_POST['max_occupancy']))
  {
    $name = sanitizeString($_POST['name']);
    $name = preg_replace('/\s\s+/', ' ', $name);
    $category = sanitizeString($_POST['category']);
    $category = preg_replace('/\s\s+/', ' ', $category);
    $description = sanitizeString($_POST['description']);
    $description = preg_replace('/\s\s+/', ' ', $description);
    $location = sanitizeString($_POST['location']);
    $location = preg_replace('/\s\s+/', ' ', $location);
    $date = sanitizeString(date('m/d/y', strtotime($_POST['date'])));
    $date = preg_replace('/\s\s+/', ' ', $date);
    $start_time = $_POST['start_time'];
    $start_time *= 100;
    $end_time = $_POST['end_time'];
    $end_time *= 100;
    $start_pm = sanitizeString($_POST['start_pm']);
    $start_pm = preg_replace('/\s\s+/', ' ', $start_pm);
    $end_pm = sanitizeString($_POST['end_pm']);
    $end_pm = preg_replace('/\s\s+/', ' ', $end_pm);
    $contact_name = sanitizeString($_POST['contact_name']);
    $contact_name = preg_replace('/\s\s+/', ' ', $contact_name);
    $contact_phone = sanitizeString($_POST['contact_phone']);
    $contact_phone = preg_replace('/\s\s+/', ' ', $contact_phone);
    $contact_email = sanitizeString($_POST['contact_email']);
    $contact_email = preg_replace('/\s\s+/', ' ', $contact_email);
    $contact_email = filter_var($contact_email, FILTER_VALIDATE_EMAIL);
    $associated_uni = sanitizeString($_POST['associated_uni']);
    $associated_uni = preg_replace('/\s\s+/', ' ', $associated_uni);
    $associated_uni = strtolower($associated_uni);
    $scope = sanitizeString($_POST['scope']);
    $scope = preg_replace('/\s\s+/', ' ', $scope);
    $max_occupancy = $_POST['max_occupancy'];

    if ($name == "" || $category == "" || $description == "" || $location == "" || $date == ""  ||
       $start_time == "" || !is_numeric($start_time) || $start_time > 2400 || $start_time < 0 || $end_time == "" || !is_numeric($end_time) || $start_pm == "" ||
       $end_pm == "" || $end_time > 2400 || $end_time < 0 || $contact_name == "" ||
       $contact_phone == "" || !is_numeric($contact_phone) || $contact_email == "" || $associated_uni == "" || $scope == "" || $max_occupancy == "" || !is_numeric($max_occupancy))
        {
          $msg = "Not all fields were entered, or some were entered incorrectly.<br><br>";
        }
    else
    {
      $result = queryMysql("SELECT * FROM Event WHERE name='$name'");
      if ($result->num_rows)
        $msg = "That name is already taken<br><br>";
      else
      {

        if ($start_pm == "PM") $start_pm = TRUE;
        else $start_pm = 0;
        if ($end_pm == "PM") $end_pm = TRUE;
        else $end_pm = 0;

        $chunk = $end_time - $start_time;
        $count = 0;

        for ($i = 0; $i < $chunk; $i+=100)
        {
            $time = $start_time + $i;
            $result = queryMysql("SELECT * FROM Event WHERE date='$date' AND location='$location' AND (start_time >= '$time' OR end_time <= '$time') AND start_pm='$start_pm'");
            $count += $result->num_rows;
        }

          if ($count)
            $msg = "There is already an event scheduled for that location, during those times.";
          else
          {

            if ($scope == 'RSO') $rso_event = TRUE;
            else $rso_event = 0;

            for ($i = 0; $i < $chunk; $i+=100)
            {
                $time = $start_time + $i;
                $end_time = $time + 100;
                queryMysql("INSERT INTO Event VALUES( NULL, '$user', '$description', '$name', '$category',
                          '$location', '$longitude', '$latitude', '$date','$time', '$end_time', '$start_pm',
                          '$end_pm', '$contact_name', '$contact_phone', '$contact_email', FALSE, '$associated_uni', '$scope', '$rso_event', '$max_occupancy', '$max_occupancy')");
            }

            $msg = "Event has been created. Note that if this is not a RSO event, then Superadmin permission (from associated University) will be needed<br>" .
            "before it will be considered an official event.";
          }
      }
    }

   echo "<div class='statusmsg'><h2 align='center'>$msg</h2></div><br><br>";
 }




echo "<div class='row'><div class='col-md-6 left-col'>" .
     "<form method='post' action='create_event.php?lat=$latitude&lng=$longitude'>$error" .
     "<br><h4>Description:</h4><textarea name='description' cols='50' rows='3'>$description</textarea>" .
     "<br><span class='fieldname'>RSO Name</span>" .
     "<input type='text' maxlength='30' name='name' value='$name' placeholder='name'><br>" .
     "<span class='fieldname'>Category</span><input type='text'" .
     "maxlength='30' name='category' value='$category' placeholder='category'><br>" .
     "<span class='fieldname'>Location</span>" .
     "<input type='text' maxlength='50' name='location' value='$location' placeholder='location'><br>" .
     "<span class='fieldname'>Date</span>".
     "<input type='date' name='date' value='$date' placeholder='mmddyy'><br>" .
     "<br><span class='fieldname'>Start time</span>" .
     "<input type='number' max='24' min='0' name='start_time' value='$start_time' placeholder='XX'><br>" .
     "<input type='radio'" .
     "name='start_pm' value='AM' checked='checked'> AM  ".
     "<input type='radio' name='start_pm' value='PM'> PM<br>" .
     "<br><span class='fieldname'>End time</span>" .
     "<input type='number' max='24' min='0' name='end_time' value='$end_time' placeholder='XX'><br>" .
     "<input type='radio'" .
     "name='end_pm' value='AM' checked='checked'> AM  ".
     "<input type='radio' name='end_pm' value='PM'> PM<br>" .
     "<span class='fieldname'>Contact Name</span>" .
     "<input type='text' maxlength='25' name='contact_name' value='$contact_name' placeholder='contact'><br><br>" .
     "<span class='fieldname'>Contact Phone</span>" .
     "<input type='text' maxlength='25' name='contact_phone' value='$contact_phone' placeholder='XXXXXXXXXX format'><br><br>" .
     "<span class='fieldname'>Contact Email</span>" .
     "<input type='text' maxlength='50' name='contact_email' value='$contact_email' placeholder='example@example.com'><br>" .
     "<span class='fieldname'>University</span>" .
     "<input type='text' maxlength='50' name='associated_uni' value='$associated_uni' placeholder='University name'><br>" .
     "<br>" .
     "<span class='fieldname'>Event Scope</span><input type='radio'" .
     "name='scope' value='Public' checked='checked'> Public  ".
     "<input type='radio' name='scope' value='Private'> Private<br>" .
     "<input type='radio' name='scope' value='RSO'> RSO<br>" .
     "<span class='fieldname'>Max Occupancy</span>" .
     "<input type='number' max='10000' min='0' step='10' name='max_occupancy' value='$max_occupancy' placeholder='XXX format'>" .
     "<span id='infoevent'></span><br><br><input type='submit' value='Create Event Petition' class='btn-primary'></form></div>";
?>

  <?php echo "</div><br><br>"; require_once 'footer.php';?><br>
</body>
</html>
