<?php

namespace Core\Entity;

/**
 * Description of Entity
 *
 * @author adair
 */
class Entity {
    
    public function __set($key, $value) {
        
        $method = "set" . ucfirst($key);
            
        if(method_exists($this, $method) && is_callable([$this, $method])){
            $this->$method($value);
        }
    }

    public function setData($data){
        
        foreach ($data as $key => $value){
            
            $this->__set($key, $value);
        }
    }
}