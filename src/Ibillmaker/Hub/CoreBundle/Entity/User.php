<?php

namespace Ibillmaker\Hub\CoreBundle\Entity;

use Sylius\Component\Core\Model\User as BaseUser;
use Sylius\Component\Core\Model\UserInterface;
class User extends BaseUser {

    protected $companyName;
    protected $admin;

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
