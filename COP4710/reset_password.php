<?php

  $login = "class='active'";

  require_once 'header.php';

  if ($loggedin)
  {
    echo "<h3>You must not be logged in to view this page.</h3>";
    echo "</div><br><br>";
    require_once 'footer.php';
    die();
  }

  echo "<div class='main'><h3>Please enter your details to log in</h3>";
  $error = $user = $pass = $type = "";

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = md5(sanitizeString($_POST['pass']));
    $type = sanitizeString($_POST['type']);
    $email = sanitizeString($_POST['email']);

    if ($user == "" || $pass == "" || $type == "" || $email == "")
        $error = "Not all fields were entered<br><br>";
    else
    {
      $result = queryMySQL("SELECT user,email FROM $type
        WHERE user='$user' AND email='$email'");

      if ($result->num_rows == 0)
      {
        $error = "<span class='error'>Username/Email
                  invalid</span><br><br>";
      }
      else
      {
        $result = queryMySQL("SELECT user,email FROM $type
          WHERE user='$user' AND email='$email' AND active='1'");

        if ($result->num_rows == 0)
        {
          $error = "<span class='error'>Account has not been verified. Please check your
                    email to verify.</span><br><br>";
        }
        else
        {
          queryMysql("UPDATE $type SET pass='$pass' WHERE user='$user'");
          $error = "<span class='error'>Your Password has been changed.</span><br><br>";
        }
      }
    }
  }

    echo "<div class='row'><div class='col-md-12 col-height'>" .
         "<form method='post' action='reset_password.php'>$error" .
         "<span class='fieldname'>Username</span><input type='text'" .
         "maxlength='16' name='user' placeholder='username'><br>" .
         "<span class='fieldname'>Email</span><input type='text'" .
         "maxlength='50' name='email' placeholder='example@example.com'><br>" .
         "<span class='fieldname'>New Password</span><input type='password'" .
         "maxlength='16' name='pass' placeholder='password'><br><br>" .
         "<form action='login.php'><span class='fieldname'>Account type</span><input type='radio'" .
         "name='type' value='Student' checked='checked'> Student  ".
         "<input type='radio' name='type' value='Admin'> Admin " .
         "<input type='radio' name='type' value='Super_admin'> Superadmin<br>" .
         "<span class='fieldname'>&nbsp;</span>" .
         "<input class='btn-primary' type='submit' value='Login'></form></div>";

    echo "</div><br><br>";
    require_once 'footer.php';
?>

  <br>
  </body>
</html>
