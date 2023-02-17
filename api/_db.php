<?php

$db_exists = file_exists("daypilot.sqlite");

$db = new PDO('sqlite:daypilot.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

if (!$db_exists) {
    //create the database
    $db->exec("CREATE TABLE IF NOT EXISTS events (
                        id INTEGER PRIMARY KEY, 
                        name TEXT, 
                        start DATETIME, 
                        end DATETIME,
                        color TEXT,
                        persona TEXT,
                        status TEXT)");

    $messages = array(
                    array('name' => 'Event 1',
                        'start' => '2023-02-17T15:00:00',
                        'end' => '2023-02-17T18:00:00',
                        'color' => '#f1c232',
                        'persona' => 'ken',
                        'status' => 'approved')
                );

    $insert = "INSERT INTO events (name, start, end,color, persona, status) VALUES (:name, :start, :end, :color, :persona, :status)";
    $stmt = $db->prepare($insert);
 
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':end', $end);
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':persona', $persona);
    $stmt->bindParam(':status', $status);

    foreach ($messages as $m) {
      $name = $m['name'];
      $start = $m['start'];
      $end = $m['end'];
      $color = $m['color'];
      $persona = $m['persona'];
      $status = $m['status'];
      $stmt->execute();
    }
    
}
