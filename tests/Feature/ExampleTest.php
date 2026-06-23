<?php

use Inertia\Testing\AssertableInertia;

test('the landing page renders for guests', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Landing'));
});
