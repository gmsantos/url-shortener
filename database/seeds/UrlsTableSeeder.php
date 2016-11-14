<?php

use Illuminate\Database\Seeder;

class UrlsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::all();

        factory(App\Url::class, 50)->create()->each(function ($u) use ($users) {
            $u->user()->save($users->random());
        });
    }
}