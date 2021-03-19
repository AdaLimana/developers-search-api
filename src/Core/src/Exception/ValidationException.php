<?php

namespace Core\Exception;

/**
 * Description of ValidationException
 *
 * @author adair
 */
class ValidationException extends \Exception
{
    public function __construct(array $message = [], int $code = 400, \Throwable $previous = NULL)
    {
        parent::__construct(json_encode($this->formatMessages($message)), $code, $previous);
    }
    
    /**
     * Mantem da mensagem somente as strings que contem
     * a mensagem, ou seja, remove as chaves que disem respoito
     * tipo de erro e ao campo     * 
     */
    private function formatMessages(array $message) {
        
        $newMessage = [];
        
        if(is_array($message)){
            $this->iteraArrayMessages($message, $newMessage);
        }

        return $newMessage;
    }
    
    private function iteraArrayMessages($array, array &$newMessage){
        
        foreach ($array as $value){
            
            if(is_array($value)){
                $this->iteraArrayMessages($value, $newMessage);
            }
            else{
                $newMessage[]= $value;
            }
        }
    }
    
}