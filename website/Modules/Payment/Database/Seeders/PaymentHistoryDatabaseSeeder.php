<?php

namespace Modules\Payment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\PaymentHistory;

class PaymentHistoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::statement('truncate table payment_history');
        // \DB::statement('truncate table profiles');
        // \DB::table('users')->truncate();
        // \DB::table('profiles')->truncate();

        PaymentHistory::create([
            'uuid' => \Str::uuid(),
            'amount' => 300.00,
            'ref_model_name' => 'courses',
            'ref_id' => '1',
            'additional_ref_model_name' => null,
            'additional_ref_id' => null,
            'stripe_trans_id' => null,
            'stripe_trans_status' => null,
            'card_id' => null,
            'easypaisa_trans_id' => '123',
            'easypaisa_trans_status' => 'paid',
            'payment_method' => 'stripe',
            'payee_id ' => '1',
            'status ' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'remember_token' => '0',
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
