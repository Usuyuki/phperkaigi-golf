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

# Atlas

DB を Eloquent の用に操作できるライブラリ

# 用語

## ハンドラ

> 何かを扱おうとした時に、それを行いやすくしてくれるものの「総称」

## ディスパッチャ

> 処理待ちのデータやプロセスに対して必要な資源の振り分けや割り当て、適切な受け入れ先への引き渡しを行うプログラム

ロードバランサと同じ意味？

# php-playground

golf 用のライブラリ？
実行環境を作る的な

psr15 のハンドラを用いてリクエスを受け取る  
index.php 80
↓  
ヘッダー出力  
index.php 51
↓  
PSR15 の図(バームクーヘンみたいなもの)の外側の Middleware の処理  
ip アドレスなど  
↓
PSR17 の ServerRequestInterface などを DI してハンドラに渡す  
Dispatcher.php 45  
↓  
セッションに書き込み
SessionSetter.php 41  
↓  
URL を見て、問題ないか確認。問題あれば利用規約にリダイレクト ← これも middleware？
index.php 69  
↓  
DI コンテナを呼ぶ(ハンドラが true なら返して、そうじゃなかったら 404 に)
index.php 75  
↓  
DI コンテナの謎の処理
ライブラリ側  
↓  
DI に定義を追加して、Atlas インスタンスを生成(JSON 系の認証も使用(config.php))  
Atlas を使って SQL を操作  
di.php 78  
↓  
エラー発生  
Undefined array key 0  
→ 配列のキーが未定義

PSR17 の HTTP factory というのは PSR7 定義の HTTP オブジェクトを作る的な形ですが、factory ということでテストデータを作るものだと勝手に誤解していましたが、

https://github.com/Usuyuki/phperkaigi-golf
