<?php

namespace App\Http\Resources;

use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//         return parent::toArray($request);
        return [
            'id'            => $this->id,
            'planCode'      => $this->{Subscription::$columns['planCode']},
            'name'          => $this->{Subscription::$columns['name']},
            'monthlyCost'   => $this->{Subscription::$columns['monthlyCost']},
            'annualCost'    => $this->{Subscription::$columns['annualCost']},
            'flag'          => $this->{Subscription::$columns['flag']},
            'createdAt'     => $this->{Subscription::$columns['createdAt']},
            'updatedAt'     => $this->{Subscription::$columns['updatedAt']},
            ];
    }
}
