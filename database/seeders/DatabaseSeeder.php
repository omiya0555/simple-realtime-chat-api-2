<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserIconsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}