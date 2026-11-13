<?php

test('the homepage uses real calls to action instead of unsupported graphic placeholders', function () {
    $this->get(route('home'))
        ->assertSuccessful()
        ->assertDontSee('Partner badge')
        ->assertDontSee('Certification</div>', false)
        ->assertDontSee('Client logo')
        ->assertDontSee('>Post<', false)
        ->assertDontSee('jm-partners__grid', false)
        ->assertDontSee('jm-news__insta-grid', false)
        ->assertSee('Ask about our credentials')
        ->assertSee('View Instagram profile')
        ->assertSee('href="https://www.instagram.com/gosocialjmor/"', false);
});
