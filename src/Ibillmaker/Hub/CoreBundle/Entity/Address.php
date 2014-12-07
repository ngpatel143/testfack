<?php

/*
 * This file is part of the Ibillmaker package.
 *
 * (c) Nishant Patel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Ibillmaker\Hub\CoreBundle\Entity;

use Sylius\Component\Addressing\Model\Address as baseAddress;

/**
 * Ibillmaler's address model.
 *
 * @author Nishant Patel <ngpatel@outlook.com>
 */
class Address extends baseAddress
{

    /**
     * people id (User id/ client Id)
     * @Type(type="Object", message="User Type Object")
     */
    protected $user;
    
    /**
     * type of address (people's primary contact address or secondary)
     * @Type(type="string", message="it is type of address. ex. primary = pri, secondory = sec")
     */
    protected $type;
    
    
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getUser(){
        return $this->user;
    }
    
    public function setUser($user){
       $this->user = $user;
       return $this;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function setType($type){
       $this->type = $type;
       return $this;
    }
   
}
