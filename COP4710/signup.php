<?php

  $signup = "class='active'";

  require_once 'header.php';

  echo "<script src='javascript/signup.js'></script>";
?>
  <div class='main'><h3>Please enter your details to sign up</h3>

<?php

  $error = $user = $pass = $email = $uni = $type = "";
  if (isset($_SESSION['user'])) destroySession();

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    $email = strtolower(sanitizeString($_POST['email']));
    $uni = strtolower(sanitizeString($_POST['uni']));
    $type = sanitizeString($_POST['type']);

    if ($user == "" || $pass == "" || $email == "" || $uni == "")
      $error = "Not all fields were entered<br><br>";
    else
    {
      if (($result = queryMysql("SELECT * FROM Student WHERE user='$user'")) && $result->num_rows);
      elseif (($result = queryMysql("SELECT * FROM Super_admin WHERE user='$user'")) && $result->num_rows);
      else (($result = queryMysql("SELECT * FROM Admin WHERE user='$user'")) && $result->num_rows);

      $result2 = queryMysql("SELECT * FROM University WHERE name='$uni'");

      if ($result->num_rows)
        $msg = "That username is taken<br><br>";
      elseif (!$result2->num_rows && $type != "Super_admin")
      {
        $msg = "That University has not been created yet. Check back once a Superadmin has created it.";
      }
      else
      {
        if (($result = queryMysql("SELECT * FROM Student WHERE email='$email'")) && $result->num_rows);
        elseif (($result = queryMysql("SELECT * FROM Super_admin WHERE email='$email'")) && $result->num_rows);
        else (($result = queryMysql("SELECT * FROM Admin WHERE email='$email'")) && $result->num_rows);

          if ($result->num_rows)
            $msg = "That email is taken<br><br>";
          else
          {
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                  $msg = "Email is not valid<br><br>";
                }
                else
                {
                    $hash = 'hash';//md5( rand(0,1000) );
                    $pass = md5($pass);

                    $uni = strtolower($uni);
                    queryMysql("INSERT INTO $type VALUES('$user', '$pass', '$email', '$uni', '$hash', '0')");
                    queryMysql("INSERT INTO Profile VALUES('$user', NULL, '$uni', NULL, NULL, NULL, NULL, NULL, NULL)");

                    if ($type == 'Super_admin')
                    {
                        $result = queryMysql("SELECT * FROM University WHERE name='$uni'");

                        if (!$result->num_rows)
                        {
                          queryMysql("INSERT INTO University VALUES(NULL, '$uni', NULL, NULL, NULL)");
                          $new = queryMysql("SELECT university_id FROM University WHERE name='$uni'");
                          $new = $new->fetch_array();
                          $id = $new['university_id'];
                          queryMysql("INSERT INTO Creates_uni VALUES('$id', '$user')");
                        }
                        else
                          $msg = "This University already has a Superadmin.";
                    }

                    $to      = $email;
                    $subject = 'Signup Verification for UniversityEvents.com';
                    $message = "

                    Thanks for signing up!
                    Your account has been created, you can login with the following username and the password provided during account creation.

                    ------------------------
                    Username: '$name'
                    Account Type: '$type'
                    ------------------------

                    Please click this link to activate your account:
                    http://www.UniversityEvents.com/verify.php?email=$email&hash=$hash&type=$type

                    ";

                    $headers = 'From: noreply@UniversityEvents.com' . "\r\n";
                    mail($to, $subject, $message, $headers);

                    $msg = "Account created. <br> Please verify it by clicking the activation link sent to your email.";
                }
          }
      }
      echo "<div class='statusmsg'><h2 align='center'>$msg</h2></div><br><br>";
    }
  }

  echo "<div class='row'><div class='col-md-6 left-col'>" .
       "<form method='post' action='signup.php'>$error" .
       "<span class='fieldname'>Username</span>" .
       "<input type='text' maxlength='16' name='user' value='$user'" .
       "onBlur='checkUser(this)' placeholder='username'><span id='infouser'></span><br>" .
       "<span class='fieldname'>Password</span>" .
       "<input type='password' maxlength='16' name='pass'" .
       "value='' placeholder='password'><br>" .
       "<span class='fieldname'>Email</span><input type='text'" .
       "maxlength='35' name='email' value='$email' placeholder='example@example.com'onBlur='checkEmail(this)'>" .
       "<span id='infoemail'></span><br>" .
       "<span class='fieldname'>University</span><input type='text'" .
       "maxlength='50' name='uni' value='$uni' placeholder='University'><br>" .
       "<span class='fieldname'>Account type</span><input type='radio'" .
       "name='type' value='Student' checked='checked'> Student  ".
       "<input type='radio' name='type' value='Super_admin'> Superadmin<br>" .
       "<span class='fieldname'>&nbsp;</span><input class='btn-primary' type='submit'" .
       "value='Sign up'></form></div>" .
       "<div class='col-md-6 right-col'><img src='img/background/signup.jpg'" .
       " height='185.5' width='600'></div></div>";

       echo "</div><br><br>";
       require_once 'footer.php';
?>
    <br>
  </body>
</html>
