<?php
require_once '_db.php';

$json = file_get_contents('php://input');
$params = json_decode($json);

$insert = "INSERT INTO events (name, start, end, color, persona, status, rtype, room) VALUES (:name, :start, :end, :barColor, :persona, :status, :rtype, :room)";

$stmt = $db->prepare($insert);

$stmt->bindParam(':start', $params->start);
$stmt->bindParam(':end', $params->end);
$stmt->bindParam(':name', $params->text);
$stmt->bindParam(':persona', $params->text1);
$stmt->bindParam(':barColor', $params->barColor);
$stmt->bindParam(':status', $params->status);
$stmt->bindParam(':rtype', $params->rtype);
$stmt->bindParam(':room', $params->room);
$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Created with id: '.$db->lastInsertId();
$response->id = $db->lastInsertId();

header('Content-Type: application/json');
echo json_encode($response);
