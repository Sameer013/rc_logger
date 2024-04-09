<?php
header('Content-Type: application/json');
include "conn.php";

$dataPoints = array();
$labels = array();
$result = $conn->query("SELECT * FROM readings ORDER BY time_stamp DESC LIMIT 1");

if ($result->rowcount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $dataPoints[] = $row['s1'];
    $dataPoints[] = $row['s2'];
    $dataPoints[] = $row['s3'];
    $dataPoints[] = $row['s4'];

    $timestamp = date("Y-m-d H:i:s", strtotime($row['time_stamp']));
    $labels[] = $timestamp;
}

$minMaxResult = $conn->query("SELECT * FROM pos_from_nose LIMIT 1");
$minMaxRow = $minMaxResult->fetch(PDO::FETCH_ASSOC);
$minValue = $minMaxRow['min'];
$maxValue = $minMaxRow['max'];

$response = array(
    'labels' => array_reverse($labels),
    'values' => array_reverse($dataPoints),
    'minMaxValues' => array(
        'min' => $minValue,
        'max' => $maxValue,
    )
);
echo json_encode($response);
?>
