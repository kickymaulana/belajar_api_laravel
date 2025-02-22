<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);

test('test halaman beranda', function () {
    $response = $this->get('/');

    $response->assertStatus(200);

});
