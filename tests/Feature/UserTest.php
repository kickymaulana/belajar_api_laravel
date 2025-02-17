
<?php
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

test('test register harus sukses', function () {
    $this->postJson('/api/user', [
        'username' => 'kickymaulana',
        'password' => 'passwordmu',
        'name' => 'Kicky Maulana'
    ])->assertStatus(201)
        ->assertJson([
            'data' => [
                'username' => 'kickymaulana',
                'name' => 'Kicky Maulana'
            ]
        ]);
});


test('test register harus gagal', function () {

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

test('test register harusnya username udah ada', function () {

    $this->postJson('/api/user', [
        'username' => 'kickymaulana',
        'password' => 'passwordmu',
        'name' => 'Kicky Maulana'
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

test('test login harus suskes', function () {

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

test('test login gagal karena user tidak tersedia', function () {

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

test('test get current test success', function () {

    $this->seed([UserSeeder::class]);


    $response = $this->withHeaders([
        'Authorization' => 'test'
    ])->get('/api/user/current');

    $response->assertStatus(200)->assertJson([
        'data' => [
            'username' => 'test',
            'name' => 'test'
        ]

    ]);

});

test('test get unauthorized', function () {

    $this->seed([UserSeeder::class]);

    $response = $this->get('/api/user/current');

    $response->assertStatus(401);
    $response->assertJson([
        'errors' => [
            'message' => [
                'unauthorized'
            ],
        ]

    ]);

});

test('test get invalid token', function () {

    $this->seed([UserSeeder::class]);

    $response = $this->withHeaders([
        'Authorization' => 'salah'

    ])->get('/api/user/current');

    $response->assertStatus(401);
    $response->assertJson([
        'errors' => [
            'message' => [
                'unauthorized'
            ],
        ]

    ]);

});
