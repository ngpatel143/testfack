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
    /*
     * @var string
     * desc companyId 
     */
    protected $companyId;
    // admin id ex. user(admin) who creates the client.
    protected $admin;
    // people id ex. clientId for the contact of that perticular client 
    protected $people;
    // phone number
    protected $phoneNumber;
    // mobile number
    protected $mobileNumber;

    /**
     * @param array $datas to set the list of the all the parameters.
     */
    public function __construct($datas = NULL) 
    {
        parent::__construct();
        if(empty($datas)){
            foreach ($datas as $attributeName => $attributeValue) {
                $this->__setAttribute($attributeName, $attributeValue);
            }
        }
        
    }

    public function __call($name, $arguments)
    {
        $actionPart = substr($name, 0, 3);
        if ($actionPart == 'get' || $actionPart == 'set') {
            $functionName = '__'.$actionPart.'Attribute';

            return $this->$functionName(substr($name, 3), $arguments);
        }
    }

    public function __getAttribute($name)
    {
        if (array_key_exists(strtolower($name), $this->datas)) {

            return $this->datas[strtolower($name)];
        }

        return;
    }

    public function __setAttribute($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            $this->datas[strtolower($name)] = $value;
        }

        return $this;
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
  
    public function setCompanyName($companyName)
    {
     $this->companyName = $companyName;
     return $this;
    }
    public function getCompanyName()
    {
        return $this->companyName;
    }
    
    public function setCompanyId($companyId)
    {
     $this->companyId = $companyId;
     return $this;
    }
    
    public function getCompanyId()
    {
        return $this->companyId;
    }
     
    
    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmailCanonical($emailCanonical)
    {
        parent::setEmailCanonical($emailCanonical);
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
