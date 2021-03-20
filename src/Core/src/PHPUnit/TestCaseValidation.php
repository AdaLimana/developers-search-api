<?php

namespace Core\PHPUnit;

use Laminas\InputFilter\InputFilter;
use PHPUnit\Framework\TestCase;

/**
 * Description of TestCaseValidation
 *
 * @author adair
 */
abstract class TestCaseValidation extends TestCase
{
    
    /**
     * return InputFilter
     */
    abstract protected function getValidation(): InputFilter;

    /**
     * @param array $data
     * @param array $errors
     */
    protected function informingInvalidData(array $data, array $errors, bool $debug = false)
    {
        
        $validation = $this->getValidation();
        
        $validation->setData($data);
        
        $this->assertFalse($validation->isValid());
        
        if($debug){
            var_dump($validation->getMessages());
        }
                
        foreach ($errors as $field => $errorsType){
            
            $this->assertArrayHasKey($field, $validation->getMessages());
            
            foreach ($errorsType as $errorType) {
                
                $this->assertArrayHasKey($errorType, $validation->getMessages()[$field]);
            }            
        }
        
        $this->assertCount(count($errors), $validation->getMessages());
    }
    
    /**
     * 
     * @param array $data
     */
    protected function informingValidData(array $data, bool $debug = false)
    {   
        $validation = $this->getValidation();
        
        $validation->setData($data);
   
        $validation->isValid();
   
        if($debug){
            var_dump($validation->getMessages());
        }
        
        $this->assertTrue($validation->isValid());
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