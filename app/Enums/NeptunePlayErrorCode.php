<?php

namespace App\Enums;

enum NeptunePlayErrorCode: int
{
    case NO_ERROR = 0;
    case USER_ALREADY_EXISTS = 1;
    case USER_DOES_NOT_EXIST = 2;
    case INSUFFICIENT_AGENT_BALANCE = 3;
    case INSUFFICIENT_USER_BALANCE = 4;
    case NO_BETTING_LOG_EXIST = 5;
    case DUPLICATE_TRANSACTION = 6;
    case INVALID_TRANSACTION = 7;
    case BALANCE_LOG_DOES_NOT_EXIST = 8;
    case VENDOR_UNDER_MAINTENANCE = 9;
    case GAME_UNDER_MAINTENANCE = 10;
    case DEPRECATED_ENDPOINT = 20;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case UNKNOWN_SERVER_ERROR = 500;

    public function message(): string
    {
        return match ($this) {
            self::NO_ERROR => 'No error',
            self::USER_ALREADY_EXISTS => 'User already exists',
            self::USER_DOES_NOT_EXIST => 'User does not exist',
            self::INSUFFICIENT_AGENT_BALANCE => 'Insufficient agent balance',
            self::INSUFFICIENT_USER_BALANCE => 'Insufficient user balance',
            self::NO_BETTING_LOG_EXIST => 'No betting log exists',
            self::DUPLICATE_TRANSACTION => 'Duplicate transaction',
            self::INVALID_TRANSACTION => 'Invalid transaction',
            self::BALANCE_LOG_DOES_NOT_EXIST => 'Balance log does not exist',
            self::VENDOR_UNDER_MAINTENANCE => 'Vendor is under maintenance',
            self::GAME_UNDER_MAINTENANCE => 'Game is under maintenance',
            self::DEPRECATED_ENDPOINT => 'Deprecated endpoint',
            self::BAD_REQUEST => 'Bad request',
            self::UNAUTHORIZED => 'Unauthorized',
            self::UNKNOWN_SERVER_ERROR => 'Unknown server error',
        };
    }
}
