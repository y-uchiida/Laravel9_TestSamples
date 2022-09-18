# Laravel のユニットテスト

## tests ディレクトリの構成

```
📂 tests
 ├ 📂 Feature (フィーチャーテストのコードを保存する)
 │  └ 🗒️ ExampleTest.php
 ├ 📂 Unit (ユニットテストのコードを保存する)
 │  └ 🗒️ ExampleTest.php
 └ 🗒️ CreatesApplication.php (テストを実行する前に、Laravelアプリケーションをブートストラップするトレイト)
 └ 🗒️ TestCase.php (テスト基底クラス)
```

### CreatesApplication.php

以下、公式ドキュメントより引用

> Laravel には、アプリケーションのベース TestCase クラスに適用されている CreatesApplication トレイトがあります。
> このトレイトは、テストを実行する前に、Laravel アプリケーションをブートストラップする createApplication メソッドを持っています。
> Laravel の並列テスト機能など、いくつかの機能がこのトレイトへ依存しているため、この trait を元の場所へ残しておくことは重要です。

### TestCase.php

テスト実行の機能を継承したクラス  
個別のテストはこのクラスを継承して記述する

## テストコードのひな型生成

artisan コマンドを利用する

```bash
# tests/Feature に、フィーチャーテスト用のファイルを作成する
$ php artisan make:test SampleFeatureTest

# tests/Unit に、ユニットテスト用のファイルを作成する
$ php artisan make:test SampleUnitTest --unit
```

## テストメソッドの宣言

テストを行うためのメソッドを宣言する方法は、2 通りある

1. メソッド名に`test_` の prefix をつける
2. メソッドの doc コメントに`@test` アノテーションをつける

```php
// 1. prefixをつける場合
public function test_example1() {
    // テストの内容
}

// 2. doc コメントにアノテーションをつける場合
/**
 * @test
 */
public function example2() {
    // テストの内容を記述
}
```

## PHPUnit のアサーションメソッド

Laravel のテスト機能は PHPUnit を拡張しているので、PHPUnit が提供するアサーションメソッドの記述方法がそのまま使える

```php
/**
 * @test
 */
public function phpUnitAssertSample(){
    // 結果と期待値が型も含めて一致するか
    $this->assertSame($expected, $result);

    // 結果と期待値が一致するか
    $this->assertEquals($expected, $result);

    // 引数に渡された値がtrueか
    $this->assertTrue($result);

    // 引数に渡された値がfalseか
    $this->assertFalse($result);

    // 引数に渡された値がnullか
    $this->assertNull($result);

    // 値が期待するクラスのインスタンスか
    $this->assertInstanceOf($expected, $result);

    // 値が正規表現にマッチするか
    $this->assertRegExp($expected, $result);

    // 引数に渡された配列が、期待する要素数を持っているか
    $this->assertCount($expected, $array);

    // 引数に渡された連想配列が、期待するキーの要素を持っているか
    $this->assertArrayHasKey($expected, $array);

    // 引数に渡されたファイルが、期待する内容に一致するか
    $this->assertFileEquals($file_expected, $file_actual);

    // 引数に渡されたjsonファイルが、期待する内容のjsonファイルに一致するか
    $this->assertJsonFileEqualsJsonFile($file_expected, $file_actual);

}
```

## テストの実行

ユニットテストの実行は、Laravel に同梱された PHPUnit を利用する
`vendor/bin/phpunit` に保存されている

```bash
# 実行するファイルを指定すると、そのファイルに含まれるテストだけを実行する
$ vendor/bin/phpunit tests/Unit/TestExample.php

# ファイル名を指定しない場合、すべてのtestsディレクトリ内のすべてのファイルに含まれるテストメソッドを実行する
$ vendor/bin/phpunit

# sail で動作しているプロジェクトの場合、 sail test のコマンドでも実行可能
$ sail test
```

## データプロバイダ

同じ処理に対して、異なるパラメータを渡してテストを行いたい場合に利用できる  
配列形式で検証する値と期待値を列挙していく  
データプロバイダが返す配列の値は、これを受け取るテストメソッドの引数に一致する

```php
    // データプロバイダ
    public function dataProviderSample(): array
    {
        return [
            [0, 0], // テストメソッドの第1引数、第2引数 に渡される(増やせばそれだけ渡せる)
            '購入金額が100なら0pt' => [0, 100], // 連想配列の形式で、テストのラベルを付けることもできる
            '購入金額が999なら0pt' => [0, 999],
            '購入金額が1000なら10pt' => [10, 1000],
            '購入金額が10000なら200pt' => [200, 10000],
        ];
    }
```

## データプロバイダを利用するテストメソッド

-   doc コメントで利用するデータプロバイダを指定する
-   メソッドの引数に、データプロバイダから受け取る値の名前を決める

```php
    /**
     * @test
     * @dataProvider dataProviderSample // dataProviderSample というメソッドから値を受け取る
     */
    // データプロバイダから、 [$expected, $amount] の配列を受け取る
    public function TestFuncUsingDataProvider(int $expected, int $amount)
    {}
```
