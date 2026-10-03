<?php

use App\Models\Lead;
use App\Models\User;
use Database\Seeders\LeadCsvSeeder;

beforeEach(function () {
    $this->seed(LeadCsvSeeder::class);
});

test('owner can access all core pages', function () {
    $owner = User::where('role', 'owner')->first();

    $this->actingAs($owner)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Selamat Datang')
        ->assertSee('Codexa.id');

    $this->actingAs($owner)
        ->get(route('leads.index'))
        ->assertOk()
        ->assertSee('Data Prospek Purwokerto')
        ->assertSee('259 Leads');

    $this->actingAs($owner)
        ->get(route('projects.board'))
        ->assertOk()
        ->assertSee('Project Board');

    $this->actingAs($owner)
        ->get(route('settings.index'))
        ->assertOk()
        ->assertSee('Pengaturan');
});

test('developer only sees contacted leads in leads page', function () {
    $developer = User::where('role', 'developer')->first();

    $response = $this->actingAs($developer)
        ->get(route('leads.index'))
        ->assertOk();

    // Developer sees contacted leads
    $deal = Lead::where('status', 'Deal / Won')->first();
    $response->assertSee($deal->name);
});

test('role switch route works seamlessly', function () {
    $user = User::where('role', 'owner')->first();

    $this->actingAs($user)
        ->post(route('role.switch', 'developer'))
        ->assertRedirect();

    expect($user->fresh()->role)->toBe('developer');
});
