<?php

$user = "root";
$pass = "";

try {
    $dbh = new PDO('mysql:host=localhost;dbname=tnbms', 'root', 'Sania@2003');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_REQUEST['edit_check'] == 'update') {
        $s1 = $_POST['s1'];
        $s2 = $_POST['s2'];
        $s3 = $_POST['s3'];
        $s4 = $_POST['s4'];
        $min = $_POST['min'];
        $max = $_POST['max'];

        $stmt = $dbh->prepare("UPDATE pos_from_nose SET s1=:upd_s1, s2=:upd_s2,
    s3=:upd_s3, s4=:upd_s4 , min=:upd_min , max=:upd_max  WHERE sno = 1");

        $stmt->bindParam(":upd_s1", $s1);
        $stmt->bindParam(":upd_s2", $s2);
        $stmt->bindParam(":upd_s3", $s3);
        $stmt->bindParam(":upd_s4", $s4);
        $stmt->bindParam(":upd_min", $min);
        $stmt->bindParam(":upd_max", $max);
        

        $stmt->execute();


    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>