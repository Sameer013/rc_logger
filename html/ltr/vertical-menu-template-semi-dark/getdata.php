<?php
header('Content-Type: application/json');
include "conn.php";

$result = $conn->query("SELECT * FROM readings ORDER BY time_stamp DESC");

$data = array();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $timestamp = date("Y-m-d H:i:s", strtotime($row['time_stamp']));
    $data[] = array(
        's1' => (int)$row['s1'],
        's2' => (int)$row['s2'],
        's3' => (int)$row['s3'],
        's4' => (int)$row['s4'],
        'timestamp' => $timestamp,
    );
}

echo json_encode($data);
?>
