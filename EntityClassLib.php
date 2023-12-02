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

class Album {
    public $Album_Id;
    public $Title;
    public $Description;
    public $Owner_Id;
    public $Accecssibility_Code;
    
    public function __construct($Album_Id, $Title, $Description, $Owner_Id, $Accessibility_Code)
    {
        $this->Album_Id = $Album_Id;
        $this->Title = $Title;
        $this->Description = $Description;
        $this->Owner_Id = $Owner_Id;
        $this->Accessibility_Code = $Accessibility_Code;
    }
    
    public function getAlbumId() 
    {
        return $this->Album_Id;
    }
    
    public function getTitle() 
    {
        return $this->Title;
    }
    
    public function getDescription() 
    {
        return $this->Description;
    }
    
    public function getOwnerId() 
    {
        return $this->Owner_Id;
    }
    
    public function getAccessibilityCode() 
    {
        return $this->Accessibility_Code;
    }
}

class Accessibility {
    public $Accessibility_Code;
    public $Description;
    
    public function __construct($Accessibility_Code, $Description)
    {
        $this->Accessibility_Code = $Accessibility_Code;
        $this->Description = $Description;
    }
    
    public function getAccessibilityCode() 
    {
        return $this->Accessibility_Code;
    }
    
    public function getDescription() 
    {
        return $this->Description;
    }
}

class Picture {
    public $Picture_Id;
    public $Album_Id;
    public $File_Name;
    public $Title;
    public $Description;
    
    public function __construct($Picture_Id, $Album_Id, $Title, $File_Name, $Owner_Id, $Description)
    {
        $this->Picture_Id = $Picture_Id;
        $this->Album_Id = $Album_Id;
        $this->Title = $Title;
        $this->File_Name = $File_Name;
        $this->Owner_Id = $Owner_Id;
        $this->Description = $Description;
    }
    
    public function getPictureId() 
    {
        return $this->Picture_Id;
    }
    
    public function getAlbumId() 
    {
        return $this->Album_Id;
    }
    
    public function getTitle() 
    {
        return $this->Title;
    }
   
    public function getFileName() 
    {
        return $this->Title;
    }
    
    public function getOwnerId() 
    {
        return $this->Owner_Id;
    }
    
    public function getDescription() 
    {
        return $this->Description;
    }
}

