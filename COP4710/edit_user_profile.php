<?php

  $profile = "active";

  require_once 'header.php';

  if (!$loggedin) die();

  $success = false;

  echo "<div class='main'><h3>$user's Profile</h3>";

  $result = queryMysql("SELECT * FROM Profile WHERE user='$user'");

  if (isset($_POST['about']) && $_POST['about'] != "")
  {
    $about = sanitizeString($_POST['about']);
    $about = preg_replace('/\s\s+/', ' ', $about);

    if ($result->num_rows)
         queryMysql("UPDATE Profile SET about='$about' where user='$user'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $about = stripslashes($row['about']);
    }
    else $about = "";
  }

  if (isset($_POST['uni']) && $_POST['uni'] != "")
  {
    $uni = sanitizeString($_POST['uni']);
    $uni = preg_replace('/\s\s+/', ' ', $uni);

    if ($result->num_rows)
      queryMysql("UPDATE Profile SET uni='$uni' where user='$user'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $uni = stripslashes($row['uni']);
    }
    else $uni = "";
  }

  if (isset($_POST['age']) && $_POST['age'] != "")
  {
    $age = sanitizeString($_POST['age']);
    $age = preg_replace('/\s\s+/', ' ', $age);

    if ($result->num_rows && is_numeric($age))
      queryMysql("UPDATE Profile SET age='$age' where user='$user'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $age = stripslashes($row['age']);
    }
    else $age = "";
  }

  if (isset($_POST['sex']) && $_POST['sex'] != "")
  {
    $sex = $_POST['sex'];

    if ($result->num_rows)
      queryMysql("UPDATE Profile SET sex='$sex' where user='$user'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $sex = stripslashes($row['sex']);
    }
    else $sex = "";
  }

  if (isset($_POST['grade']) && $_POST['grade'] != "")
  {
    $grade = sanitizeString($_POST['grade']);
    $grade = preg_replace('/\s\s+/', ' ', $grade);

    if ($result->num_rows)
      queryMysql("UPDATE Profile SET grade='$grade' where user='$user'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $grade = stripslashes($row['grade']);
    }
    else $grade = "";
  }

  if (isset($_POST['grad']) && $_POST['grad'] != "")
  {
    $grad = sanitizeString($_POST['grad']);
    $grad = preg_replace('/\s\s+/', ' ', $grad);

    if ($result->num_rows)
      queryMysql("UPDATE Profile SET grad='$grad' where user='$user'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $grad = stripslashes($row['grad']);
    }
    else $grad = "";
  }

  if (isset($_POST['major']) && $_POST['major'] != "")
  {
    $major = sanitizeString($_POST['major']);
    $major = preg_replace('/\s\s+/', ' ', $major);

    if ($result->num_rows)
      queryMysql("UPDATE Profile SET major='$major' where user='$user'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $major = stripslashes($row['major']);
    }
    else $major = "";
  }

  if (isset($_POST['minor']) && $_POST['minor'] != "")
  {
    $minor = sanitizeString($_POST['minor']);
    $minor = preg_replace('/\s\s+/', ' ', $minor);

    if ($result->num_rows)
      queryMysql("UPDATE Profile SET minor='$minor' where user='$user'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $minor = stripslashes($row['minor']);
    }
    else $minor = "";
  }

  if (isset($_FILES['file']['name']))
  {
    $saveto = __DIR__ . "/img/users/$user.jpg";
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

      $max = 500;
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

  if ($success) echo "Profile successfully updated";

  echo "<form method='post' action='edit_user_profile.php' enctype='multipart/form-data'>" .
       "<h3>Enter or edit your details and/or upload a profile picture</h3>" .
       "<h4>Upload Profile Picture:</h4><input type='file' name='file' class='btn-primary'>" .
       "<br><h4>About me:</h4><textarea name='about' cols='50' rows='3'>$about</textarea><br>" .
       "<br><h4>University:</h4><input type='text' maxlength='50' name='uni' value='$uni'><br>" .
       "<br><h4>Age:</h4><input type='text' maxlength='3' name='age' value='$age'><br>" .
       "<br><h4>Sex: <h4><input type='radio' name='sex' value='Male' > Male  " .
       "<input type='radio' name='sex' value='Female'> Female<br>" .
       "<br><h4>Grade:</h4><input type='text' maxlength='10' name='grade' value='$grade'><br>" .
       "<br><h4>Grad Year:</h4><input type='text' maxlength='10' name='grad' value='$grad'><br>" .
       "<br><h4>Major:</h4><input type='text' maxlength='100' name='major' value='$major'><br>" .
       "<br><h4>Minor (if any):</h4><input type='text' maxlength='100' name='minor' value='$minor'><br>";

?>
    <br><input type='submit' value='Save Profile' class='btn-primary'>
  </form><?php echo "</div><br><br>"; require_once 'footer.php';?><br>
  </body>
</html>
