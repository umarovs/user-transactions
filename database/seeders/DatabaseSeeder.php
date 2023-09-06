<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Users\User::factory(10)->create();
        \App\Models\Users\User::all()
            ->each(function (User $user) {
                $user->balance = 1000;
                $user->save();
            });
    }
}
