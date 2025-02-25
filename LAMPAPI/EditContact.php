<?php
$inData = getRequestInfo();

$FirstName = $inData["FirstName"];
$LastName = $inData["LastName"];

$conn = new mysqli("localhost", "root", "+Ac2iBTTust=", "SmallProject10");

if ($conn->connect_error) {
    returnWithError($conn->connect_error);
} else {
    // Get existing contact
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE FirstName=? AND LastName=?");
    $stmt->bind_param("ss", $FirstName, $LastName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        returnWithError("Contact Not Found");
    } else {
        $row = $result->fetch_assoc();

        //Use existing data if new data isn't provided
        $NewFirstName = isset($inData["NewFirstName"]) ? $inData["NewFirstName"] : $row["FirstName"];
        $NewLastName = isset($inData["NewLastName"]) ? $inData["NewLastName"] : $row["LastName"];
        $NewEmail = isset($inData["NewEmail"]) ? $inData["NewEmail"] : $row["Email"];
        $NewPhoneNumber = isset($inData["NewPhoneNumber"]) ? $inData["NewPhoneNumber"] : $row["PhoneNumber"];

        //Update fields
        $stmt = $conn->prepare("UPDATE Contacts SET FirstName=?, LastName=?, Email=?, PhoneNumber=? WHERE FirstName=? AND LastName=?");
        $stmt->bind_param("ssssss", $NewFirstName, $NewLastName, $NewEmail, $NewPhoneNumber, $FirstName, $LastName);

        if ($stmt->execute()) {
            returnWithInfo("Contact Updated Successfully");
        } else {
            returnWithError("Failed to Update Contact");
        }

        $stmt->close();
    }

    $conn->close();
}

function getRequestInfo() {
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}

function returnWithError($err) {
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithInfo($msg) {
    $retValue = '{"message":"' . $msg . '", "error":""}';
    sendResultInfoAsJson($retValue);
}
?>
