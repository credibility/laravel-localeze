<?php

namespace Credibility\LaravelLocaleze;

class LocalezeBusiness {

    public $phone;
    public $businessName;
    public $department;
    public $address;
    public $city;
    public $state;
    public $zip;
    public $plus4;
    public $fax;
    public $tollFreeNumber;
    public $altNumber;
    public $mobileNumber;
    public $unstructuredTerms; //comma separated values
    public $creditCards; //sum of all accepted values
    public $URL;
    public $email;
    public $yearOpenned;
    public $hoursOfOperation;
    public $languagesSpoken; //concat each type

    public $tagLine;
    public $logoImage;
    public $sharedKey;
    public $facebook;
    public $linkedIn;
    public $twitter;
    public $googlePlus;
    public $yelp;
    public $foursquare;

    public $categories = [];

    public function toXML()
    {
        $BPMSPost = new \SimpleXMLElement('<BPMSPost/>');
        $BPMSPost->addAttribute("Edition","1.1");
        $record = $BPMSPost->addChild('Record');
        foreach($this as $key => $value){
            if($key != "categories"){
                $record->addChild(ucfirst($key),$value);
            } else {
                $categories = $record->addChild("Categories");
                $count = 0;
                foreach($this->categories as $catValue){
                    $category = $categories->addChild("Category");
                    if($count == 0){
                        $category->addChild("Type","Primary");
                        $count++;
                    } else {
                        $category->addChild("Type","Alt".$count++);
                    }
                    $category->addChild("Name",$catValue);
                }
            }
        }
        return $BPMSPost->asXML();
    }

    //change to add attribute ID, attribute name, cat name, and cat id
    public function addCategory($category)
    {
        $this->categories[] = $category;
    }

}