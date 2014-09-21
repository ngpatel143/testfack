<?php
namespace Ibillmaker\Hub\CoreBundle\Service;
use Doctrine\ORM\Query;
/**
 * This is service to communicate with angujar js it is RESTfull service.
 * It will returns JSON objects as a response.
 * Auther Nishant Patel
 */
Class UserService
{
    
    private $container;
    public function __construct($container) {
        $this->container = $container;
    }
    /**
     * @param integer $userId get User Details. ex. clients details.
     * @return JSON it will returns users details to display in view.
     */
    public function getUserDetails($userId)
    {

      $user =   $this->container->get('sylius.repository.user')->findOneBy(array('id'=>$userId));
      if($user){
        $result['firstName'] = $user->getFirstName();
        $result['lastName'] = $user->getLastName();
        $result['email'] = $user->getEmail();
        $result['userName'] = $user->getUserName();
        return $result;
      }else{
          throw new Exception('Page Not Found', 404);
      }
      
    }
    
    /**
     * @param array $userDetails it will update users details. ex. clients, vendors details 
     * @return JSON this method will returns the HTTP Response 
     */
    public function saveUserDetails($userDetails)
    {
        
    }
    
    public function checkUserName($userName,$adminId)
    {
        $user = $this->container->get('sylius.repository.user')->findOneBy(array(''));
    }
}
