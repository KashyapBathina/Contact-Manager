<?php
        $inData = getRequestInfo();

        $FirstName = $inData["FirstName"];
        $LastName = $inData["LastName"];  // Fixed missing semicolon
        $Email = $inData["Email"];
        $PhoneNumber = $inData["PhoneNumber"];

        $conn = new mysqli("localhost", "root", "+Ac2iBTTust=", "SmallProject10");
        if ($conn->connect_error)
        {
                returnWithError( $conn->connect_error );
        }
        else
        {
                // Changed "Colors" to "Contacts" to insert data into the correct table
                $stmt = $conn->prepare("INSERT into Contacts (FirstName, LastName, Email, PhoneNumber, UserID) VALUES(?,?,?,?,?)");
                $stmt->bind_param("ssssi", $FirstName, $LastName, $Email, $PhoneNumber, $inData["UserId"]);  // Added UserID parameter
                $stmt->execute();
                $stmt->close();
                $conn->close();
                returnWithError("Success");  // Success - no error
        }

        function getRequestInfo()
        {
                return json_decode(file_get_contents('php://input'), true);
        }

        function sendResultInfoAsJson( $obj )
        {
                header('Content-type: application/json');
                echo $obj;
        }

        function returnWithError( $err )
        {
                $retValue = '{"error":"' . $err . '"}';
                sendResultInfoAsJson( $retValue );
        }
?>

