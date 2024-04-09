<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=tnbms', 'root', 'Sania@2003');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT s1 ,s2 ,s3 , s4 ,min , max FROM pos_from_nose";
    $stmt = $dbh->query($query);

    $result_array = [];

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result_array[] = $row;
        }
        header('Content-type: application/json');
        echo json_encode($result_array);
    }
} catch (PDOException $exception) {
    echo "Some error " . $exception->getMessage();
}
?>