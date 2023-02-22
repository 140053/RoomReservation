<?php
require_once '_db.php';

$json = file_get_contents('php://input');
$params = json_decode($json);

$insert = "UPDATE events SET room=:room,  rtype = :rtype, status = :status, color = :barColor, name = :text, start = :start, end = :end WHERE id = :id";

$stmt = $db->prepare($insert);

$stmt->bindParam(':id', $params->id);
$stmt->bindParam(':text', $params->text);
$stmt->bindParam(':start', $params->start);
$stmt->bindParam(':end', $params->end);
$stmt->bindParam(':barColor', $params->color);
$stmt->bindParam(':status', $params->status);
$stmt->bindParam(':rtype', $params->rtype);
$stmt->bindParam(':room', $params->room);

$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->message = 'Update successful';

header('Content-Type: application/json');
echo json_encode($response);
