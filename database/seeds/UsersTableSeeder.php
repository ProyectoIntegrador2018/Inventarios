<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creation of default user for testing
        User::create([
            'name'        => 'Luis Alfonso Rojo Sánchez',
            'email'       => 'luis_alfonso_96@hotmail.com',
            'password'    => Hash::make("123456"),
            'employee_id' => 'A01234567',
            'user_type'   => 'Administrator',
            'created_at'  => \Carbon\Carbon::now(),
            'updated_at'  => \Carbon\Carbon::now()
        ]);
    }
}
