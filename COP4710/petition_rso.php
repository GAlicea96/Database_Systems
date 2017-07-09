<?php

  $search = "class='active'";

  require_once 'header.php';

  if (!$loggedin)
  {
    echo "<h2>You must be logged in to view this page (and petition to create RSOs).</h2>";
    die();
  }

  echo "<script src='javascript/signup.js'></script>";
?>

  <div class='main'><h3>Please enter the details of the RSO you wish to create</h3>

<?php

  $error = $name = $type = $description = $email = "";
  $num_students = 1;

  if (isset($_POST['name']) && isset($_POST['type']) && isset($_POST['description']))
  {
    $name = sanitizeString($_POST['name']);
    $name = preg_replace('/\s\s+/', ' ', $name);
    $type = sanitizeString($_POST['type']);
    $type = preg_replace('/\s\s+/', ' ', $type);
    $description = sanitizeString($_POST['description']);
    $description = preg_replace('/\s\s+/', ' ', $description);

    if ($name == "" || $type == "" || $description == "")
      $msg = "Not all fields were entered<br><br>";
    else
    {
      $result = queryMysql("SELECT * FROM Rso WHERE name='$name'");
      if ($result->num_rows)
        $msg = "That name is already taken<br><br>";
      else
      {
        if (($result = queryMysql("SELECT * FROM Admin WHERE user='$user'")) && $result->num_rows) $ad = "TRUE";
        elseif (($result = queryMysql("SELECT * FROM Student WHERE user='$user'"))) $ad = "FALSE";

          if ($ad == "TRUE")
            $msg = "You must be a Student to petition to create an RSO. Also, admins may only own one RSO.";
          elseif ($result->num_rows)
          {
            $result = $result->fetch_array(MYSQLI_ASSOC);
            $email = $result['email'];


            $domain = getDomain($email);


            queryMysql("INSERT INTO Admin(user, pass, email, uni, hash, active) SELECT * FROM Student WHERE user = '$user'");

            queryMysql("UPDATE Admin SET active = 0 WHERE user='$user'");
            queryMysql("INSERT INTO Rso VALUES('$name', '$user', '$description', '$type', '$num_students', '$domain')");
            queryMysql("INSERT INTO Follows_Rso VALUES('$user', '$name')");

            $msg = "Unofficial RSO created. <br> Once at least 5 students are following the RSO, it will become official." .
                   " Direct them to <a class='$appname' href='petition_page.php?name=$name&domain=$domain'>this page</a> (they must have the same email domain as you)" .
                   " Additionally, your account will be made an Admin account when the RSO becomes official.";
          }
          else $msg = "You must have a student account and be logged in to petition to create an RSO.";
      }
    }

   echo "<div class='statusmsg'><h2 align='center'>$msg</h2></div><br><br>";
 }

   if (isset($_FILES['file']['name']))
   {
     $saveto = __DIR__ . "/img/rsos/$name.jpg";
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


echo "<div class='row'><div class='col-md-6 left-col'>" .
     "<form method='post' action='petition_rso.php'>$error" .
     "<h4>Upload RSO Picture:</h4><input type='file' name='file' class='btn-primary'>" .
     "<br><h4>Description:</h4><textarea name='description' cols='50' rows='3'>$description</textarea><br>" .
     "<br><span class='fieldname'>RSO Name</span>" .
     "<input type='text' maxlength='25' name='name' value='$name' placeholder='RSO name' " .
     "onBlur='checkRso(this)'><span id='infoname'></span><br>" .
     "<br><span class='fieldname'>RSO Type</span><input type='text'" .
     "maxlength='25' name='type' value='$type' placeholder='RSO type'>" .
     "<span id='inforso'></span><br><br><input type='submit' value='Create RSO Petition' class='btn-primary'></form></div>" .
     "<div class='col-md-6 right-col'><img src='img/background/rso.jpg'" .
     "height='185.5' width='600'></div></div>";
?>

  <?php echo "</div><br><br>"; require_once 'footer.php';?><br>
</body>
</html>
