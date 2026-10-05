<?php

test('social links appear only in the footer, never in the top bar', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertDontSee('jm-topbar__socials');
    $response->assertSee('jm-footer__socials');
});
