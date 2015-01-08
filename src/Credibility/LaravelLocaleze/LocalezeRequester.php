<?php

namespace Credibility\LaravelLocaleze;

use Illuminate\Foundation\Application;

class LocalezeRequester {

    public $app;
    public $soap;

    /**
     * @param Application $app
     * @param null $wsdl
     */
    public function __construct(Application $app, $wsdl = null)
    {
        //move from class constant to package constant
        $this->app = $app;
        $wsdl = $wsdl ?: $this->app->make('config')->get('laravel-localeze::wsdl');
        $this->soap = new \SoapClient($wsdl);
    }

    /**
     * @param array $serviceKeys
     * @param array $elements
     * @return mixed
     */
    public function run(Array $serviceKeys, Array $elements)
    {
        $origination = $this->getOrigination();
        $setup = array(
            "origination" => $origination,
            "transId" => 1,
            "serviceId" => "CustomerServiceID",
            "elements" => $elements,
            "serviceKeys" => $serviceKeys
        );

        return $this->soap->query($setup);
    }

    /**
     * @return \stdClass
     */
    public function getOrigination()
    {
        $origination = new \stdClass();
        $origination->username = $this->app->make('config')->get('laravel-localeze::username');
        $origination->password = $this->app->make('config')->get('laravel-localeze::password');
        return $origination;
    }
}