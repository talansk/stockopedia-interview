<?php
namespace App\Http\Helper;

/**
 *
 * @author sachintalan
 *
 */
class Utility
{
    /**
     * Format the attribute name to User Friendly
     *
     * @param array $errorValidations
     * @return array
     */
    public static function formatErrorValidations($errorValidations) {
        $formattedErrorValidations  =   [];
        foreach($errorValidations as $errorValidationName => $errorMessages) {
            $errorValidationNameKey         =   str_replace('_', ' ', $errorValidationName);
            $formattedErrorValidationName   =   ucwords(str_replace('_', ' ', $errorValidationName));
            
            foreach($errorMessages as $errorMessage) {
                $formattedErrorValidations[$errorValidationName][] = str_replace($errorValidationNameKey, $formattedErrorValidationName, $errorMessage);
            }
            
        }
        
        return $formattedErrorValidations;
    }
}

