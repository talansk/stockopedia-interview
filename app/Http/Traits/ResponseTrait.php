<?php namespace App\Http\Traits;

use Illuminate\Http\Response;

trait ResponseTrait {
    
    /**
     * Will return the Http Response
     *
     * @param   array       $data
     * @param   string      $status
     * @param   string      $responseType
     *
     * @return  array|\Illuminate\Http\JsonResponse
     */
    public function respond($data = [], $status = Response::HTTP_OK, $responseType = 'json') {
        switch ($responseType) {
            case 'json':
            default:
                return $this->respondInJson($data, $status);
        }
    }
    
    /**
     * Will return the Response Message in JSON
     *
     * @param   Int         $status
     * @param   array       $data
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function respondInJSON($data = [], $status = Response::HTTP_OK) {
        return response()->json($data, $status);
    }
}