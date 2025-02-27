<?php

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('test create success', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);
    $contact = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test'])->post('/api/contacts/'.$contact->id.'/addresses', [
        'street' => 'test',
        'city' => 'test',
        'province' => 'test',
        'country' => 'test',
        'postal_code' => '213123'
    ]);

    $response->assertStatus(201);

    $response->assertJson([
        'data' => [
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => 'test',
            'postal_code' => '213123'
        ]
    ]);
});

test('test create failed', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);
    $contact = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test'])->post('/api/contacts/'.$contact->id.'/addresses', [
        'street' => 'test',
        'city' => 'test',
        'province' => 'test',
        'country' => '',
        'postal_code' => '213123'
    ]);

    $response->assertStatus(400);

    $response->assertJson([
        'errors' => [
            'country' => [
                'The country field is required.'
            ]
        ]
    ]);
});

test('test create contact not found', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);
    $contact = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test'])->post('/api/contacts/'.($contact->id + 1).'/addresses', [
        'street' => 'test',
        'city' => 'test',
        'province' => 'test',
        'country' => 'test',
        'postal_code' => '213123'
    ]);

    $response->assertStatus(404);

    $response->assertJson([
        'errors' => [
            'message' => [
                'not found'
            ]
        ]
    ]);
});

test('test get success', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $address = Address::query()->limit(1)->first();
    $response = $this->withHeaders(['Authorization' => 'test'])->get('/api/contacts/'.$address->contact_id.'/addresses/'.$address->id);
    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'street' => 'test',
            'city' => 'test',
            'province' => 'test',
            'country' => 'test',
            'postal_code' => '11111'
        ]
    ]);
});

test('test get not found', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $address = Address::query()->limit(1)->first();
    $response = $this->withHeaders(['Authorization' => 'test'])->get('/api/contacts/'.$address->contact_id.'/addresses/'.($address->id + 1));
    $response->assertStatus(404);
    $response->assertJson([
        'errors' => [
            'message' => [
                'not found'
            ]
        ]
    ]);

});
