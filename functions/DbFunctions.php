<?php

// Original for MAC
include_once '../EntityClassLib.php';

//  Windows
//include_once '../SocialMedia/EntityClassLib.php';

function getMyPDO() {
    $config = parse_ini_file("SocialMedia.ini");
    extract($config);
    return new PDO($dsn, $scriptUser, $scriptPassword);
}

function userIdExists($uid) {
    $myPdo = getMyPDO();

    $sql = "SELECT UserId, Name, Phone FROM user WHERE UserId = :uid";
    $result = $myPdo->prepare($sql);
    $result->execute(['uid' => $uid]);
    $row = $result->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return new User($row['UserId'], $row['Name'], $row['Phone']);
         
    }
    
    return null;
}

function addUser($uid, $name, $phone, $pw) {
    $myPdo = getMyPDO();

    $sql = "INSERT INTO user(UserId, Name, Phone, Password) VALUES(:uid, :name, :phone, :pw)";
    $statement = $myPdo->prepare($sql);  
    $statement->execute(['uid' => $uid, 'name' => $name, 'phone' => $phone, 'pw' => $pw]);
}

function getUserByIdAndPassword($uid, $pw) {
        $myPdo = getMyPDO();
        
        $sql = "SELECT UserId, Name, Phone FROM user WHERE UserId = :uid AND Password = :pw";
        $result = $myPdo->prepare($sql);
        $result->execute(['uid' => $uid, 'pw' => $pw]);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['UserId'], $row['Name'], $row['Phone']);
        }   
        return null;
    }

function getAllAccessCodes() {
    $myPdo = getMyPDO();
    
    $sql = "SELECT Accessibility_Code, Description FROM cst8257project.accessibility";
    $result = $myPdo->query($sql);

    return $result;
}

function addAlbum($txtTitle, $txtAreaDescription, $uid, $selAccessCode) {
    $myPdo = getMyPDO();

    $sql = "INSERT INTO cst8257project.album (Title, Description, Owner_Id, Accessibility_Code) "
            ."VALUES(:txtTitle, :txtAreaDescription, :uid, :selAccessCode)";
    
    $statement = $myPdo->prepare($sql);  
    $statement->execute(['txtTitle' => $txtTitle, 'txtAreaDescription' => $txtAreaDescription,'uid' => $uid, 'selAccessCode' => $selAccessCode]);
}

function getAlbumsById($uid) {
    $myPdo = getMyPDO();
    
    $sql = "SELECT Album_Id, Title, Description, Owner_Id, Accessibility_Code "
            ."FROM cst8257project.album "
            ."WHERE album.Owner_Id = :uid";
            
    $result = $myPdo->prepare($sql);
    $allAlbums = array();
    $result->execute(['uid' => $uid]);
    
    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        foreach ($result as $row)
        {
            $album = new Album( $row['Album_Id'], $row['Title'], $row['Description'], $row['Owner_Id'], $row['Accessibility_Code']);
            $allAlbums[] = $album;
        }
        return $allAlbums;
    } 
    else {
        throw new Exception("Query failed! SQL statement: $sql");
    }     
}

function getDescByAccessCode($ac) {
    $myPdo = getMyPDO();
    
    $sql = "SELECT Accessibility_Code, Description FROM cst8257project.accessibility "
            ."WHERE Accessibility_Code = :ac";
    
    $result = $myPdo->prepare($sql);
    $result->execute(['ac' => $ac]);
    
    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if($row) {
            return new Accessibility($row['Accessibility_Code'], $row['Description']);
        } 
        else {
            return null;
        }
    } 
    else {
        throw new Exception("Query failed! SQL statement: $sql");
    }     
}

