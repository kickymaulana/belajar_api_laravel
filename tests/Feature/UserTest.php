
<?php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use Illuminate\Http\Request;

it('test register success', function () {
    $this->postJson('/api/user', [
        'username' => 'khannedy',
        'password' => 'rahasia',
        'name' => 'Eko Kurniawan khannedy'
    ])->assertStatus(201)
        ->assertJson([
            'data' => [
                'username' => 'khannedy',
                'name' => 'Eko Kurniawan khannedy'
            ]
        ]);
});


test('test register failed', function () {

    $this->postJson('/api/user', [
        'username' => '',
        'password' => '',
        'name' => ''
    ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'The username field is required.',
                'The password field is required.',
                'The name field is required.'
            ]
        ]);
});

test('test register username already exists', function () {

    $this->postJson('/api/user', [
        'username' => 'khannedy',
        'password' => 'rahasia',
        'name' => 'Eko Kurniawan khannedy'
    ])->assertStatus(201);

    $this->postJson('/api/user', [
        'username' => '',
        'password' => '',
        'name' => ''
    ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'The username field is required.',
                'The password field is required.',
                'The name field is required.'
            ]
        ]);
});
