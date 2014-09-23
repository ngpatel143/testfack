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
          throw new \Exception('Page Not Found', 404);
      }
      
    }
    
    /**
     * @param array $userDetails it will update users details. ex. clients, vendors details 
     * @return JSON this method will returns the HTTP Response 
     */
    public function saveUserDetails($userDetails)
    {
        
    }
    
    /**
     * @param string $userName 
     * checkUserName for perticular admin is it exist or not.
     */
    public function checkUserName($userName)
    {
        try{
            if(empty($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))){ 
                throw new \Exception('user has not rights to access this content', 401);
                exit;
            }
            $user = $this->container->get('security.context')->getToken()->getUser();
            
            // check if current logged in user is a admin or not. 
            $adminUser = ($user->getAdmin() == NULL)? $user :$user->getAdmin();
            $adminId = $adminUser->getId();
            $userDetails = $this->container->get('sylius.repository.user')->findOneBy(array('username'=>$userName));
            
            if(!isset($userDetails)){
                return array('userName'=>$userName);
            }else{
                // for detail about the error codes check out the documentation.
                throw new \Exception('UserName already Exist',21);
            }
        }  catch (\Exception $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
