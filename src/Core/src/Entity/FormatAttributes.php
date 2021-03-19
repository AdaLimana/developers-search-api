<?php

namespace Core\Entity;

/**
 * Description of FormatAttributes
 *
 * @author adair
 */
class FormatAttributes {
    
    /**
     * Formata para string todos so valores que sao
     * do tipo \DateTime de um dado array
     * 
     * @param array $entity
     */
    public static function formatDate(array &$entity)
    {
        
        foreach ($entity  as &$attribute_value){

            if($attribute_value instanceof \DateTime){
                $attribute_value = $attribute_value->format('c');
            }
            else if(is_array($attribute_value)){
                self::formatDate($attribute_value);
            }
        }
    }
}