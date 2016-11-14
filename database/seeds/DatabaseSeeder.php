<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('urls')->truncate();
        DB::table('users')->truncate();

        $this->call('UsersTableSeeder');
        $this->call('UrlsTableSeeder');

        Schema::enableForeignKeyConstraints();
    }
}
