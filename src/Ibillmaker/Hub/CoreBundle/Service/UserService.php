<?php
namespace Ibillmaker\Hub\CoreBundle\Service;

/**
 * This is service to communicate with angujar js it is RESTfull service.
 * It will returns JSON objects as a response.
 * Auther Nishant Patel
 */
Class UserService
{
    
    /**
     * @param integer $userId get User Details. ex. clients details.
     * @return JSON it will returns users details to display in view.
     */
    public function getUserDetails($userId)
    {
        
    }
    
    /**
     * @param array $userDetails it will update users details. ex. clients, vendors details 
     * @return JSON this method will returns the HTTP Response 
     */
    public function saveUserDetails($userDetails)
    {
        
    }
    
}
