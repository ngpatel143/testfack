<?php
namespace Ibillmaker\Hub\CoreBundle\Service;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * How to use ValidationService?
 *  //  this is all the validation fields.
 *   $validation = array(
            'quantity' => 'id',
            'userId' => 'id',
            'currency' => 'words',
            'promotionCode' => 'alfanum',
            'orderTotal' => 'price',
            'variantSku' => 'alfanum',
            'sessionId' => 'alfanum'
        );
 *  //  Mandatories fields 
        $required = array('quantity', 'userId', 'currency', 'orderTotal', 'sessionId', 'variantSku');

        $validationRepository = $this->container->get('dacast.api.validation');
        $validationRepository->init($validation, $required);

        $result = $validationRepository->validate($postData);
 *      // it is  return's true, If Request the pass the test.
        if ($result !== TRUE) {
            throw new \Exception($result, 400);
        }
 *
 * @author ngpatel
 * @
 */
class ValidationService {

    public static $regexes = Array(
        'date' => "^[0-9]{4}[-/][0-9]{1,2}[-/][0-9]{1,2}\$",
        'amount' => "^[-]?[0-9]+\$",
        'number' => "^[-]?[0-9,]+\$",
        'alfanum' => "^[0-9a-zA-Z ,.-_\\s\?\!]+\$",
        'not_empty' => "[a-z0-9A-Z]+",
        'words' => "^[A-Za-z]+[A-Za-z \\s]*\$",
        'phone' => "^[0-9]{10,11}\$",
        'zipcode' => "^[1-9][0-9]{3}[a-zA-Z]{2}\$",
        'plate' => "^([0-9a-zA-Z]{2}[-]){2}[0-9a-zA-Z]{2}\$",
        'price' => "^[0-9.,]*(([.,][-])|([.,][0-9]{2}))?\$",
        '2digitopt' => "^\d+(\,\d{2})?\$",
        '2digitforce' => "^\d+\,\d\d\$",
        'anything' => "^[\d\D]{1,}\$",
        'id' => "^[1-9][0-9]*$",
        'datetime' => "^(((\d{4})(-)(0[13578]|10|12)(-)(0[1-9]|[12][0-9]|3[01]))|((\d{4})(-)(0[469]|1‌​1)(-)([0][1-9]|[12][0-9]|30))|((\d{4})(-)(02)(-)(0[1-9]|1[0-9]|2[0-8]))|(([02468]‌​[048]00)(-)(02)(-)(29))|(([13579][26]00)(-)(02)(-)(29))|(([0-9][0-9][0][48])(-)(0‌​2)(-)(29))|(([0-9][0-9][2468][048])(-)(02)(-)(29))|(([0-9][0-9][13579][26])(-)(02‌​)(-)(29)))(\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9]))$",
        'creditCard' => "^([0-9]{4})[-|s]*([0-9]{4})[-|s]*([0-9]{4})[-|s]*([0-9]{2,4})$",
        'numchar'=> "^(?:\d+[a-z]|[a-z]+\d)[a-z\d]*$",
        'everything'  => ".*"
    );
    private $validations, $sanatations, $mandatories, $errors, $corrects, $fields;

    
    public function init($validations = array(), $mandatories = array(), $sanatations = array()) {
        $this->validations = $validations;
        $this->sanatations = $sanatations;
        $this->mandatories = $mandatories;
        $this->errors = array();
        $this->corrects = array();
    }
    /**
     * Validates an array of items (if needed) and returns true or false
     *
     */
    public function validate($items) {
        
        // check string  = '"ù*;:&é"'(-_èru or !empty() 
        foreach ($this->validations as $key => $value)
        {   
            if(!ctype_print($this->validations[$key]) && !empty($this->validations[$key]))
            {
                $message = 'Invalid value for ' .$key;
                throw new \Exception($message, 400);
            }
        }

        $missing = \array_diff($this->mandatories, \array_keys($items));
        if (!empty($missing)) {
            $message = 'Invalid value for ' . implode(' , ', $missing);
            throw new \Exception($message, 400);
        }
        
        $this->fields = $items;
        $havefailures = false;
        foreach ($items as $key => $val) {
            if ((strlen($val) == 0 || array_search($key, $this->validations) === false) && array_search($key, $this->mandatories) === false) {
                $this->corrects[] = $key;
                continue;
            }
            try{
                 $result = self::validateItem($val, $this->validations[$key]);
            } catch (\Exception $ex) {
              
            }
           
            
            if ($result === false) {
                $this->addError($key, $this->validations[$key]);
             
            } else {
                $this->corrects[] = $key;
            }
        }

         if (!empty($this->errors)) {
             return $response = 'Invalid  values for ' . implode(' ,  &nbsp;',array_keys($this->errors));
             throw new \Exception($response, 400);
         }
         return true;
    }

    /**
     *
     * Sanatizes an array of items according to the $this->sanatations
     * sanatations will be standard of type string, but can also be specified.
     * For ease of use, this syntax is accepted:
     * $sanatations = array('fieldname', 'otherfieldname'=>'float');
     */
    public function sanatize($items) {
        foreach ($items as $key => $val) {
            if (array_search($key, $this->sanatations) === false && !array_key_exists($key, $this->sanatations))
                continue;
            $items[$key] = self::sanatizeItem($val, $this->validations[$key]);
        }
        return($items);
    }

    /**
     *
     * Adds an error to the errors array.
     */
    private function addError($field, $type = 'string') {
        $this->errors[$field] = $type;
    }

    /**
     *
     * Sanatize a single var according to $type.
     * Allows for static calling to allow simple sanatization
     */
    public static function sanatizeItem($var, $type) {
        $flags = NULL;
        switch ($type) {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
                break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
                break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
                break;
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
                break;
            case 'string':
            default:
                $filter = FILTER_SANITIZE_STRING;
                $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
                break;
        }
        $output = filter_var($var, $filter, $flags);
        return($output);
    }

    /**
     *
     * Validates a single var according to $type.
     * Allows for static calling to allow simple validation.
     *
     */
    public static function validateItem($var, $type) {
        if (array_key_exists($type, self::$regexes)) {
            $returnval = filter_var($var, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => '!' . self::$regexes[$type] . '!i'))) !== false;
            return($returnval);
        }
        $filter = false;
        switch ($type) {
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_VALIDATE_EMAIL;
                break;
            case 'int':
                $filter = FILTER_VALIDATE_INT;
                break;
            case 'boolean':
                $filter = FILTER_VALIDATE_BOOLEAN;
                break;
            case 'ip':
                $filter = FILTER_VALIDATE_IP;
                break;
            case 'url':
                $filter = FILTER_VALIDATE_URL;
                break;
        }
        return ($filter === false) ? false : filter_var($var, $filter) !== false ? true : false;
    }

}
