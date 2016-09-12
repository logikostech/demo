<?php

use LtDemo\Auth\UserEntity;
use Logikos\Auth\Manager as AuthManager;

return [
    'options' => [
        AuthManager::ATTR_ENTITY          => UserEntity::class,
        AuthManager::ATTR_EMAIL_REQUIRED  => false,
        AuthManager::ATTR_PASS_MIN_LEN    => 8,
        AuthManager::ATTR_PASS_MIN_LOWER  => 1,
        AuthManager::ATTR_PASS_MIN_UPPER  => 1,
        AuthManager::ATTR_PASS_MIN_NUMBER => 1,
        AuthManager::ATTR_PASS_MIN_SYMBOL => 1,
        AuthManager::ATTR_SESSION_TIMEOUT => 7*24*60*60
    ]
];