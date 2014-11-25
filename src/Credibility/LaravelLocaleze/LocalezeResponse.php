<?php

namespace Credibility\LaravelLocaleze;

class LocalezeResponse {

    protected $response;
    protected $errorCode;

    /**
     * @param $response
     */
    public function __construct($response)
    {
        $xml = new \SimpleXMLElement($response->response->result->element->value);
        $this->response = json_decode(json_encode($xml),true);

        //The error message location is inconsistent across the API
        if(isset($this->response['ErrorCode'])) {
            $this->errorCode = $this->response['ErrorCode'];
        } elseif(isset($this->response['Error'])) {
            $this->errorCode = $this->response['Error'];
        }

    }



}