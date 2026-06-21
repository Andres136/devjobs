<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salarios = [
            '$0 - $499',
            '$500 - $749',
            '$750 - $999',
            '$1000 - $1499',
            '$1500 - $1999',
            '$2000 - $2499',
            '$2500 - $2999',
            '$3000 - $4999',
            '+$5000',
        ];

        foreach ($salarios as $salario) {
            DB::table('salarios')->updateOrInsert(
                ['salario' => $salario],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}

