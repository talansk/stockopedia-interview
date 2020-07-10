<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use App\Validators\BaseValidator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @OA\Server(url="http://localhost:8000/api/v1/subscription_service/")
 */

/**
 * @OA\Swagger(
 * 		host=API_HOST,
 * 		schemes={"http"},
 * 		produces={"application/json"},
 *      
 * 		@OA\Info(
 * 			title="Stockopedia Service API",
 * 			description="L5 Swagger OpenApi description",
 * 			version="1.0.0",
 * 			@OA\Contact(email="talansk@gmail.com"),
 * 		),
 *      @OA\Tag(name="Subscription", description="API Endpoints of Project"),
 *	)
 */

abstract class ApiController extends Controller
{
    /**
     * Object for interacting with Eloquent Model
     *
     * @var BaseRepository
     */
    protected $repository;
    
    /**
     * To filter and Validate the Request
     *
     * @var BaseValidator
     */
    protected $validator;
    
    /**
     * @var string
     */
    protected $modelName;
    
    
    /**
     * Initialize the Repository and Validator
     *
     * @param BaseRepository $repository
     * @param BaseValidator $validator
     */
    public function __construct(BaseRepository $repository, BaseValidator $validator) {
        $this->repository   =   $repository;
        $this->validator    =   $validator;
    }
    
    abstract public function resource();
    
    abstract public function getModelName();
    
    /**
     * Will delete the record for the given Id.
     *
     * @param integer $id
     * @throws BadRequestHttpException
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id) {
        if(!is_numeric($id)){
            throw new BadRequestHttpException(trans('errors.invalid_input'));
        }
        
        if(!$this->repository->delete($id)) {
            throw new \Exception(trans('errors.failed_to_delete_record'));
        }
        
        return $this->respond(['status' => true], Response::HTTP_NO_CONTENT);
    }
    
    /**
     * Will fetch the Result for the given Query String
     *
     * @param Request $request
     * @throws NotFoundHttpException
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getList(Request $request) {
        $requestInput   =   $request->all();
        $where          =   [];
        
        //Validate the Create Request
        $this->validator->validate($requestInput, 'paginate');
        //Filter the Unnecessary Fields from the Request Input
        $attributes = $this->validator->filters($requestInput, 'paginate');
        
        $limit          =   ($request->input('limit')  ?: 10);
        $orderBy        =   ($request->input('sort_field')   ?: 'updated_at');
        $sortOrder      =   ($request->input('sort_order')   ?: 'desc');
        
        foreach($attributes as $field => $value) {
            $where[$field] = $value;
        }
        
        $model          =   $this->repository->paginate($where, $limit, $orderBy, $sortOrder);
        
        return $this->transformCollection($model);
    }
    
    
    /**
     * Fetch the Object for the given Id.
     *
     * @param integer $id
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getById(Request $request, $id) {
        if(!is_numeric($id)) {
            throw new BadRequestHttpException(trans('errors.id_is_not_valid'));
        }
        
        $model = $this->repository->findById($id);
        
        if(is_null($model)) {
            throw new NotFoundHttpException(trans('errors.id_does_not_exist'));
        }
        
        return $this->transformResource($model);
    }
    
    /**
     * Will transform the Object to Collection
     *
     * @param   Collection      $collection
     * @return  \Illuminate\Support\Collection
     */
    public function transformResource($model) {
        $resource = $this->resource();
        
        return new $resource($model);
    }
    
    /**
     * Will transform the list to Collection
     *
     * @param   Collection      $collection
     * @return  \Illuminate\Support\Collection
     */
    public function transformCollection($collection) {
        $resource = $this->resource();
        
        return $resource::collection($collection);
    }
}