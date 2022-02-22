<?php

declare(strict_types=1);

namespace Playground\Web;

use Aura\Router\RouterContainer;
use Closure;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface as Emitter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ResponseInterface as HttpResponse;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Relay\Relay;
use RKA\Middleware\IpAddress;
use Whoops\RunInterface as WhoopsInterface;
use function date_default_timezone_set;
use function error_reporting;
use const PHP_VERSION;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL); //すべてのPHPエラーを表示(-1)でも良いらしいが、互換性的に名前付き定数が推奨されている
date_default_timezone_set('Asia/Tokyo');

$container = (include __DIR__ . '/../app/di.php'); //これは何を変数に入れてる？
$container->get(WhoopsInterface::class)->register(); //interfaceをdiで注入してる

$server_request = $container->get(ServerRequest::class);
//コマンドライン以外から起動された場合&&ファイルがある時は終了
if (PHP_SAPI === 'cli-server' && is_file(__DIR__ . $server_request->getUri()->getPath())) {
    return false;
}

//psr17でpsr15に通せるやつを作る
$http = $container->get(Psr17Factory::class);
//A library-specific container.↓とは？？？
$router = $container->get(RouterContainer::class);

$_404 = fn (ResponseFactory $factory, StreamFactory $stream, View\HtmlFactory $html): HttpResponse
=> $factory->createResponse(404)->withBody($stream->createStream($html('404', [])));

/** @var array<Closure|MiddlewareInterface> */
$queue = [];

$queue[] = fn (ServerRequest $request, RequestHandler $handler): HttpResponse
=> $handler->handle($request)->withHeader('X-Powered-By', 'PHP/' . PHP_VERSION)->withHeader('X-Robots-Tag', 'noindex');
$queue[] = new IpAddress();
$queue[] = $container->get(Http\Dispatcher::class);
$queue[] = $container->get(Http\SessionSetter::class);

//クロージャー
$queue[] = function (ServerRequest $request, RequestHandler $handler) use ($http, $router): HttpResponse {
    $session = $request->getAttribute('session');

    $uri = $request->getUri()->getPath();

    // 利用規約とかログイン周りじゃなくかつ、ログインしてなかったら
    if (!in_array($uri, ['/', '/terms', '/login', '/phpinfo.php'], true) && !$session->isLoggedIn()) {
        $gen = $router->getGenerator();

        return $http->createResponse(302)->withHeader('Location', $gen->generate('terms')); //利用規約に飛ばす
    }

    return $handler->handle($request);
};

//psrに則ったやつ
$queue[] = fn (ServerRequest $request): HttpResponse
=> $container->call($router->getMatcher()->match($request)->handler ?? $_404, [
    'request' => $request,
]);

//psr15ハンドラ
$relay = new Relay($queue);
$response = $relay->handle($server_request);

$container->get(Emitter::class)->emit($response);
