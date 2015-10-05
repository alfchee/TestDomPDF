<?php

trait PropGeneratorTrait 
{

    public $data = array();

    // set magicaly the data attributes
    public function __get($property) {
        $method = 'get' .  ucfirst($property); // for camelCase method name

        if(method_exists($this, $method)) {
            $reflection = new Reflection($this,$method);
            if(!$reflection->isPublic())
                throw new RuntimeException("The called method is not public.");
        }

        if(array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }
    }//__get()

    public function __set($property, $value) {
        $method = 'set' . ucfirst($property); // for camelCase method name

        if(method_exists($this,$method)) {
            $reflection = new Reflection($this,$method);
            if(!$reflection->isPublic()) {
                throw new RuntimeException('The called method is not public.');
            }
        }

        $this->data[$property] = $value;            
    }//__set()

}