function getCtPictures($albumid){
    $myPdo = getMyPDO();
    
    $sql = "SELECT Album_Id, COUNT(Picture_Id) AS ctPictures FROM cst8257project.picture "
            ."WHERE Album_Id = :albumid GROUP BY Album_Id";
    
    $result = $myPdo->prepare($sql);
    $result->execute(['albumid' => $albumid]);
    
    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if($row){
            return $row['ctPictures'];
        } 
        else {
            return null;
        }
    }
    else {
        throw new Exception("Query failed! SQL statement: $sql");
    }     
}

function saveMyAlbumsChanges($albumid, $accesscode){
    $myPdo = getMyPDO();
   
    $sql = "UPDATE cst8257project.album SET Accessibility_Code=:accesscode "
            ."WHERE Album_Id = :albumid";

    $resultSet = $myPdo->prepare($sql);
    $resultSet->execute(['albumid'=> $albumid, 'accesscode'=> $accesscode]);
}


function deletePictures($albumid){
    $myPdo = getMyPDO();
    
    $sql = "DELETE FROM cst8257project.picture WHERE album_id = :albumid";
   
    $result = $myPdo->prepare($sql);
    $result->execute(['albumid'=> $albumid]);
}

function deleteAlbum($albumid)
{
    $myPdo = getMyPDO();
    
    $sql = "DELETE FROM cst8257project.album WHERE album_id = :albumid";
    $result = $myPdo->prepare($sql);
    $result->execute(['albumid'=> $albumid]);
}

function getFriendsList($userId)
{
    $myPdo = getMyPDO();
    
    $sql1 = "SELECT Friend_RequesteeId FROM Friendship "
            . "WHERE Friend_RequesterId = :userId AND Status = 'accepted'";
    
    $result1 = $myPdo->prepare($sql1);
    $result1->execute(['userId' => $userId]);
    
    $friendList1Arr = array();
    
    foreach ($result1 as $row)
    {
         $friendList1 = new Friendship($row['Friend_RequesteeId']);
         $friendList1Arr[] = $friendList1;
    }
    
    
    $sql2 = "SELECT Friend_RequesterId FROM Friendship "
            . "WHERE Friend_RequesteeId = :userId AND Status = 'accepted'";
    
    $result2 = $myPdo->prepare($sql2);
    $result2->execute(['userId' => $userId]);
    
    $friendList2Arr = array();
    foreach ($result2 as $row)
    {
         $friendList2 = new Friendship($row['Friend_RequesterId']);
         $friendList2Arr[] = $friendList2;
    }
    
    $friendsList = array_merge($friendList1Arr, $friendList2Arr);
    
    return $friendsList;
    
    
}

function getFriendRequestersFor($userId) {
    $myPdo = getMyPDO();
    
    $sql = "SELECT Friend_RequesterId FROM Friendship "
            . "WHERE Friend_RequesteeId = :userId AND Status = 'request'";
    
    $result = $myPdo->prepare($sql);
    $result->execute(['userId' => $userId]);
    $friendRequesterListArr = array();
    
    foreach ($result as $row)
    {
         $friendRequesterList = new Friendship($row['Friend_RequesterId']);
         $friendRequesterListArr[] = $friendRequesterList;
    }
    return $friendRequesterListArr;
    
    
}

function acceptFriendRequest($userId, $requesterId) {
    $myPdo = getMyPDO();
    
    $sql = "UPDATE Friendship SET Status = 'accepted' WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :userId";
    $result = $myPdo->prepare($sql);
    $result->execute(['requesterId' => $requesterId, 'userId' => $userId]);
    
}

function sendFriendRequest($requesterId, $requesteeId) {
    $myPdo = getMyPDO();
    
    $sql = "INSERT INTO Friendship VALUES("
           . "(SELECT UserId FROM user WHERE UserId = :requesterId),"
           . "(SELECT UserId FROM user WHERE UserId = :requesteeId),"
           . "(SELECT Status_Code FROM friendshipstatus WHERE Status_Code = 'request'))";
    $result = $myPdo->prepare($sql);
    $result->execute(['requesterId' => $requesterId, 'requesteeId' => $requesteeId]);

    
}

