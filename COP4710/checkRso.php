<?php
  require_once 'functions.php';

  if (isset($_POST['name']))
  {
    $name   = sanitizeString($_POST['name']);
    $result = queryMysql("SELECT * FROM Rso WHERE name='$name'");

    if ($result->num_rows)
      echo  "<br><span class='taken'>&nbsp;&#x2718; " .
            "This RSO name is taken</span>";
    else
      echo "<br><span class='available'>&nbsp;&#x2714; " .
           "This RSO name is available</span>";
  }
?>
