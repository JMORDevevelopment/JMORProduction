<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Newsletter form action
    |--------------------------------------------------------------------------
    |
    | Leave null to post to the local /newsletter/subscribe handler (dev).
    | In production set NEWSLETTER_FORM_ACTION to the Mailchimp embed URL:
    | https://XXXX.list-manage.com/subscribe/post?u=...&id=...
    |
    */

    'action' => env('NEWSLETTER_FORM_ACTION'),

];
