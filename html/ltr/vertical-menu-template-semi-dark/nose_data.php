<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=tnbms', 'root', 'Sania@2003');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['check_edit']) == 'true') {
        $sno = $_GET['sno'];

        $query="SELECT s1, s2, s3, s4 , min , max FROM pos_from_nose WHERE sno  = :sno";

        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':sno', $sno, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $result_array = $stmt->fetch(PDO::FETCH_ASSOC);
            header('Content-type: application/json');
            echo json_encode($result_array);
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>