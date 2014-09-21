<?php

namespace Ibillmaker\Hub\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
/**
 * IAUserController is a controller to doth Internal API call to user.
 */

class IAUserController extends Controller
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
            $responseResult = $this->container->get('dacast.api.response.create')->create($code, $ex->getMessage(), NULL); 
            return $responseResult;
        }       
    }
}
