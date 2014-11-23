<?php

namespace Ibillmaker\Hub\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
/**
 * IAUserControlIAAddressControllerler is a controller to doth Internal API call to user.
 */

class IAAddressController extends Controller
{
    /**
     * @param Object $request
     * This method is for getUserDetail for User Interface 
     * @return JSONObject 
     */
    public function getUserDetailsAction(Request $request)
    {
        try{
            $userId = $request->get('id');
            $userDetails = $this->container->get('ibillmaker.hub.core.service.user')->getUserDetails($userId);
            
            return $this->container->get('ibillmaker.hub.core.service.response_create')->create(200,'yes user exist',$userDetails);
        }  catch (\Exception $ex){
            $code = ($ex->getCode() > 0 && $ex->getCode() < 500) ? $ex->getCode() : 500;
            $responseResult = $this->container->get('ibillmaker.hub.core.service.response_create')->create($code, $ex->getMessage(), NULL); 
            return $responseResult;
        }       
    }
    
    /**
     * @param Object $request
     * This method is for checkUserName for perticular Client's people group if user name is already allocated to perticular person.
     */
    public function checkUserNameAction(Request $request){
        try {
            $userName = $request->get('userName');
            $id = $request->get('id');
            $user = $this->container->get('ibillmaker.hub.core.service.user')->checkUserName($userName,$id);
            return $this->container->get('ibillmaker.hub.core.service.response_create')->create(20,'User Name is Available',$user);
        }  catch (\Exception $ex){
            $code = ($ex->getCode() > 0 && $ex->getCode() < 500) ? $ex->getCode() : 500;
            $responseResult = $this->container->get('ibillmaker.hub.core.service.response_create')->create($code, $ex->getMessage(), NULL); 
            return $responseResult;
        }    
    }    
    /**
     * @param Object $request
     * This method is for Save User Details.
     */
    public function saveUserDetailsAction(Request $request){
        try {
            $userDetails = $request->request->all();
            $userDetails = $this->container->get('ibillmaker.hub.core.service.user')->saveUserDetails($userDetails);
            return $this->container->get('ibillmaker.hub.core.service.response_create')->create(20,'User successfully Saved.',$userDetails);
        }  catch (\Exception $ex){
            $code = ($ex->getCode() > 0 && $ex->getCode() < 500) ? $ex->getCode() : 500;
            $responseResult = $this->container->get('ibillmaker.hub.core.service.response_create')->create($code, $ex->getMessage(), NULL); 
            return $responseResult;
        }    
    }    
    
    public function createAddressAction(Request $request){
        try{
            
            $country = $this->container->get('sylius.repository.country')->findAll(array());

            $addressDetail = $request->request->all();
            $addressDetailResponse = $this->container->get('ibillmaker.hub.core.service.address')->createAddress($addressDetail);
            
            return $addressDetailResponse;
           
        } catch (\Exception $ex) {
            
            $code = ($ex->getCode() > 0 && $ex->getCode() < 500) ? $ex->getCode() : 500;
            $responseResult = $this->container->get('ibillmaker.hub.core.service.response_create')->create($code, $ex->getMessage(), NULL); 
            return $responseResult;

        }
    }
        
}
