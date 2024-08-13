<?php

namespace App\webhook;

class Servicos
{
    private $cnn;
    private $url;
    private $method;
    private $options;
    private $data;

    public function __construct($url, $method, $data)
    {
        $this->url = $url;
        $this->method = $method;
        $this->data = $data;

        $this->cnn = curl_init(); 
        $this->options = array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $this->data,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json') 
        );

        curl_setopt_array($this->cnn, $this->options);
    }

    public function sendMessage()
    {
        $response = curl_exec($this->cnn); 
        curl_close($this->cnn); 
        return $response;
    }
}
