<?php
namespace App\Validators;

use App\Http\Helper\Utility;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BaseValidator
{
    /**
     * Rules to validate the Request Object
     *
     * @var array
     */
    protected $rules = [];
    
    /**
     * To filter the unnecessary fields from the Request
     *
     * @var array
     */
    protected $inputOnly = ['*' => '*'];
    
    /**
     * Will return the Rules based on Request Name
     *
     * @param string $requestName
     */
    protected function rules($requestName) {
        switch ($requestName) {
            case 'paginate' :
                return [
                'page'          =>  'integer|min:1,max:99999',
                'limit'         =>  'integer|min:1,max:100',
                'sort_order'    =>  'string|in:asc,desc'
                    ];
            default:
                return $this->rules;
        }
    }
    
    /**
     * Will return the Fields to be filtered based on Request Name
     *
     * @param string $requestName
     */
    protected function inputOnly($requestName) {
        switch ($requestName) {
            case 'paginate' :
                return [
                'page'          =>  'page',
                'limit'         =>  'limit',
                'sort_field'    =>  'sort_field',
                'sort_order'    =>  'sort_order'
                    ];
            case 'update' :
                return array_merge($this->inputOnly, ['id' => 'id']);
            default:
                return $this->inputOnly;
        }
    }
    
    /**
     * Will return the Message for Rules.
     *
     * @param string $requestName
     */
    protected function messages() {
        return [
            'required'          =>  trans('validations.required'),
            'unique'            =>  trans('validations.unique'),
            'in'                =>  'The :attribute must be one of the following types: :values',
            'max'               =>  trans('validations.max'),
            'numeric'           =>  trans('validations.numeric'),
            'integer'           =>  trans('validations.integer'),
            'string'            =>  trans('validations.string'),
            'end_time.after'    =>  trans('validations.end_time_after'),
            'json'              =>  trans('validations.json'),
        ];
    }
    
    /**
     * Filtered the unnecessary fields from the Request
     *
     * @param array $requestParam
     * @param string $requestName
     * @return array
     */
    public function filters(array $requestParam, $requestName) {
        $filteredList   =   array();
        $filters        =   $this->inputOnly($requestName);
        foreach($requestParam as $attributeName => $attributeValue) {
            if(in_array($attributeName, $filters)) {
                $filteredList[$attributeName] = $attributeValue;
            }
        }
        
        return $filteredList;
    }
    
    /**
     * Validate the Request Parameters
     *
     * @param array $requestParam
     * @param string $requestName
     * @throws BadRequestHttpException
     */
    public function validate(array $requestParam, $requestName) {
        $validator  =   Validator::make($requestParam, $this->rules($requestName), $this->messages());
        
        if ($validator->fails()) {
            throw new BadRequestHttpException(json_encode(Utility::formatErrorValidations($validator->errors()->toArray())));
        }
    }
}

