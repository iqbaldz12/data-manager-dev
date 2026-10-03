<?php

use App\Models\Lead;
use App\Models\User;
use Database\Seeders\LeadCsvSeeder;

beforeEach(function () {
    $this->seed(LeadCsvSeeder::class);
});

test('csv seeder loaded real purwokerto leads', function () {
    expect(Lead::count())->toBeGreaterThan(250);

    $kafe = Lead::where('category', 'Kafe')->first();
    expect($kafe)->not->toBeNull();
    expect($kafe->name)->not->toBeEmpty();
});

test('developer only sees contacted leads', function () {
    $developer = User::where('role', 'developer')->first();

    $this->actingAs($developer);

    $uncontacted = Lead::where('status', 'Belum Dihubungi')->first();
    expect($developer->can('view', $uncontacted))->toBeFalse();

    $deal = Lead::where('status', 'Deal / Won')->first();
    expect($developer->can('view', $deal))->toBeTrue();
    expect($deal->isContacted())->toBeTrue();
});

test('marketing cannot update progress but can update status', function () {
    $marketing = User::where('role', 'marketing')->first();
    $lead = Lead::where('status', 'Deal / Won')->first();

    expect($marketing->can('updateStatus', $lead))->toBeTrue();
    expect($marketing->can('updateProgress', $lead))->toBeFalse();
});

test('owner has full access to update and delete', function () {
    $owner = User::where('role', 'owner')->first();
    $lead = Lead::first();

    expect($owner->can('updateStatus', $lead))->toBeTrue();
    expect($owner->can('updateProgress', $lead))->toBeTrue();
    expect($owner->can('delete', $lead))->toBeTrue();
});
