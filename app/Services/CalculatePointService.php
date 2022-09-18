<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\PreconditionException;

final class CalculatePointService
{
    /**
     * 購入金額にによって、付与するポイントを計算する
     * 付与条件は以下
     * 1. 購入金額が \0 ~ \999の場合 = 0pt
     * 2. 購入金額が \1,000 ~ \9,999 の場合 = \100につき1pt
     * 3. 購入金額が \10,000以上 の場合 = \100 につき2pt
     * ---
     * @param int $amount
     * @return int
     * @throws PreconditionException
     */
    public static function CalcPoint(int $amount): int
    {
        if ($amount < 0) {
            throw new PreconditionException('購入金額が負の数');
        }
        if ($amount < 1000) {
            return 0;
        }
        if ($amount < 10000) {
            $basePint = 1;
        } else {
            $basePint = 2;
        }

        return intval($amount / 100) * $basePint;
    }
}