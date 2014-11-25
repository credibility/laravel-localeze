<?php

namespace Credibility\LaravelLocaleze;

class LocalezeFilter {

    public $licenseID;
    public $phone;
    public $businessName;
    public $category;
    public $companyKey;
    public $firstRowIndex = 0;
    public $lastRowIndex = 1;
    public $zip;
    public $state;

    public function toXML()
    {
        $query = new \SimpleXMLElement('<BizStatusQuery/>');
        $query->addAttribute('Edition',"1.1");
        $parameters = $query->addChild('Parameters');
        $filter = $parameters->addChild('Filter');
        foreach($this as $key => $value){
            $filter->addChild(ucfirst($key),$value);
        }
        return $query->asXML();
    }
}