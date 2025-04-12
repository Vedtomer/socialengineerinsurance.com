<?php

namespace Database\Seeders;

use App\Models\InsuranceCompany;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'id' => 100,
                'slug' => '100roya',
                'name' => 'ROYAL SUNDARAM GIC LTD.',
                'image' => '1717397602.jpg',
                'status' => 1,
                'created_at' => '2024-06-03 06:53:22',
                'updated_at' => '2024-06-03 06:53:22'
            ],
            [
                'id' => 101,
                'slug' => '101tata',
                'name' => 'TATA AIG',
                'image' => '1717397813.jpg',
                'status' => 1,
                'created_at' => '2024-06-03 06:56:53',
                'updated_at' => '2024-09-16 12:15:18'
            ],
            [
                'id' => 102,
                'slug' => '102god',
                'name' => 'GO DIGIT',
                'image' => '1717397994.jpg',
                'status' => 0,
                'created_at' => '2024-06-03 06:59:54',
                'updated_at' => '2024-09-07 10:21:04'
            ],
            [
                'id' => 103,
                'slug' => '103futu',
                'name' => 'FUTURE GENERALI',
                'image' => '1717570066.jpg',
                'status' => 0,
                'created_at' => '2024-06-05 06:47:46',
                'updated_at' => '2024-11-19 12:09:09'
            ],
            [
                'id' => 104,
                'slug' => '104icic',
                'name' => 'ICICI LOMBARD',
                'image' => '1727948528.png',
                'status' => 0,
                'created_at' => '2024-06-28 14:27:13',
                'updated_at' => '2025-04-03 12:39:21'
            ],
            [
                'id' => 105,
                'slug' => '105niti',
                'name' => 'NITISH TATA',
                'image' => '1725448389.jpg',
                'status' => 0,
                'created_at' => '2024-09-04 16:43:09',
                'updated_at' => '2024-11-19 12:09:16'
            ],
            [
                'id' => 106,
                'slug' => '106shri',
                'name' => 'SHRI RAM',
                'image' => '1726550957.jpg',
                'status' => 0,
                'created_at' => '2024-09-17 10:59:17',
                'updated_at' => '2024-10-08 22:04:34'
            ],
            [
                'id' => 107,
                'slug' => '107sami',
                'name' => 'SAMIDHA ARORA',
                'image' => '1729751180.png',
                'status' => 1,
                'created_at' => '2024-10-24 11:56:20',
                'updated_at' => '2024-10-24 11:56:20'
            ],
            [
                'id' => 108,
                'slug' => '108two-',
                'name' => 'TWO-WHEELER',
                'image' => '1738568051.jpg',
                'status' => 1,
                'created_at' => '2025-02-03 13:04:11',
                'updated_at' => '2025-02-03 13:04:11'
            ],
            [
                'id' => 109,
                'slug' => '109star',
                'name' => 'Star Health And Allied Insurance Company Limited',
                'image' => '1741249956.jpg',
                'status' => 1,
                'created_at' => '2025-03-06 13:59:21',
                'updated_at' => '2025-03-06 14:02:36'
            ]
        ];

        foreach ($companies as $company) {
            InsuranceCompany::updateOrCreate(['id' => $company['id']], $company);
        }
    }
}