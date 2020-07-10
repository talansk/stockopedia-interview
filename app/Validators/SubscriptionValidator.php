<?php
namespace App\Validators;

use App\Http\Helper\Utility;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SubscriptionValidator extends BaseValidator
{
    /**
     * Will initial the Rules and Input Only instance Variables
     */
    public function __construct(){
        $this->inputOnly = [
            Subscription::$columns['planCode']          => Subscription::$columns['planCode'],
            Subscription::$columns['name']              => Subscription::$columns['name'],
            Subscription::$columns['monthlyCost']       => Subscription::$columns['monthlyCost'],
            Subscription::$columns['annualCost']        => Subscription::$columns['annualCost'],
            Subscription::$columns['flag']              => Subscription::$columns['flag'],
        ];
    }
    
    protected function inputOnly($requestName) {
        switch ($requestName) {
            case 'update' :
                return array_merge($this->inputOnly, ['id' => 'id']);
                
            default:
                return $this->inputOnly;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Validators\BaseValidator::rules()
     */
    protected function rules($requestName) {
        switch ($requestName) {
            case 'paginate' :
                return array_merge(parent::rules('paginate'),['sort_field' =>  "in:name,created_at,updated_at"]);
                
            case 'create':
                return $this->rules = [
                Subscription::$columns['planCode']      => "required|string|min:1|max:50",
                Subscription::$columns['name']          => "required|string|min:1|max:55",
                Subscription::$columns['monthlyCost']   => "required|integer|min:1",
                Subscription::$columns['annualCost']    => "required|required|integer|min:1",
                ];
                
            case 'update':
                return $this->rules = [
                Subscription::$columns['planCode']      => "sometimes|required|string|min:1|max:50",
                Subscription::$columns['name']          => "sometimes|required|string|min:1|max:55",
                Subscription::$columns['monthlyCost']   => "sometimes|required|integer|min:1",
                Subscription::$columns['annualCost']    => "sometimes|required|integer|min:1",
                ];
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Validators\BaseValidator::validate()
     */
    public function validate(array $requestParam, $requestName) {
        $rules      = $this->rules($requestName);
        $validator  = Validator::make($requestParam, $rules, $this->messages());
        if ($validator->fails()) {
            throw new BadRequestHttpException(json_encode(Utility::formatErrorValidations($validator->errors()->toArray())));
        }
    }
}

