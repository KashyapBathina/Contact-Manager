<?php
        $inData = getRequestInfo();

        $firstName = $inData["FirstName"];
        $lastName = $inData["LastName"];
        $userId = $inData["UserId"];

        $conn = new mysqli("localhost", "root", "+Ac2iBTTust=", "SmallProject10");
        if ($conn->connect_error)
        {
                returnWithError($conn->connect_error);
        }
        else
        {
                //Prepare the SQL
                $stmt = $conn->prepare("DELETE FROM Contacts WHERE FirstName = ? AND LastName = ? AND UserID = ?");
                $stmt->bind_param("ssi", $firstName, $lastName, $userId);
                
                
                $stmt->execute();

                //Check if row was deleted
                if ($stmt->affected_rows > 0) {
                    returnWithInfo("Contact deleted successfully");
                } else {
                    returnWithError("No contact found with the provided name or not owned by the user");
                }

                $stmt->close();
                $conn->close();
        }

        //Get input data 
        function getRequestInfo()
        {
                return json_decode(file_get_contents('php://input'), true);
        }

        //Result in JSON format
        function sendResultInfoAsJson($obj)
        {
                header('Content-type: application/json');
                echo $obj;
        }

        //Error messages
        function returnWithError($err)
        {
                $retValue = '{"error":"' . $err . '"}';
                sendResultInfoAsJson($retValue);
        }

        //Success messages
        function returnWithInfo($message)
        {
                $retValue = '{"message":"' . $message . '","error":""}';
                sendResultInfoAsJson($retValue);
        }
?>
