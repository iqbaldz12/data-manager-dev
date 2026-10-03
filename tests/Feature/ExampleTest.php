<?php

use App\Models\User;

test('redirects guest to login', function () {
    $response = $this->get(route('home'));

    $response->assertRedirect(route('login'));
});

test('login page returns a successful response', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

test('authenticated user can view dashboard', function () {
    $user = User::factory()->create(['role' => 'owner']);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
});
