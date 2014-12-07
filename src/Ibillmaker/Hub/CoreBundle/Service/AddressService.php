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
    * @access public
     * @param string $street address streat
     * @param int $postCode address post code
     * @param string $city  city of address
     * @param int $countryId country list id
     * @param Object $user User class object. address for which client
     * @param string $type type of address pri,sec or null.
     * @return true or exception 
     */
    public function createAddress($street,$postCode,$city,$countryId,UserInterface $user,$type){
        try{
                $validationAddress = $this->validationAddress($street,$postCode,$city,$countryId,$type);
                $country = $this->container->get('sylius.repository.country')->findOneBy(array('id'=>$countryId));
                if(empty($user)){
                   throw new \Exception('User Not found', 401); 
                }
                
                    $address = new Address();            
                    $address->setStreet($street);
                    $address->setPostcode($postCode);
                    $address->setCountry($country);
                    $address->setCity($city);
                    $address->setUser($user);                
                    $address->setType($type);                
                    $this->container->get('sylius.manager.address')->persist($address);
                    $this->container->get('sylius.manager.address')->flush($address);
                    
                    return $this->container->get('ibillmaker.hub.core.service.responseCreate')->create('200','address successfully added.');
                
        } catch (\Exception $ex) {
          throw new \Exception($ex->getMessage(), $ex->getCode());
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
        $typePre = ($type == 'pri')?'p_':'s_';
        if($address){
          $result[$typePre.'street'] = $address->getStreet();
          $result[$typePre.'postcode'] = $address->getPostCode();
          $result[$typePre.'city'] = $address->getCity();
          $result[$typePre.'country'] = $address->getCountry()->getId();
          $country = $this->container->get('sylius.repository.country')->findOneBy(array('id'=>$address->getCountry()->getId()));    
          $result[$typePre.'country_name'] =  $country->getName();
          return $result;
        }else{
            //throw new \Exception('Page Not Found', 404);
        }
      
    }
    
    /**
     * @param string $street address streat
     * @param int $postCode address post code
     * @param string $city  city of address
     * @param int $countryId country list id
     * @param Object $user User class object. address for which client
     * @param string $type type of address pri,sec or null.
     * @return JSON this method will returns the HTTP Response 
     */
    public function updateAddress($street,$postCode,$city,$countryId,UserInterface $user,$type)
    {
        try{
           
           //$validationAddress = $this->validationAddress($street,$postCode,$city,$countryId,$type);
            // here address is a address object.
            $address =   $this->container->get('sylius.repository.address')->findOneBy(array('user'=>$user,'type'=>$type));
            if(empty($address)){
               return $this->createAddress($street, $postCode, $city, $countryId, $user, $type);
            }
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
    
   
    
    public function validationAddress($street,$postCode,$city,$countryId,$type){
        $postData['street'] = $street;
        $postData['postCode'] = $postCode;
        $postData['city'] = $city;
        $postData['countryId'] =$countryId;
        $postData['type'] = $type;
        
        $validation = array(
                'street' => 'words',
                'postCode' => 'number',
                'countryId' => 'number',
                'city' => 'words',
                'type' => 'words'
            );

            $required = array('type');
            $validationRepository = $this->container->get('ibillmaker.hub.core.service.validation');
            $validationRepository->init($validation);

            $result = $validationRepository->validate($postData);
            if ($result !== TRUE) {
                throw new \Exception($result, 400);
            }
            return true;
    }
    
    public function getCountryList(){
        $dbStorage = $this->container->get('doctrine.dbal.default_connection');
        $countryList = $dbStorage->fetchAll("select id , name from sylius_country");
        
        return $countryList;
    }

}
