# DI

https://qiita.com/fagai/items/bf336e56c5b2bddec749

SOLID 原則の依存性逆転の原則

> あるモジュールが別のモジュールを利用するとき、モジュールはお互いに直接依存すべきではなく、どちらのモジュールも、共有された抽象（インターフェイスや抽象クラスなど）に依存すべきであるという原則

を実現できる。

DI コンテナは Docker コンテナ的なのじゃなくて箱のイメージ

## Laravel の例

Laravel ではアプリそのものが DI コンテナ ←？？

php reflection

> ReflectionClass クラスは クラスについての情報を報告します。
> https://www.php.net/manual/ja/class.reflectionclass.php

> public function hoge(Request $request)できるのはこのため

→ これって Request タイプの$request を引数にとるよって意味じゃなかったの……

Request クラスがメソッドインジェクションで注入されているらしい・

# アロー関数

改めて。

アロー演算子の後が戻り値に

```php

$二乗した値=fn($x) => $x * 2;
```

# 無名関数(=クロージャー )

```php

 $mumeifunction($name){

 }

 //引き継ぐ場合は
 $id=1;
 $mumei=function($name) use($id){

 }


```

# DataSouth\MySQL

- Error
  - ErrorRow
  - ErrorTable
- Password
  - PasswordRow
  - PasswordTable
- Player
  - PlayerRow
  - PlayerTable

# Hole

問題コーナー

-

# Http

# tpl

twig template

# schema

マイグレーション的な

> scheme→ 構造という意味
