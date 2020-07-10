<?php

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptions = [
            0 => [
                'plan_code'         => 'gb',
                'name'              => 'UK',
                'monthly_cost'      => 10,
                'annual_cost'       => 50
            ],
            1 => [
                'plan_code'         => 'fr',
                'name'              => 'France',
                'monthly_cost'      => 10,
                'annual_cost'       => 60
            ],
            2 => [
                'plan_code'         => 'de',
                'name'              => 'Germany',
                'monthly_cost'      => 15,
                'annual_cost'       => 75
            ],
            3 => [
                'plan_code'         => 'us',
                'name'              => 'USA',
                'monthly_cost'      => 25,
                'annual_cost'       => 150
            ],
            4 => [
                'plan_code'         => 'jp',
                'name'              => 'Japan',
                'monthly_cost'      => 15,
                'annual_cost'       => 65
            ],
        ];
        
        foreach ($subscriptions as $subscription){
            Subscription::create([
                'plan_code'         => $subscription['plan_code'],
                'name'              => $subscription['name'],
                'monthly_cost'      => $subscription['monthly_cost'],
                'annual_cost'       => $subscription['annual_cost'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
