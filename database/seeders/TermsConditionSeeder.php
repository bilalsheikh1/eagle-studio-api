<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TermsConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ["type" => "developer_terms_condition", "terms_condition" => "<p>OK</p>"],
            ["type" => "buyer_terms_condition", "terms_condition" => "<p>OK</p>"],
            ["type" => "reskin_terms_condition", "terms_condition" => "<p>OK</p>"],
            ["type" => "user_terms_condition", "terms_condition" => "<p>OK</p>"],
            ["type" => "affiliate_terms_condition", "terms_condition" => "<p>OK</p>"]
        ];
        \App\Models\TermsCondition::query()->upsert($data,["type"],["type"]);
    }
}
