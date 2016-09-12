<?php

return [ 
    'account' => [
        'demo' => [
            'driver'     => 'smtp',
            'host'       => 'ssl://smtp.googlemail.com',
            'port'       => 465,
            'encryption' => 'ssl',
            'username'   => getenv('MAILER_USER'),
            'password'   => getenv('MAILER_PASS'),
            'from'       => [
                'email' => getenv('MAILER_USER'),
                'name'  => 'demo'
            ]
        ]
    ]
];