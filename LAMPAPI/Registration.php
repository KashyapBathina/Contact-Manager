<?php
header("Content-Type: application/json");
require_once "config.php"; 

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the JSON input
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input["first"], $input["last"], $input["email"], $input["phone"], $input["password"])) {
        $response["message"] = "Missing required fields.";
        echo json_encode($response);
        exit();
    }

    $firstName = trim($input["first"]);
    $lastName = trim($input["last"]);
    $email = trim($input["email"]);
    $phoneNumber = trim($input["phone"]);
    $password = $input["password"];

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber) || empty($password)) {
        $response["message"] = "All fields are required.";
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["message"] = "Invalid email format.";
        echo json_encode($response);
        exit();
    }

    if (!preg_match("/^\+?[0-9]{7,15}$/", $phoneNumber)) {
        $response["message"] = "Invalid phone number format.";
        echo json_encode($response);
        exit();
    }


    try {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT ID FROM Users WHERE Email = :email");
        $stmt->execute(["email" => $email]);

        if ($stmt->fetch()) {
            $response["message"] = "Email is already registered.";
        } else {
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO Users (DateCreated, FirstName, LastName, Email, PhoneNumber, Password) 
                                   VALUES (NOW(), :firstName, :lastName, :email, :phoneNumber, :password)");
            $stmt->execute([
                "firstName" => $firstName,
                "lastName" => $lastName,
                "email" => $email,
                "phoneNumber" => $phoneNumber,
                "password" => $password
            ]);

            $response["success"] = true;
            $response["message"] = "Registration successful.";
        }
    } catch (PDOException $e) {
        $response["message"] = "Database error: " . $e->getMessage();
    }
}

echo json_encode($response);
?>
