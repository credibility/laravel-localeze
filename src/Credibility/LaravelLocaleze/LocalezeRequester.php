<?php

namespace Credibility\LaravelLocaleze;

use Illuminate\Foundation\Application;

class LocalezeRequester {

    /** @var Application  */
    public $app;

    /** @var \SoapClient  */
    public $soap;

    /** @var integer */
    public $serviceId;

    /**
     * @param Application $app
     * @param null $wsdl
     */
    public function __construct(Application $app, $wsdl = null)
    {
        $this->app = $app;
        $wsdl = $this->app->make('config')->get('laravel-localeze::wsdl');
        $this->soap = new \SoapClient($wsdl);
        $this->serviceId = $this->app->make('config')->get('laravel-localeze::serviceId');
    }

    public function process(LocalezeRequest $request)
    {
        $username = $this->app->make('config')->get('laravel-localeze::username');
        $password = $this->app->make('config')->get('laravel-localeze::password');
        $response = $this->soap->query([
            "origination" => $this->setupOrigination($username, $password),
            "transId" => 1,
            "serviceId" => $this->serviceId,
            "elements" => $request->elements,
            "serviceKeys" => $request->serviceKeys
        ]);

        return new LocalezeResponse($response);
    }

    /**
     * Setup the origination object for the SoapClient
     *
     * @param $username
     * @param $password
     * @return \stdClass
     */
    private function setupOrigination($username, $password)
    {
        $origination = new \stdClass();
        $origination->username = $username;
        $origination->password = $password;
        return $origination;
    }
}