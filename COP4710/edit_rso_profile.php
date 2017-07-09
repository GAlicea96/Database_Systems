<?php

  $view_rso = "active";

  require_once 'header.php';

  if (!$loggedin) die();

  if (isset($_GET['rso']) && $_GET['rso'] != "")
    $rso = sanitizeString($_GET['rso']);

  $result = queryMysql("SELECT * FROM Rso Where user='$user' AND name='$rso'");

  if (!$result->num_rows)
  {
    echo "<h1>You do not have access to this page.</h1>";
    echo "<a href='index.php'>Click here</a> to go back to the home page.";
    die();
  }

  $row = $result->fetch_array();
  $name = $row['name'];
  $description = $row['description'];
  $type = $row['type'];

  $success = false;

  echo "<div class='main'><h3>RSO Profile for $name</h3>";

  if (isset($_POST['description']) && $_POST['description'] != "")
  {
    $description = sanitizeString($_POST['description']);
    $description = preg_replace('/\s\s+/', ' ', $description);

    if ($result->num_rows)
         queryMysql("UPDATE Rso SET description='$description' where name='$name'");
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

  if (isset($_POST['type']) && !empty($_POST['type']))
  {
    $type = sanitizeString($_POST['type']);
    $type = preg_replace('/\s\s+/', ' ', $type);

    if ($result->num_rows)
      queryMysql("UPDATE Rso SET type='$type' WHERE name='$name'");
    $success = true;
  }
  else
  {
    if ($result->num_rows)
    {
      $row  = $result->fetch_array(MYSQLI_ASSOC);
      $num_students = stripslashes($row['name']);
    }
    else $num_students = "";
  }

   if (isset($_FILES['file']['name']))
   {
     $saveto = __DIR__ . "/img/rsos/$rso.jpg";
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

   if ($success) echo "RSO profile successfully updated";

  echo "<form method='post' action='edit_rso_profile.php?rso=$rso' enctype='multipart/form-data'>$error" .
       "<h4>Upload RSO Picture:</h4><input type='file' name='file' class='btn-primary'>" .
       "<br><h4>Description:</h4><textarea name='description' cols='50' rows='3'>$description</textarea><br>" .
       "<br><span class='fieldname'>RSO Type</span><input type='text' " .
       "maxlength='25' name='type' value='$type' placeholder='RSO type'>" .
       "<span id='inforso'></span><br><br><input type='submit' value='Save Changes' class='btn-primary'></form>";
?>

  <?php echo "</div><br><br>"; require_once 'footer.php';?><br>
</body>
</html>
