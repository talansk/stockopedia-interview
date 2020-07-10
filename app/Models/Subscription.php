<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subscriptions';
    
    /**
     * These fields are mass assignable
     *
     * @var array
     */
    protected $fillable     = ["plan_code", "name", "monthly_cost", "annual_cost"];
    
    /**
     * For converting date fields to Carbon Object
     *
     * @var array
     */
    protected $dates        =   ['created_at', 'updated_at'];
    
    /**
     * For typecasting the Model Attributes
     *
     * @var array
     */
    protected $cast         = [
        'plan_code'         => 'string',
        'name'              => 'stribg',
        'monthly_cost'      => 'integer',
        'annual_cost'       => 'integer',
        'flag'              => 'integer'
    ];
    
    /**
     * Column Mapping that will be used throughout Application
     *
     * @var array
     */
    public static $columns  = [
        'planCode'          => 'plan_code',
        'name'              => 'name',
        'monthlyCost'       => 'monthly_cost',
        'annualCost'        => 'annual_cost',
        'flag'              => 'flag',
        'createdAt'         => 'created_at',
        'updatedAt'         => 'updated_at',
    ];
}
