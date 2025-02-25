<?php

$inData = getRequestInfo();

$searchResults = "";
$searchCount = 0;

$conn = new mysqli("localhost", "root", "+Ac2iBTTust=", "SmallProject10");
if ($conn->connect_error)
{
    returnWithError($conn->connect_error);
}
else
{
    if (isset($inData["search"]) && $inData["search"] != "") {
        // If search term is provided
        $searchField = "%" . $inData["search"] . "%";
        $stmt = $conn->prepare("SELECT FirstName, LastName, Email, PhoneNumber FROM Contacts WHERE (FirstName LIKE ? OR LastName LIKE ? OR Email LIKE ? OR PhoneNumber LIKE ?) AND UserId=?");
        $stmt->bind_param("ssssi", $searchField, $searchField, $searchField, $searchField, $inData["UserId"]);
    } else {
        // If no search term, return all contacts
        $stmt = $conn->prepare("SELECT FirstName, LastName, Email, PhoneNumber FROM Contacts WHERE UserID=?");
        $stmt->bind_param("i", $inData["UserId"]);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc())
    {
        if( $searchCount > 0 )
        {
            $searchResults .= ",";
        }
        $searchCount++;
        $searchResults .= '{"FirstName":"' . $row["FirstName"] . '", "LastName":"' . $row["LastName"] . '", "Email":"' . $row["Email"] . '", "PhoneNumber":"' . $row["PhoneNumber"] . '"}';
    }

    if ($searchCount == 0)
    {
        returnWithError("No Records Found");
    }
    else
    {
        returnWithInfo($searchResults);
    }

    $stmt->close();
    $conn->close();
}

function getRequestInfo()
{
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj)
{
    header('Content-type: application/json');
    echo $obj;
}

function returnWithError($err)
{
    $retValue = '{"id":0,"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

function returnWithInfo($searchResults)
{
    $retValue = '{"results":[' . $searchResults . '],"error":""}';
    sendResultInfoAsJson($retValue);
}
?>


