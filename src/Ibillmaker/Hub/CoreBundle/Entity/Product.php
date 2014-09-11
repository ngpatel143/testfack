<?php

namespace Ibillmaker\Hub\CoreBundle\Entity;

use Sylius\Component\Product\Model\Product as BaseProduct;
use Sylius\Component\Core\Model\UserInterface;
class Product extends BaseProduct {

    protected $adminId;
    protected $user;

    public function __construct() 
    {
        parent::__construct();
    }

    
    public function getAdminId()
    {
        return $this->adminId;
    }
    
    public function setAdminId($adminId)
    {
     $this->adminId = $adminId;
     return $this;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    public function setUser(UserInterface $user)
    {
     $this->user = $user;
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
