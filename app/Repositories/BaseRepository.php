<?php
namespace App\Repositories;

use Illuminate\Container\Container as Application;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class BaseRepository
{
    /**
     * @var Application
     */
    protected $app;
    
    /**
     * @var Model
     */
    protected $model;
    
    /**
     * @var Model
     */
    protected $query;
    
    /**
     * @var String
     */
    protected $modelName;
    
    /**
     * Initialize the Instance Variable
     */
    public function __construct(Application $app) {
        $this->app          =   $app;
        $this->model        =   $this->makeModel();
        $this->query        =   $this->model;
    }
    
    /**
     * @return Model
     */
    public function makeModel() {
        $this->model = $this->app->make($this->model());
        
        //Get Model Name
        $modelReflection = new \ReflectionClass($this->model);
        $this->modelName = strtolower($modelReflection->getShortName());
        return $this->model;
    }
    
    /**
     * Specify Models class name
     *
     * @return string
     */
    abstract public function model();
    
    /**
     * Retrieve data array for populate field select
     *
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function lists() {
        
    }
    
    /**
     * Retrieve data array for populate field select
     *
     * @param string $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function pluck($column, $key = null) {
        
    }
    
    /**
     * Retrieve all data of repository
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*']) {
        return $this->model->all();
    }
    
    /**
     * Will fetch the model based on Id
     *
     * @param   integer $id
     * @param array $columns
     *
     * @throws  NotFoundHttpException
     * @return  \Illuminate\Http\JsonResponse
     */
    public function findById($id, $columns = ['*']) {
        $model = $this->model->find($id);
        
        if(is_null($model)) {
            return NULL;
        }
        
        return $model;
    }
    
    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate(array $where, $limit = null, $orderBy = 'updated_at', $sortOrder = 'desc',  $columns = ['*']) {
        $this->model->select($columns);
        
        $model = $this->model->where($where)->orderBy($orderBy, $sortOrder)->paginate($limit);
        
        return $model;
    }
    
    /**
     * Save a new entity in repository
     *
     * @param array $requestParams
     *
     * @return Collection
     */
    public function create(array $attributes) {
        return $this->model->create($attributes)->fresh();
    }
    
    /**
     * Update a entity in repository by id
     *
     * @param int $id
     * @param array $attributes
     * @return Collection
     */
    public function update(int $id, array $attributes) {
        $model = $this->model->find($id);
        
        if(is_null($model)) {
            throw new NotFoundHttpException(trans('errors.ID_DOESNT_EXIST'));
        }
        
        $model->update($attributes);
        return $model;
    }
    
    
    /**
     * Delete a entity in repository by id
     *
     * @param integer $id
     * @throws NotFoundHttpException
     * @return boolean
     */
    public function delete($id) {
        $model = $this->model->find($id);
        
        if(is_null($model)) {
            throw new NotFoundHttpException(trans('errors.ID_DOESNT_EXIST'));
        }
        
        return $model->delete();
    }
    
    /**
     * Reset Query Scope
     */
    public function resetScope() {
        $this->model = $this->query;
    }
    
    /**
     * Will check duplicate subscription entry by plan code
     *
     * @param array $attributes
     * @param array $columns
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function isSubscriptionExist($attributes)
    {
    }
    
    /**
     * Will check duplicate match
     *
     * @param array $attributes
     * @param array $columns
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function duplicateMatch($attributes)
    {
    }
    
    /**
     * Will check duplicate match played by player
     *
     * @param array $attributes
     * @param array $columns
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function duplicatePlayerMatch($attributes)
    {
    }
    
    /**
     * Will check duplicate team and match
     *
     * @param array $attributes
     * @param array $columns
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function duplicateTeamMatch($attributes)
    {
    }
    
    /**
     * Will Fetch result for the following Search Filters
     *
     * @param   Request $request
     * @throws  BadRequestHttpException
     * @return  \Illuminate\Http\JsonResponse
     */
    public function findWhere(array $where, $order_by = 'created_at', $columns = ['*']) {
        $this->model->select($columns);
        $this->applyConditions($where);
        $this->model->orderBy($order_by, 'desc');
        
        $model = $this->model->get();
        
        return $model;
    }
    
    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                $this->model = $this->model->whereIn($field, $value);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }
}

