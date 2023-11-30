<?php

class User {
    private $uid;
    private  $name;
    private $phone;
    
    public function __construct($uid, $name, $phone) {
        $this->uid = $uid;
        $this->name = $name;
        $this->phone = $phone;
    }
    
    public function getUserId() {
        return $this->uid;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getPhone() {
        return $this->phone;
    }
    
}

