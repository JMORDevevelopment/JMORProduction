@php
    $webchatWidgetUrl = config('services.webchat.widget_url');
    $webchatChatUrl = config('services.webchat.chat_url');
@endphp

@if ($webchatWidgetUrl && $webchatChatUrl)
    {{-- The existing PHP Live! chat engine (issue 056). The engine still runs
         at its installed location; this site loads its widget and every chat
         control falls back to the direct chat page, including when the widget
         script is blocked or there is no JavaScript. --}}
    <script>
        window.jmorWebChat = {
            chatUrl: @json($webchatChatUrl),
            widgetLoaded: false,
            open: function () {
                window.open(window.jmorWebChat.chatUrl, '_blank', 'noopener');
            },
        };

        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-chat-launch]');

            if (!trigger || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                return;
            }

            event.preventDefault();
            window.jmorWebChat.open();
        });
    </script>

    <script src="{{ $webchatWidgetUrl }}" async
        onload="window.jmorWebChat.widgetLoaded = true"
        onerror="window.jmorWebChat.widgetLoaded = false"></script>

    <noscript>
        <div style="position:fixed;right:1rem;bottom:1rem;z-index:9999;max-width:20rem;background:#fff;padding:.75rem 1rem;border-radius:.5rem;box-shadow:0 2px 8px rgba(0,0,0,.15);font-size:.9rem;">
            <a href="{{ $webchatChatUrl }}">Chat With Us</a> — live chat needs JavaScript.
            You can also call <a href="tel:+19733941000">(973) 394-1000</a>.
        </div>
    </noscript>
@endif
