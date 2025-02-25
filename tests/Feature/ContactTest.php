<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

use function GuzzleHttp\json_encode;

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

test('test get success', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);

    $contacts = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->get('/api/contacts/'.$contacts->id);

    $response->assertStatus(200);

    $response->assertJson([
        'data' => [
            'first_name' => 'first',
            'last_name' => 'last',
            'email' => 'test@pzn.com',
            'phone' => '08210821',
        ]
    ]);

});


test('test get contact tidak ketemu', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);

    $contacts = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->get('/api/contacts/'.($contacts->id + 1));

    $response->assertStatus(404);

    $response->assertJson([
        'errors' => [
            'message' => [
                'not found'
            ],
        ]
    ]);

});


test('test ambil contact punya orang lain', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);

    $contacts = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test2']);

    $response = $this->get('/api/contacts/'.($contacts->id + 1));

    $response->assertStatus(404);

    $response->assertJson([
        'errors' => [
            'message' => [
                'not found'
            ],
        ]
    ]);

});

test('test update success', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);

    $contacts = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->put('/api/contacts/'.$contacts->id, [
            'first_name' => 'test2',
            'last_name' => 'test2',
            'email' => 'test2@pzn.com',
            'phone' => '082108212',
    ]);

    $response->assertStatus(200);

    $response->assertJson([
        'data' => [
            'first_name' => 'test2',
            'last_name' => 'test2',
            'email' => 'test2@pzn.com',
            'phone' => '082108212',
        ]
    ]);

});

test('test update validation errors', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);

    $contacts = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->put('/api/contacts/'.$contacts->id, [
            'first_name' => '',
            'last_name' => 'test2',
            'email' => 'test2@pzn.com',
            'phone' => '082108212',
    ]);

    $response->assertStatus(400);

    $response->assertJson([
        'errors' => [
            'first_name' => [
                'The first name field is required.'
            ],
        ]
    ]);

});

test('test delete success', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);

    $contacts = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->delete('/api/contacts/'.$contacts->id);

    $response->assertStatus(200);

    $response->assertJson([
        'data' => true
    ]);

});

test('test delete not found', function () {

    $this->seed([UserSeeder::class, ContactSeeder::class]);

    $contacts = Contact::query()->limit(1)->first();

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->delete('/api/contacts/'.($contacts->id + 1));

    $response->assertStatus(404);

    $response->assertJson([
        'errors' => [
            'message' => [
                'not found'
            ]
        ]
    ]);

});

test('test search by first name', function () {

    $this->seed([UserSeeder::class, SearchSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->get('/api/contacts?name=first');

    $response->assertStatus(200);


    //Log::info(json_encode($response->json(), JSON_PRETTY_PRINT));

    // dd($response['meta']['total']);
    $response = $this->assertEquals(20, $response['meta']['total']);

});

test('test search by last name', function () {

    $this->seed([UserSeeder::class, SearchSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->get('/api/contacts?last=first');

    $response->assertStatus(200);


    //Log::info(json_encode($response->json(), JSON_PRETTY_PRINT));

    // dd($response['meta']['total']);
    $response = $this->assertEquals(20, $response['meta']['total']);

});

test('test search by email', function () {

    $this->seed([UserSeeder::class, SearchSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->get('/api/contacts?email=test');

    $response->assertStatus(200);


    //Log::info(json_encode($response->json(), JSON_PRETTY_PRINT));

    // dd($response['meta']['total']);
    $response = $this->assertEquals(20, $response['meta']['total']);


});

test('test search by phone', function () {

    $this->seed([UserSeeder::class, SearchSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->get('/api/contacts?phone=11111');

    $response->assertStatus(200);


    //Log::info(json_encode($response->json(), JSON_PRETTY_PRINT));

    // dd($response['meta']['total']);
    $response = $this->assertEquals(20, $response['meta']['total']);

});


test('test search not found', function () {

    $this->seed([UserSeeder::class, SearchSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->get('/api/contacts?name=tidakada');

    $response->assertStatus(200);

    $response = $this->assertEquals(0, $response['meta']['total']);

});

test('test search with page', function () {


    $this->seed([UserSeeder::class, SearchSeeder::class]);

    $response = $this->withHeaders(['Authorization' => 'test']);

    $response = $this->get('/api/contacts?size=5&page=2');

    Log::info(json_encode($response->json(), JSON_PRETTY_PRINT));

    $response->assertStatus(200);

    $response = $this->assertEquals(5, count($response['data']));

});
