<?php
namespace Application\Model\Entity;

abstract class AbstractEntity
{
    public function __get($name) 
    {
        if(property_exists($this, $name)){
            return $this->$name;
        }
        
        throw \Exception('Property ' . $name . ' do not exist!');
    }
    
    public function __set($name, $value) 
    {
        if(property_exists($this, $name)){
            $this->$name = $value;
            return $this;
        }
        
        throw \Exception('Property ' . $name . ' do not exist!');
    }
    
    public function __call($method, $parameters)
    {
      $prefix = substr($method, 0, 3);
      
        if ($prefix == "get") {
            // Sanitizing so no functions should get through
            $property = preg_replace("/[^0-9a-zA-Z]/", "", lcfirst(substr($method, 3)));
            
            if(property_exists($this, $property)){
                return $this->{$property};
            }

            throw new \Exception('Property ' . $property . ' do not exist!');
        } else if ($prefix == 'set') {
            // Sanitizing so no functions should get through
            $property = preg_replace("/[^0-9a-zA-Z]/", "", lcfirst(substr($method, 3)));
            
            if(property_exists($this, $property)){
                $this->{$property} = $parameters[0];
                return $this;
            }

            throw new \Exception('Property ' . $property . ' do not exist!'); 
        } 
        
        throw new \Exception('Method ' . $method . ' do not exist!'); 
    }
}