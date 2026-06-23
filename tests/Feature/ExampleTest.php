<?php

test('the home page sends guests to the login screen', function () {
    $response = $this->get(route('home'));

    $response->assertRedirect(route('login'));
});
