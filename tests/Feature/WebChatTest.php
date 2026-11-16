<?php

test('the contact page offers a chat entry point instead of live support', function () {
    config([
        'services.webchat.chat_url' => 'https://chat.example.test/WebChat/chat',
        'services.webchat.widget_url' => 'https://chat.example.test/WebChat/widget.js',
    ]);

    $this->get(route('contact'))
        ->assertSuccessful()
        ->assertSee('Chat With Us')
        ->assertDontSee('Live Support')
        ->assertSee('href="https://chat.example.test/WebChat/chat"', false)
        ->assertSee('data-chat-launch', false)
        ->assertSee('aria-label="Chat with a JMOR representative (opens in a new tab)"', false);
});

test('the existing web chat widget is loaded sitewide', function (string $route) {
    config([
        'services.webchat.widget_url' => 'https://chat.example.test/WebChat/widget.js',
        'services.webchat.chat_url' => 'https://chat.example.test/WebChat/chat',
    ]);

    $this->get(route($route))
        ->assertSuccessful()
        ->assertSee('https://chat.example.test/WebChat/widget.js', false)
        ->assertSee('window.jmorWebChat', false);
})->with(['home', 'contact']);

test('chat controls fall back to the direct chat page when the widget is unavailable', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('chatUrl:')
        ->toContain('jmor.com\/WebChat\/chat')
        ->toContain('window.open(window.jmorWebChat.chatUrl')
        // Every launch control shares one handler, so keyboard and pointer
        // activation both open the chat.
        ->toContain("closest('[data-chat-launch]')")
        ->toContain('event.preventDefault()');
});

test('chat has a no javascript fallback with the phone number', function () {
    $html = $this->get(route('contact'))->assertSuccessful()->getContent();

    expect($html)
        ->toContain('<noscript>')
        ->toContain('live chat needs JavaScript')
        ->toContain('href="tel:+19733941000"');
});
