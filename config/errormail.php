<?php

return [
    'error_mail_enabled' => env('ERROR_MAIL_ENABLED', false),
    'error_mail_to' => array_filter(
        array_map('trim', explode(',', env('ERROR_MAIL_TO', 'geo@vanetworks.in,abhilash@vanetworks.in')))
    ),
];
