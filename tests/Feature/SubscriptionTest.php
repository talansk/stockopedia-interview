<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ApiTestCase;

class SubscriptionTest extends ApiTestCase
{
    use RefreshDatabase;
    
    /**
     * @test
     */
    public function it_fetch_the_subscriptions(){
        $response   =   $this->json('GET', 'api/v1/subscription_service/subscriptions/');
        $dataObj    =   json_decode($response->getContent());
        
        $this->assertEquals(200, $response->status());
        
        //Validate the Existence of the Attribute
        foreach($dataObj->data as $dataTuple) {
            $this->validate_data1($dataTuple);
        }
    }
    
    /**
     * @test
     */
    public function it_fetch_a_subscription(){
        $subscriptionId     = 100;
        $subscription       = array_merge(factory('App\Models\Subscription')->make()->toArray(), ['id' => $subscriptionId]);
        factory('App\Models\Subscription')->create($subscription);
        
        $response   =   $this->json('GET', 'api/v1/subscription_service/subscriptions/'.$subscriptionId);
        $dataObj    =   json_decode($response->getContent());
        
        $this->assertEquals(200, $response->status());
        //Validate the Existence of the Attribute
        $this->validate_data($dataObj->data);
    }
    
    /**
     * @test
     */
    public function it_create_the_subscription()
    {
        $subscriptionData   = $this->get_dummy_data();
        
        $response   = $this->json('POST', 'api/v1/subscription_service/subscriptions/', $subscriptionData);
        $dataObj    = json_decode($response->getContent());
        
        $this->assertEquals(201, $response->status());
        $this->validate_data($dataObj->data);
    }
    
    /**
     * @test
     */
    public function it_update_the_subscription()
    {
        $subscriptionId    = 100;
        $subscription      = array_merge(factory('App\Models\Subscription')->make()->toArray(), ['id' => $subscriptionId]);
        factory('App\Models\Subscription')->create($subscription);
        
        $subscriptionData       = $this->get_dummy_data();
        
        $response   = $this->json('PATCH', 'api/v1/subscription_service/subscriptions/' . $subscriptionId, $subscriptionData);
        $dataObj    = json_decode($response->getContent());
        
        $this->assertEquals(200, $response->status());
        $this->validate_data($dataObj->data);
    }
    
    /**
     * @test
     */
    public function it_delete_a_subscription() {
        $subscriptionId     = 100;
        $subscription      = array_merge(factory('App\Models\Subscription')->make()->toArray(), ['id' => $subscriptionId]);
        factory('App\Models\Subscription')->create($subscription);
        
        $response   =   $this->json('DELETE', 'api/v1/subscription_service/subscriptions/' . $subscriptionId);
        
        //Validate the Status Code
        $this->assertEquals(204, $response->status());
    }
    
    /**
     * @test
     */
    public function it_verify_delete_a_subscription_return_400() {
        $response    =   $this->json('DELETE', 'api/v1/subscription_service/subscriptions/blabla');
        
        //Validate the Status Code
        $this->assertEquals(400, $response->status());
    }
    
    /**
     * @test
     */
    public function it_verify_delete_a_subscription_return_404() {
        $response    =   $this->json('DELETE', 'api/v1/subscription_service/subscriptions/2000');
        
        //Validate the Status Code
        $this->assertEquals(404, $response->status());
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \Tests\ApiTestCase::validate_data()
     */
    protected function validate_data1($data) {
        $this->assertObjectHasAttribute('id',           $data);
        $this->assertObjectHasAttribute('name',         $data);
        $this->assertObjectHasAttribute('plan_code',    $data);
        $this->assertObjectHasAttribute('monthly_cost', $data);
        $this->assertObjectHasAttribute('annual_cost',  $data);
        $this->assertObjectHasAttribute('flag',         $data);
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \Tests\ApiTestCase::validate_data()
     */
    protected function validate_data($data) {
        $this->assertObjectHasAttribute('id',           $data);
        $this->assertObjectHasAttribute('name',         $data);
        $this->assertObjectHasAttribute('planCode',    $data);
        $this->assertObjectHasAttribute('monthlyCost', $data);
        $this->assertObjectHasAttribute('annualCost',  $data);
        $this->assertObjectHasAttribute('flag',         $data);
    }
    

    protected function get_dummy_data()
    {
        return [
        'plan_code'         => 'gb',
        'name'              => 'UK',
        'monthly_cost'      => 10,
        'annual_cost'       => 50
        ];
    }

}

