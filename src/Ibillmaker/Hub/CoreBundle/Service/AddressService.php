<?php
namespace Ibillmaker\Hub\CoreBundle\Service;

use Ibillmaker\Hub\CoreBundle\Entity\Address as Address;
use Sylius\Component\Core\Model\UserInterface as UserInterface;
/**
 * Address service is for client vender address,
 * This is service to communicate with angujar js it is RESTfull service.
 * It will returns JSON objects as a response.
 * Auther Nishant Patel
 */
Class AddressService
{
    
    private $container;
    public function __construct($container) {
        $this->container = $container;
        if(empty($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))){ 
                throw new \Exception('user has not rights to access this content', 401);
                exit;
        }
    }
    
    /**
     * @param integer $userId get User Details. ex. clients details.
     * @return JSON it will returns users details to display in view.
     */
    public function getAddress($user,$type)
    {
        // here address is a address object.
        $address =   $this->container->get('sylius.repository.address')->findOneBy(array('user'=>$user,'type'=>$type));
        if($address){
          $result['p_street'] = $address->getStreet();
          $result['p_postcode'] = $address->getPostCode();
          $result['p_city'] = $address->getCity();
          $result['p_country'] = $this->getCountryList();
          return $result;
        }else{
            //throw new \Exception('Page Not Found', 404);
        }
      
    }
    
    /**
     * @param array $addressDetails it will update users details. ex. clients, vendors details 
     * @return JSON this method will returns the HTTP Response 
     */
    public function update($street,$postCode,$city,$countryId,UserInterface $user,$type)
    {
        try{
            // here address is a address object.
            $address =   $this->container->get('sylius.repository.address')->findOneBy(array('user'=>$user,'type'=>$type));
            // country object.
            if($countryId){
                $country = $this->container->get('sylius.repository.country')->findOneBy(array('id'=>$countryId));    
                $address->setCountry($country);
            }
            
            $address->setStreet(($street)?$street:$address->getStreet());
            $address->setPostcode(($postCode)?$postCode:$address->getPostCode());
            $address->setCity(($city)?$city:$address->getCity());
            
            $this->container->get('sylius.manager.address')->persist($address);
            $this->container->get('sylius.manager.address')->flush($address);
            return true;
        }  catch (\Exception $ex){
            throw new \Exception($ex->getMessage(), $ex->getCode());
        }
    }
    /**
     * @param array $postData list of all the fields parameters like street, postCode, country, user.
     * @access public
     * 
     */
    public function createAddress($postData){
        try{
                $validationAddress = $this->validationAddress($postData);
                $country = $this->container->get('sylius.repository.country')->findOneBy(array('id'=>$postData['countryId']));
                $user = $this->container->get('sylius.repository.user')->findOneBy(array('id'=>$postData['userId']));
                if(empty($user)){
                   throw new Exception('User Not found', 401); 
                }
                if($validationAddress){
                    $address = new Address();            
                    $address->setStreet($postData['street']);
                    $address->setPostcode($postData['postCode']);
                    $address->setCountry($country);
                    $address->setCity($postData['city']);
                    $address->setUser($user);                
                    $this->container->get('sylius.manager.address')->persist($address);
                    $this->container->get('sylius.manager.address')->flush($address);
                    
                    return $this->container->get('ibillmaker.hub.core.service.responseCreate')->create('200','address successfully added.');
                }
        } catch (\Exception $ex) {
          throw new \Exception($ex->getMessage(), $ex->getCode());
        }
    }
    
    public function validationAddress($postData){
        $validation = array(
                'street' => 'words',
                'postCode' => 'number',
                'countryId' => 'number',
                'city' => 'words',
                'userId' => 'number'
            );

            $required = array('street','postCode','countryId','userId','city');

            $validationRepository = $this->container->get('ibillmaker.hub.core.service.validation');
            $validationRepository->init($validation, $required);

            $result = $validationRepository->validate($postData);
            if ($result !== TRUE) {
                throw new \Exception($result, 400);
            }
            return true;
    }
    
    public function getCountryList(){
        $dbStorage = $this->container->get('doctrine.dbal.default_connection');
        $countryList = $dbStorage->fetchAll("select id , name from sylius_country");
        
        return json_encode($countryList);
    }

}
