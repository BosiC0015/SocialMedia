<?php

// Original for MAC
include_once './DbFunctions.php';

//  Windows
// include_once '../SocialMedia/functions/DbFunctions.php';

function validateUid($uid) {
    if (empty($uid)) {
        return '<div class="col-md-6 text-danger">User ID is required</div>';
    } else if (userIdExists($uid)) {
        return '<div class="col-md-6 text-danger">User ID already exists</div>';
    }
    return null;
}

function validateName($name) {
    if (empty($name)) {
        return '<div class="col-md-6 text-danger">Name is required</div>';
    }
    return null;
}

function validatePhone($phone) {
    if (!preg_match("/[2-9][0-9][0-9]-[2-9][0-9][0-9]-[0-9][0-9][0-9][0-9]/", $phone)) {
        return '<div class="col-md-6 text-danger">Invalid phone number</div>';
    }
    return null;
}

function validatePw($pw) {
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$/", $pw)) {
        return '<div class="col-md-6 text-danger">Password should be at least 6 characters long, contains at least one upper case, one lowercase and one digit.</div>';
    }
    return null;
}

function validatePwAgain($pw, $pw2) {
    if ($pw2 != $pw) {
        return '<div class="col-md-6 text-danger">Passwords do not match</div>';
    }
    return null;
}

function validateLoginUId($uid) {
    if (empty($uid)) {
        return '<div class="col-md-6 text-danger">User ID cannot be blank</div>';
    }
}

function validateLoginPw($pw) {
    if (empty ($pw)) {
        return '<div class="col-md-6 text-danger">Password cannot be blank</div>';
    }
}