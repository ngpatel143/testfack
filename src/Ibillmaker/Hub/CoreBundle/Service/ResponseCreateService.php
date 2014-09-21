<?php

namespace Ibillmaker\Hub\CoreBundle\Service;

use Symfony\Component\HttpFoundation\Response;

class ResponseCreateService {

    public function __construct($container) 
    {
        $this->container = $container;
    }

    // generete Response
    public function create($status_code, $status_message, $result = NULL, $jsonp = NULL) 
    {

        $responseArrayStatus = array(
            'code' => $status_code,
            'message' => $status_message
        );

        if ($result != NULL) {
            $responseArray = array_merge($responseArrayStatus, $result);
            $response = new Response(json_encode($responseArray));
        } else {
            $response = new Response(json_encode($responseArrayStatus));
        }
        
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/javascript');
        if (!empty($jsonp)) {
            $responseResult = ($jsonp) ? $response->setContent($this->container->get('service.jsonp')->convertToJsonp($jsonp, $response->getContent())) : $response;
            return $responseResult;
        } else {
            return $response;
        }
    }
}

?>