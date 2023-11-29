<?php

include_once '../EntityClassLib.php';

function getMyPDO() {
    $config = parse_ini_file("SocialMedia.ini", true);
    $dbConfig = $config['database'];

    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};port={$dbConfig['port']};charset=utf8";
    $pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
    return $pdo;
}

function userIdExists($uid) {
    $myPdo = getMyPDO();

    $sql = "SELECT UserId FROM user WHERE UserId = :uid";
    $result = $myPdo->prepare($sql);
    $result->execute(['uid' => $uid]);

    if ($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return true;
        } else {
            return false;
        }
    } else {
        throw new Exception("Query failed! SQL statement: $sql");
    }       
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
        
        if ($result) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new User($row['UserId'], $row['Name'], $row['Phone']);
            } else {
                return null;
            }
        } else {
            throw new Exception("Query failed! SQL statement: $sql");
        }      
    }