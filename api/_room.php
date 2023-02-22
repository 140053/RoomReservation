<?php
$db_exists = file_exists("/api/daypilot.sqlite");

if(!$db_exists){    
    $db = new PDO('sqlite:../api/daypilot.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}else{
    echo "no database!";    
}