<?php

  $univ = "active";

  require_once 'header.php';

  if (!$loggedin) die();

  $result = queryMysql("SELECT * FROM Super_admin Where user='$user'");

  if (!$result->num_rows)
  {
    echo "<h1>You do not have access to this page.</h1>";
    echo "<a href='index.php'>Click here</a> to go back to the home page.";
    die();
  }

  $uni = queryMysql("SELECT uni FROM Super_admin WHERE user='$user'");
  $uni = $uni->fetch_array();
  $uni = $uni['uni'];
  $uni = strtoupper($uni);
  $match = queryMysql("SELECT university_id FROM University WHERE name='$uni'");
  $match = $match->fetch_array();
  $id = $match['university_id'];
  $result = queryMysql("SELECT user FROM Creates_uni WHERE university_id='$id'");
  $result = $result->fetch_array();
  $result = $result['user'];

  if ($result != $user)
  {
    echo "<h1>You do not have access to this page.</h1>";
    echo "<a href='index.php'>Click here</a> to go back to the home page.";
    die();
  }

  $success = false;

  echo "<div class='main'><h3>$uni's Profile</h3>";

  $result = queryMysql("SELECT * FROM University WHERE university_id='$id'");

  if (isset($_POST['description']) && $_POST['description'] != "")
  {
    $description = sanitizeString($_POST['description']);
    $description = preg_replace('/\s\s+/', ' ', $description);

    if ($result->num_rows)
         queryMysql("UPDATE University SET description='$description' where university_id='$id'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $description = stripslashes($row['description']);
    }
    else $description = "";
  }

  if (isset($_POST['location']) && $_POST['location'] != "")
  {
    $location = sanitizeString($_POST['location']);
    $location = preg_replace('/\s\s+/', ' ', $location);

    if ($result->num_rows)
      queryMysql("UPDATE University SET location='$location' WHERE university_id='$id'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $location = stripslashes($row['location']);
    }
    else $location = "";
  }

  if (isset($_POST['num_students']) && $_POST['num_students'] != "")
  {
    $num_students = sanitizeString($_POST['num_students']);
    $num_students = preg_replace('/\s\s+/', ' ', $num_students);

    if ($result->num_rows && is_numeric($num_students))
      queryMysql("UPDATE University SET num_students='$num_students' WHERE university_id='$id'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $num_students = stripslashes($row['num_students']);
    }
    else $num_students = "";
  }

  if (isset($_FILES['file']['name']))
  {
    $unil = strtolower($uni);
    $saveto = __DIR__ . "/img/unis/$unil.jpg";
    $tmp = $_FILES['file']['tmp_name'];
    move_uploaded_file($tmp, $saveto);
    $typeok = TRUE;


    switch($_FILES['file']['type'])
    {
      case "image/gif":   $src = imagecreatefromgif($saveto); break;
      case "image/jpeg":  // Both regular and progressive jpegs
      case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
      case "image/png":   $src = imagecreatefrompng($saveto); break;
      default:            $typeok = FALSE; break;
    }

    if ($typeok)
    {
      list($w, $h) = getimagesize($saveto);

      $max = 250;
      $tw  = $w;
      $th  = $h;

      if ($w > $h && $max < $w)
      {
        $th = $max / $w * $h;
        $tw = $max;
      }
      elseif ($h > $w && $max < $h)
      {
        $tw = $max / $h * $w;
        $th = $max;
      }
      elseif ($max < $w)
      {
        $tw = $th = $max;
      }

      $tmp = imagecreatetruecolor($tw, $th);
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
      imageconvolution($tmp, array(array(-1, -1, -1),
        array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
      imagejpeg($tmp, $saveto);
      imagedestroy($tmp);
      imagedestroy($src);
    }
  }

  if ($success) echo "University profile successfully updated";

  echo "<form method='post' action='edit_uni_profile.php' enctype='multipart/form-data'>" .
       "<h3>Enter or edit your university's details and/or upload a university picture</h3>" .
       "<h4>Upload University Picture:</h4><input type='file' name='file' class='btn-primary'>" .
       "<br><h4>Description:</h4><textarea name='description' cols='50' rows='3'>$description</textarea><br>" .
       "<br><h4>Location:</h4><input type='text' maxlength='50' name='location' value='$location'><br>" .
       "<br><h4>Population:</h4><input type='text' maxlength='10' name='num_students' value='$num_students'><br>";

?>
    <br><input type='submit' value='Save University Profile' class='btn-primary'>
  </form><?php echo "</div><br><br>"; require_once 'footer.php';?><br>
  </body>
</html>
