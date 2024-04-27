<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HolidaySeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('holidays')->insert(
            [
                [
                    'date' => new Carbon('0000-01-01'),
                    'country' => 'cz',
                ],
                [
                    'date' => new Carbon('0000-05-01'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-05-08'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-07-05'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-07-06'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-09-28'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-10-28'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-11-17'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-12-24'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-12-25'),
                    'country' => 'cz'
                ],
                [
                    'date' => new Carbon('0000-12-26'),
                    'country' => 'cz'
                ],
            ]
        );
    }
}
