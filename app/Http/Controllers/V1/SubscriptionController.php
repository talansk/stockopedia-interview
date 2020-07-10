<?php

namespace App\Http\Controllers\V1;


use App\Repositories\SubscriptionRepository;
use App\Validators\SubscriptionValidator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @OA\Get(
 *     path="/subscriptions",
 *     operationId="getList",
 *     tags={"Subscription"},
 *     summary="Get list of subscriptions",
 *     description="Return a List of subscriptions",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\Schema(ref="#/components/schemas/SubscriptionResource")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 * )
 */

/**
 * @OA\Get(
 *     path="/subscriptions/{subscriptionId}",
 *     operationId="getById",
 *     tags={"Subscription"},
 *     summary="Get subscription by id",
 *     description="Return a subscription",
 *     @OA\Parameter(
 *         name="subscriptionId",
 *         required=true, 
 *         in="path",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\Schema(ref="#/components/schemas/SubscriptionResource")
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found"
 *     ),
 * )
 */

/**
 * @OA\Post(
 *      path="/subscriptions",
 *      operationId="create",
 *      tags={"Subscription"},
 *      summary="Create new subscription",
 *      description="Create new subscription",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/SubscriptionRequest")
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Successful operation",
 *          @OA\Schema(ref="#/components/schemas/SubscriptionResource")
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 * )
 */

/**
 * @OA\Patch(
 *      path="/subscriptions/{subscriptionId}",
 *      operationId="update",
 *      tags={"Subscription"},
 *      summary="Update subscription",
 *      description="Update subscription and return data",
 *      @OA\Parameter(
 *         name="subscriptionId",
 *         required=true, 
 *         in="path",
 *         @OA\Schema(type="integer")
 *     ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/SubscriptionRequest")
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Successful operation",
 *          @OA\Schema(ref="#/components/schemas/SubscriptionResource")
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 * )
 */

/**
 * @OA\Delete(
 *      path="/subscriptions/{subscriptionId}",
 *      operationId="delete",
 *      tags={"Subscription"},
 *      summary="Delete subscription",
 *      description="Delete subscription",
 *      @OA\Parameter(
 *         name="subscriptionId",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer")
 *     ),
 *      @OA\Response(
 *          response=204,
 *          description="Successful operation",
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 * )
 */

/**
 * @OA\Schema(
 *      schema="SubscriptionRequest",
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="plan_code", type="string"),
 *      @OA\Property(property="monthly_cost", type="integer"),
 *      @OA\Property(property="annual_cost", type="integer")
 * )
 */


class SubscriptionController extends ApiController
{
    /**
     * 
     * @param SubscriptionRepository $repository
     * @param SubscriptionValidator $validator
     */
    public function __construct(SubscriptionRepository $repository, SubscriptionValidator $validator){
        $this->repository   =   $repository;
        $this->validator    =   $validator;
    }
    
    /**
     * {@inheritDoc}
     * @see \App\Http\Controllers\V1\ApiController::resource()
     */
    public function resource()
    {
        return 'App\Http\Resources\SubscriptionResource';
    }

    /**
     * 
     * {@inheritDoc}
     * @see \App\Http\Controllers\V1\ApiController::getModelName()
     */
    public function getModelName()
    {
        return 'Subscription';
    }
    
    /**
     * Will create the Record for the given input.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request) {
        $requestInput   =   $request->json()->all();

        //Filter the Unnecessary Fields from the Request Input
        $attributes = $this->validator->filters($requestInput, 'create');
        //Validate the Create Request
        $this->validator->validate($attributes, 'create');
        
        //Validate duplicate subscription
        if($this->repository->isSubscriptionExist($attributes)){
            throw new BadRequestHttpException(trans('errors.duplicate_subscription'));
        }else{
            try {
                //Will Perform the DB Transaction
                DB::beginTransaction();
                $model = $this->repository->create($attributes);
                DB::commit();
            } catch(BadRequestHttpException $e) {
                DB::rollback();
                throw new BadRequestHttpException($e->getMessage());
            } catch (QueryException $e) {
                DB::rollback();
                throw new \Exception(trans('errors.failed_to_create_record'));
            } catch(\Exception $e) {
                DB::rollback();
                throw new \Exception(trans('errors.failed_to_create_record'));
            }
        }
        
        return  $this->transformResource($model)->response()->setStatusCode(201);
    }
    
    /**
     * Will update the Record for the given Id.
     *
     * @param Request $request
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id) {
        if(!is_numeric($id)){
            throw new BadRequestHttpException(trans('errors.invalid_input'));
        }
        
        $requestInput           =   $request->json()->all();
        
        if(!$requestInput){
            throw new NotFoundHttpException(trans('errors.invalid_input'));
        }
        
        if($this->repository->findById($id) == NULL) {
            throw new NotFoundHttpException(trans('errors.id_does_not_exist'));
        }
        
        //Filter the Unnecessary Fields from the Request Input
        $attributes = $this->validator->filters(array_merge($requestInput, ['id' => $id]), 'update');
        
        if(count($attributes)<=1){
            return $this->transformResource($this->repository->findById($id));
        }
                
        //Validate the Update Request
        $this->validator->validate($attributes, 'update');
        
        //Validate duplicate subscription
        if($this->repository->isSubscriptionExist($attributes)){
            throw new BadRequestHttpException(trans('errors.duplicate_subscription'));
        }else{
            try {
                DB::beginTransaction();
                $model = $this->repository->update($id, $attributes);
                DB::commit();
            } catch (BadRequestHttpException $e) {
                DB::rollback();
                throw new BadRequestHttpException($e->getMessage());
            } catch (QueryException $e) {
                DB::rollback();
                throw new \Exception(trans('errors.failed_to_invalid_record'));
            } catch (\Exception $e) {
                DB::rollback();
                throw new \Exception(trans('errors.failed_to_invalid_record'));
            }
        }
        
        return $this->transformResource($model);
    }
}
