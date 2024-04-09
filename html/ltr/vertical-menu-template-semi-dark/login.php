<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $username = $_POST["username"];
    $password = $_POST["password"];

  
    $sql = "SELECT * FROM users WHERE user_name = :username AND pass = :hashedPassword";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":hashedPassword", $password);
            $stmt->execute();
            // $result=$stmt->fetchAll();
            $rowCount = $stmt->rowCount();

            // echo ($result);
            
            echo ($rowCount);

            if ($rowCount ==1) {
                echo "success";
            } else {
                echo "failure";
            }

            $stmt->closeCursor();
        } else {
            echo "Statement preparation failed.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>
