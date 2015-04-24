<?php

namespace Credibility\LaravelLocaleze;

class LocalezeRequest {

    public $serviceKeys = [];

    public $elements = [];

    public function __construct(array $elements)
    {
        $this->elements = $elements;
    }


    public function addServiceKey($id, $value)
    {
        $this->serviceKeys[] = ["id" => $id, "value" => $value];
    }
}