<?php

namespace Ibillmaker\Hub\CoreBundle\Entity;

use Sylius\Component\Core\Model\User as BaseUser;
use Sylius\Component\Core\Model\UserInterface;
class User extends BaseUser {

    /*
     * @var string
     * desc companyName 
     */
    protected $companyName;
    // admin id ex. user(admin) who creates the client.
    protected $admin;
    // people id ex. clientId for the contact of that perticular client 
    protected $people;
    // phone number
    protected $phoneNumber;
    // mobile number
    protected $mobileNumber;

    public function __construct() 
    {
        parent::__construct();
    }

    
    public function getAdmin()
    {
        return $this->admin;
    }
    
    public function setAdmin(UserInterface $admin)
    {
     $this->admin = $admin;
     return $this;
    }
  
    public function getPeople()
    {
        return $this->people;
    }
    
    public function setPeople(UserInterface $people)
    {
     $this->people = $people;
     return $this;
    }
    
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    
    public function setPhoneNumber($phoneNumber)
    {
     $this->phoneNumber = $phoneNumber;
     return $this;
    }
   
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }
    
    public function setMobileNumber($mobileNumber)
    {
     $this->mobileNumber = $mobileNumber;
     return $this;
    }
    
    
    
    
    public function __get($property) 
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
  
    public function __set($property, $value) 
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    
        return $this;
    }
    


}
