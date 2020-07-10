<?php
namespace App\Repositories;

use App\Models\Subscription;
use phpDocumentor\Reflection\Types\Null_;

class SubscriptionRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Subscription';
    }

    /**
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::update()
     */
    public function create(array $attributes) {
        return $this->model->create($attributes)->fresh();
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::update()
     */
    public function update(int $id, array $requestParams) {
        return parent::update($id, $requestParams)->fresh();
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Repositories\BaseRepository::isSubscriptionExist()
     */
    public function isSubscriptionExist($attributes)
    {
        $response = false;
        if(!empty($attributes['id'])){
            $record = $this->findById($attributes['id']);
            $response = $this->execute($attributes['plan_code'], $attributes['id']);
        }else{
            $response = $this->execute($attributes['plan_code']);
        }
        
        return $response;
    }
    
    /**
     * Validate condition based on jerseynumber and country
     */
    private function execute($planCode, $attributeId = null){
        $where = [];
        $response = true;
        
        $where[Subscription::$columns['planCode']] = $planCode;
        $model = $this->findWhere($where)->get(0);

        if(is_null($model)){
            $response = false;
        }else if(!is_null($attributeId) && $planCode==$model->plan_code && $attributeId==$model->id){
            $response = false;
        }
        
        $this->resetScope();
        
        return $response;
    }
}

