<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserIconsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_icons')->insert([
            ['path' => '/images/icons/apple.png'],
            ['path' => '/images/icons/banana.png'],
            ['path' => '/images/icons/orange.png'],
            ['path' => '/images/icons/lemon.png'],
            ['path' => '/images/icons/testuser.png'],
        ]);
    }
}
