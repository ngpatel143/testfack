<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ibillmaker\Hub\CoreBundle\Service;

/**
 * Description of ContactService
 *
 * @author ngpatel
 */
class ContactService {
    
    /**
     * contact is a list of users in one company.
     * 
     * @access public
     * @param Array $contactDetails This method createNewContact accept the list of the give parameters 
     * key index
     * 
     * 
     * firstName 
     * lastName
     * userName** 
     * password** 
     * email*
     * phoneNumber 
     * mobileNumber
     * ================ please Note * at the end of variable description means this field is required
     * ================ please Note * at the end of variable description means this field is required but it is depend on condition
     * @return json return response in json
     */
    public function createContact($contactDetails){
        
    }
    
    /**
     * @param Array $contactDetails  Description is same as createNewContact method 
     * @return json return response in json
     */
    public function updateContact($contactDetails){
        
    }
    
    /**
     * @param Array $contactDetails  Description is same as createNewContact method 
     * @return json return response in json
     */
    public function deleteContact($contactDetails){
        
    }
    
    
    
}
