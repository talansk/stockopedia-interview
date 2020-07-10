<?php
namespace Tests;

use Illuminate\Support\Facades\DB;

abstract class ApiTestCase extends TestCase 
{
    public function setUp():void
    {
        parent::setUp();
        DB::beginTransaction();
    }
    
    public function tearDown():void
    {
        DB::rollback();
        parent::tearDown();
        \Mockery::close();
    }
    
    protected abstract function get_dummy_data();
    
    protected abstract function validate_data($data);
}

