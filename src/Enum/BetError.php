<?php

namespace App\Enum;

class BetError
{
    public const LABELS = [
        self::UNKNOWN => 'Unknown error',
        self::STRUCTURE => 'Betslip structure mismatch',
        self::MIN_AMOUNT=> 'Minimum stake amount is :min_amount',
        self::MAX_AMOUNT => 'Maximum stake amount is :max_amount',
        self::MIN_SELECTIONS => 'Minimum number of selections is :min_selections',
        self::MAX_SELECTIONS => 'Maximum number of selections is :max_selections',
        self::MIN_ODDS => 'Minimum odds are :min_odds',
        self::MAX_ODDS => 'Maximum odds are :max_odds',
        self::DUPLICATE => 'Duplicate selection found',
        self::MAX_WIN => 'Maximum win amount is :max_win_amount',
        self::UNFINISHED => 'Your previous action is not finished yet',
        self::BALANCE => 'Insufficient balance',
    ];

    public const UNKNOWN = 0;
    public const STRUCTURE = 1;
    public const MIN_AMOUNT = 2;
    public const MAX_AMOUNT = 3;
    public const MIN_SELECTIONS = 4;
    public const MAX_SELECTIONS = 5;
    public const MIN_ODDS = 6;
    public const MAX_ODDS = 7;
    public const DUPLICATE = 8;
    public const MAX_WIN = 9;
    public const UNFINISHED = 10;
    public const BALANCE = 11;
}
