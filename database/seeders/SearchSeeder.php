<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'test')->first();

        for ($i = 0; $i < 20 ; $i++) {
            Contact::create([
                'last_name' => 'first ' .$i,
                'first_name' => 'last ' .$i,
                'email' => 'test' .$i.'@pzn.com',
                'phone' => '1111'.$i,
                'user_id' => $user->id,
            ]);
        }
    }
}
