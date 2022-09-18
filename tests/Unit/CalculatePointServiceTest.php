<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use App\Services\CalculatePointService;

class CalculatePointServiceTest extends TestCase
{
    /**
     * @test
     */
    public function calcPoint_購入金額が0なら0pt()
    {
        $result = CalculatePointService::CalcPoint(0);
        $this->assertSame(0, $result);
    }

    /**
     * @test
     */
    public function calcPoint_購入金額が1000なら10pt()
    {
        $result = CalculatePointService::CalcPoint(1000);
        $this->assertSame(10, $result);
    }

    // データプロバイダ
    public function dataProvider_for_calcPoint(): array
    {
        return [
            [0, 0], // テストメソッドの第1引数、第2引数 に渡される(増やせばそれだけ渡せる)
            '購入金額が100なら0pt' => [0, 100], // 連想配列の形式で、テストのラベルを付けることもできる
            '購入金額が999なら0pt' => [0, 999],
            '購入金額が1000なら10pt' => [10, 1000],
            '購入金額が10000なら200pt' => [200, 10000],
        ];
    }

    // データプロバイダを受け取ってテストを実行するメソッド
    /**
     * @test
     * @dataProvider dataProvider_for_calcPoint
     */
    public function calcPointTest(int $expected, int $amount)
    {
        $result = CalculatePointService::CalcPoint($amount);
        $this->assertSame($expected, $result);
    }
}