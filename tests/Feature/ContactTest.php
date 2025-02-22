<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('create success', function () {

    $this->seed([UserSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'test'])->post('/api/contacts', [
        'first_name' => 'Eko',
        'last_name' => 'Khannedy',
        'email' => 'eko@pzn.com',
        'phone' => '082108210821'
    ]);

    $response->assertStatus(201);

    $response->assertJson([
            'data' => [
                'first_name' => 'Eko',
                'last_name' => 'Khannedy',
                'email' => 'eko@pzn.com',
                'phone' => '082108210821'
            ]
        ]);

});
test('create failed', function () {

    $this->seed([UserSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'test'])->post('/api/contacts', [
        'first_name' => '',
        'last_name' => 'Khannedy',
        'email' => 'eko',
        'phone' => '082108210821'
    ]);

    $response->assertStatus(400);

    $response->assertJson([
        'errors' => [
            'first_name' => [
                'The first name field is required.'
            ]
        ]
    ]);
});


test('create Anauthorize', function () {

    $this->seed([UserSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'salah'])->post('/api/contacts', [
        'first_name' => '',
        'last_name' => 'Khannedy',
        'email' => 'eko',
        'phone' => '082108210821'
    ]);

    $response->assertStatus(401);

    $response->assertJson([
        'errors' => [
            'message' => [
                'unauthorized'
            ]
        ]
    ]);
});
