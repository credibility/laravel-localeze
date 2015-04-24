<?php

namespace Credibility\LaravelLocaleze;

class LocalezeResponse
{
    /** @var array  */
    public $data = [];

    /**
     * DEPRECATED
     * @var array
     */
    public $response = [];

    /** @var integer */
    public $errorCode;

    /**
     * @param $response
     */
    public function __construct($response)
    {
        $xml = new \SimpleXMLElement($response->response->result->element->value);
        $this->data = $this->xmlToArray($xml);
        $this->errorCode = $this->getErrorCode($this->data);

        // Setting value to deprecated property
        $this->response = $this->data;
    }

    /**
     * Determine if the response is a successful response
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->errorCode !== 0;
    }

    /**
     * Get error code from data array
     *
     * The error message is inconsistent depending on the request being made. So we check for the existence of
     * 'ErrorCode' or 'Error' to pull the error code.
     *
     * @param $data
     * @return null
     */
    private function getErrorCode($data)
    {
        $errorCode = null;

        if(isset($data['ErrorCode'])) {
            $errorCode = $data['ErrorCode'];
        } else if(isset($data['Error'])) {
            $errorCode = $data['Error'];
        }

        return $errorCode;
    }

    /**
     * Converts XML to array
     *
     * @param $xml
     * @return mixed
     */
    private function xmlToArray($xml)
    {
        return json_decode(json_encode($xml),true);
    }
}