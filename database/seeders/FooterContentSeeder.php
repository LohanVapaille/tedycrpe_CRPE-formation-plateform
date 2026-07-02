<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FooterContentSeeder extends Seeder
{
    public function run(): void
    {
        $footerContents = [
            [
                'key' => 'footer_company_name',
                'value' => 'Tedy Florette Formation CRPE',
            ],
            [
                'key' => 'footer_address',
                'value' => "SAS au capital de 180 380€\n18, rue Lucien Granet\n33150, Cenon, France",
            ],
            [
                'key' => 'footer_phone',
                'value' => '+33 5 56 40 14 02',
            ],
            [
                'key' => 'footer_facebook_url',
                'value' => '#',
            ],
            [
                'key' => 'footer_linkedin_url',
                'value' => '#',
            ],

        ];

        foreach ($footerContents as $content) {
            DB::table('contents')->updateOrInsert(
                ['page' => 'footer', 'key' => $content['key']],
                [
                    'section' => 'footer',
                    'value' => $content['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}