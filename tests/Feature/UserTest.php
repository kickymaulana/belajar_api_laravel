
<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

test('test login success', function () {

    $this->seed([UserSeeder::class]);


    $this->postJson('/api/user/login', [
        'username' => 'test',
        'password' => 'test'
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'username' => 'test',
                'name' => 'test'
            ]
        ]);
    $user = User::where('username', 'test')->first();

    self::assertNotNull($user->token);
    //expect($user->token)->not->toBeNull();


});

test('test login failed username not found', function () {

    $this->postJson('/api/user/login', [
        'username' => 'test',
        'password' => 'test'
    ])->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'username or password wrong'
                ]
            ]
        ]);


});
