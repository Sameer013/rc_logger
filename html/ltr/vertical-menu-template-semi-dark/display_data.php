<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=tnbms', 'root', 'Sania@2003');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM pos_from_nose";
    $stmt = $dbh->query($query);

    $result_array = [];
    if ($stmt->rowCount() > 0) {
        $serialNumber = 1;
        $remark="No wear";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $min = $row['min'];
            $max = $row['max'];

            $valuesArray = array($row['s1'], $row['s2'], $row['s3'], $row['s4']);

            foreach ($valuesArray as $value) {
                $status = ($value >= $min && $value <= $max) ? 'Active' : 'Inactive';

                $result_array[] = array(
                    "serialNumber" => $serialNumber,
                    "value" => $value,
                    "status" => $status,
                    "remark" => $remark,

                );

                $serialNumber++;
            }
        }

        echo json_encode(["data" => $result_array]);
    } else {
        echo json_encode(["data" => []]);
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

