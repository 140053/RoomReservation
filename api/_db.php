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
                        status TEXT,
                        rtype TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS users (
                        id INTEGER PRIMARY KEY, 
                        email TEXT,                         
                        firstname TEXT,
                        lastname TEXT,
                        password TEXT,
                        auth TEXT)");

    $messages = array(
                    array('name' => 'Event 1',
                        'start' => '2023-02-17T15:00:00',
                        'end' => '2023-02-17T18:00:00',
                        'color' => '#f1c232',
                        'persona' => 'ken',
                        'status' => 'approved',
                        'rtype' => 'class')
                );

    $user = array(
        array('email' => 'admin@local.a',
          'firstname' => 'Ken',
          'lastname' => 'Roman',
          'password' => '140053ken',
          'auth' => '0')
    );


    $insert = "INSERT INTO events (name, start, end,color, persona, status, rtype) VALUES (:name, :start, :end, :color, :persona, :status, :rtype)";
    $insert2 = "INSERT INTO users (email, firstname, lastname, password, auth) VALUES (:email, :firstname, :lastname, :password,  :auth)";

    $stmt = $db->prepare($insert);
    $stmt2 = $db->prepare($insert2);
    
    //events
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':end', $end);
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':persona', $persona);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':rtype', $rtype);
    //user
    $stmt2->bindParam(':email', $email);
    $stmt2->bindParam(':firstname', $firstname);
    $stmt2->bindParam(':lastname', $lastname);
    $stmt2->bindParam(':password', $password);
    $stmt2->bindParam(':auth', $auth);

    //events
    foreach ($messages as $m) {
      $name = $m['name'];
      $start = $m['start'];
      $end = $m['end'];
      $color = $m['color'];
      $persona = $m['persona'];
      $status = $m['status'];
      $rtype = $m['rtype'];
      $stmt->execute();
    }
    //user
    foreach ($user as $s){
      $email = $s['email'];
      $firstname = $s['firstname'];
      $lastname = $s['lastname'];
      $password = $s['password'];
      $auth =$s['auth'];
      $stmt2->execute();
    }


    
}
