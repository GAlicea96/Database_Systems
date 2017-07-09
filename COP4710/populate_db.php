<?php

  //add status and contact info

  require_once 'functions.php';

  $ucfevents = simplexml_load_file("https://events.ucf.edu/feed.xml");
  foreach($ucfevents->event as $event):
    $title = $event->title;
    $start_date = $event->start_date;
    $end_date = $event->end_date;
    $location = $event->location;
    $description = $event->description;
    $contact = $event->contact_person;
    echo "$title at $location on $start_date<br>";
    echo "contact person is $contact<br><br>";
  endforeach;

  $ucfevents = simplexml_load_file("http://events.ucf.edu/upcoming/feed.xml");
  foreach($ucfevents->event as $event):
    $title = $event->title;
    $start_date = $event->start_date;
    $end_date = $event->end_date;
    $location = $event->location;
    $description = $event->description;
    $contact = $event->contact_person;
    echo "$title at $location on $start_date<br>";
    echo "contact person is $contact<br><br>";
  endforeach;

?>
