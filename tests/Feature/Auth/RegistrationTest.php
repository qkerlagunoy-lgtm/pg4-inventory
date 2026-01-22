<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'first_name' => 'Test',
        'middle_name' => 'User',
        'last_name' => 'Example',
        'suffix' => 'Jr',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'sex' => 'male',
        'unit' => 'Test Unit',
        'category_id' => 1,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    expect(User::count())->toBe(1); // Check if user is created

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
