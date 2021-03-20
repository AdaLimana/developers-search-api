<?php

namespace Core\PHPUnit;

use Core\Entity\Entity;
use Laminas\InputFilter\InputFilter;
use PHPUnit\Framework\TestCase;

/**
 * Description of TestCaseService
 *
 * @author adair
 */
class TestCaseService extends TestCase
{
       
    protected function assertUpdatedEntity(Entity $entity, array $data)
    {
        
        foreach ($data as $attribute => $value) {
            
            $method = "get" . ucfirst($attribute);
            
            if(method_exists($entity, $method)){
                
                $value_of_entity = $entity->$method();
            
                if($value_of_entity instanceof \DateTime){
                
                    if(!($value instanceof \DateTime) && is_string($value)){
                        $value = new \DateTime($value);
                    }
                
                    $this->assertEquals($value, $value_of_entity);
                }
                else if(is_string($value) || is_numeric($value)){
                    
                    $this->assertEqualsIgnoringCase($value, $value_of_entity);
                }
            }    
        }   
    }

    protected function generateRandomString(int $length, $withoutSpace = false)
    {
        if($withoutSpace){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        else{
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ';
        }
        $charactersLength = strlen($characters);
        
        $length = $length-2;
        $randomString = 'a';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString . 'z';
    }
    
}