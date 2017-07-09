<?php

  session_start();

  echo "<!DOCTYPE html>\n<html><head>";

  require_once 'functions.php';

  $userstr = ' (Guest)';

  if (isset($_SESSION['user']))
  {
    $user     = $_SESSION['user'];
    $loggedin = TRUE;
    $userstr  = " (Hello, $user)";
  }
  else $loggedin = FALSE;

  echo "<title>$appname$userstr</title><link rel='stylesheet' " .
       "href='styles.css?<?php echo time(); ?>' type='text/css'>"                     .
       "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>" .
       "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>" .
       "</head><body>"                 .
       "<script src='javascript/javascript.js'></script>" .
       "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>" .
       "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>";
       echo "<script src='javascript/google_maps.js'></script>";
       echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js'></script>";
       echo "<div class='appname'>&emsp;<img id='logo' src='img/background/ucf.jpg'>$appname$userstr<br>" .
       "<div class='aligncenter' style='width:100%px;height:0;border-top:2px solid blue;padding-top:25px;'></div>";
?>

  <div id="fb-root"></div>
  <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : 'UniversityEvents',
      xfbml      : true,
      version    : 'v2.8'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
  </script>

<?php
  if ($loggedin)
  {
    $result1 = queryMysql("SELECT * FROM Super_admin WHERE user='$user'");
    $result2 = queryMysql("SELECT * FROM Admin WHERE user='$user' AND Active='1'");
    $row = $result1->fetch_array();
    $uni = $row['uni'];

    if ($result1->num_rows)
    {
      $result = queryMysql("SELECT * FROM Creates_uni WHERE user='$user'");
      $row = $result->fetch_array();
      $id  = $row['university_id'];
      $result = queryMysql("SELECT name FROM University WHERE university_id='$id'");
      $row = $result->fetch_array();
      $uni = $row['university_id'];

      echo "<div id='menu' class='container-fluid'><ul class='nav nav-pills nav-justified menu'>" .
           "<li $home><a href='index.php'>Home</a></li>" .
           "<li class='nav-item dropdown $profile'><a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>" .
           "Profile<span class='caret'></span></a><div class='dropdown-menu'>" .
           "<a class='dropdown-item' href='user_profile.php?user=$user'>View Your Profile<br></a>" .
           "<a class='dropdown-item' href='edit_user_profile.php'>Edit Your Profile<br></a></div></li>" .
           "<li class='nav-item dropdown $univ'><a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>" .
           "University<span class='caret'></span></a><div class='dropdown-menu'>" .
           "<a class='dropdown-item' href='uni_profile.php?uni=$id'>View Your Uni Profile<br></a>" .
           "<a class='dropdown-item' href='edit_uni_profile.php'>Edit Your Uni Profile</a></div></li>" .
           "<li><a href='logout.php'>Log out</a></li></ul><br>";
    }
    elseif ($result2->num_rows)
    {
      $result = queryMysql("SELECT name FROM Rso WHERE user='$user'");
      $result = $result->fetch_array();
      $rso = $result['name'];

      echo "<div id='menu' class='container-fluid'><ul class='nav nav-pills nav-justified menu'>" .
           "<li $home><a href='index.php'>Home</a></li>" .
           "<li class='nav-item dropdown $profile'><a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>" .
           "Profile<span class='caret'></span></a><div class='dropdown-menu'>" .
           "<a class='dropdown-item' href='user_profile.php?user=$user'>View Profile<br></a>" .
           "<a class='dropdown-item' href='edit_user_profile.php'>Edit Profile</a></div></li>" .
           "<li class='nav-item dropdown $events'><a class='dropdown-toggle' data-toggle='dropdown' href='#'>" .
           "Events<span class='caret'></span></a><div class='dropdown-menu'>" .
           "<a class='dropdown-item' href='google_maps.php'>Create Event<br></a>" .
           "<a class='dropdown-item' href='list_events.php'>List Owned Events<br></a></div></li>" .
           "<li class='nav-item dropdown $view_rso'><a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>" .
           "RSO<span class='caret'></span></a><div class='dropdown-menu'>" .
           "<a href='rso_profile.php?rso=$rso'>View Your Rso<br></a>" .
           "<a href='edit_rso_profile.php?rso=$rso'>Edit Your Rso</a></div></li>" .
           "<li><a href='logout.php'>Log out</a></li></ul><br>";
    }
    else
    {
      echo "<div id='menu' class='container-fluid'><ul class='nav nav-pills nav-justified menu'>" .
           "<li $home><a href='index.php'>Home</a></li>" .
           "<li class='nav-item dropdown $profile'><a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>" .
           "Profile<span class='caret'></span></a><div class='dropdown-menu'>" .
           "<a class='dropdown-item' href='user_profile.php?user=$user'>View Profile<br></a>" .
           "<a class='dropdown-item' href='edit_user_profile.php'>Edit Profile</a></div></li>" .
           "<li class='nav-item dropdown $search'><a class='nav-link dropdown-toggle' data-toggle='dropdown' href='#'>" .
           "Search<span class='caret'></span></a><div class='dropdown-menu'>" .
           "<a class='dropdown-item' href='search_rso.php'>Search RSOs<br></a>" .
           "<a class='dropdown-item' href='search_event.php'>Search Events</a>" .
           "<li><a href='logout.php'>Log out</a></li></ul><br>";
    }
  }
  else
  {
    echo "<div id='menu' class='container-fluid'><ul class='pill menu nav nav-pills nav-justified'>" .
         "<li $home><a href='index.php'>Home</a></li>"                .
         "<li $signup><a href='signup.php'>Sign up</a></li>"            .
         "<li $login><a href='login.php'>Log in</a></li>"     .
         "<br><br>";
  }
?>
