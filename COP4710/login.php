<?php

  $login = "class='active'";

  require_once 'header.php';

  echo "<div class='main'><h3>Please enter your details to log in</h3>";
  $error = $user = $pass = $type = "";

  if (isset($_POST['user']))
  {
    $user = sanitizeString($_POST['user']);
    $pass = md5(sanitizeString($_POST['pass']));
    $type = sanitizeString($_POST['type']);

    if ($user == "" || $pass == "" || $type == "")
        $error = "Not all fields were entered<br><br>";
    else
    {
      $result = queryMySQL("SELECT user,pass FROM $type
        WHERE user='$user' AND pass='$pass'");

      if ($result->num_rows == 0)
      {
        $error = "<span class='error'>Username/Password
                  invalid</span><br><br>";
      }
      else
      {
        $result = queryMySQL("SELECT user,pass FROM $type
          WHERE user='$user' AND pass='$pass' AND active='1'");

        if ($result->num_rows == 0)
        {
          $error = "<span class='error'>Account has not been verified. Please check your
                    email to verify.</span><br><br>";
        }
        else
        {
          $_SESSION['user'] = $user;
          $_SESSION['pass'] = $pass;
          header('Location: index.php');
        }
      }
    }
  }

    echo "<div class='row'><div class='col-md-4 left-col col-height'>" .
         "<form method='post' action='login.php'>$error" .
         "<span class='fieldname'>Username</span><input type='text'" .
         "maxlength='16' name='user' value='$user' placeholder='username'><br>" .
         "<span class='fieldname'>Password</span><input type='password'" .
         "maxlength='16' name='pass' value='$pass' placeholder='password'><br>" .
         "<form action='login.php'><span class='fieldname'>Account type</span><input type='radio'" .
         "name='type' value='Student' checked='checked'> Student  ".
         "<input type='radio' name='type' value='Admin'> Admin " .
         "<input type='radio' name='type' value='Super_admin'> Superadmin<br>" .
         "<span class='fieldname'>&nbsp;</span>" .
         "<input class='btn-primary' type='submit' value='Login'></form></div>" .
         "<div class='col-md-8 right-col col-height'><img src='img/background/login.jpg'" .
         " height='122' width='600'></div></div>";

    echo "<br><a href='reset_password.php' class='btn-primary'>Forgot Your Password?</a>";

    echo "</div><br><br>";
    require_once 'footer.php';
?>

  <br>
  </body>
</html>
