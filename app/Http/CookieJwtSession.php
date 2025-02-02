<?php

declare(strict_types=1);

namespace Playground\Web\Http;

use Bag2\Cookie\Oven;
use Cake\Chronos\Chronos;
use Exception;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSLoader;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer as JoseSerializer;
use Playground\Web\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as ServerRequest;
use function Safe\json_decode;
use function Safe\json_encode;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;


// これ以上継承できないようにfinal
//JWT→JSON Web Token

/**
 * クッキーで認証関連を扱うクラス
 */
final class CookieJwtSession implements SessionStorage
{
    private const COOKIE_LIFETIME_OFFSET = 60 * 60 * 24 * 30;

    private string $cookie_name;
    private JWK $jwk;
    private JWSBuilder $jws_builder;
    private JWSLoader $jws_loader;
    private JWSVerifier $jws_verifier;
    private Chronos $now;
    private Oven $oven;
    private JoseSerializer $serializer;
    private Session $session;

    //インスタンス化のときに実行
    public function __construct(
        JoseSerializer $serializer,
        JWK $jwk,
        JWSBuilder $jws_builder,
        JWSLoader $jws_loader,
        JWSVerifier $jws_verifier,
        Chronos $now,
        Oven $oven,
        string $cookie_name
    ) {
        $this->serializer = $serializer;
        $this->jwk = $jwk;
        $this->jws_builder = $jws_builder;
        $this->jws_loader = $jws_loader;
        $this->jws_verifier = $jws_verifier;
        $this->now = $now;
        $this->oven = $oven;
        $this->cookie_name = $cookie_name;
    }

    //JWS→JSON Web Signature
    /**
     * @return array{accepted_terms?:bool,id?:int,name?:string}
     */
    private function fetchJws(string $token): array
    {
        try {
            //与えられたキーで認証する
            $jws = $this->jws_loader->loadAndVerifyWithKey($token, $this->jwk, $_);
        } catch (Exception $e) {
            //エラーが意図したものでなかったら空配列
            if ($e->getMessage() !== 'Unable to load and verify the token.') {
                throw $e;
            }

            return [];
        }

        return json_decode($jws->getPayload() ?? '', true); //jsonを展開したものを返す
    }

    public function fromRequest(ServerRequest $request): self
    {
        //cookieを取得、スーパーグローバールを使っていないのはテストなどで事故るからだと思われる
        $cookies = $request->getCookieParams();

        //クッキーあればその情報引っ張り出す
        if (isset($cookies[$this->cookie_name])) {
            $data = $this->fetchJws($cookies[$this->cookie_name]);
        } else {
            $data = [];
        }

        $this->session = Session::fromArray($data);

        return $this;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function initialized(): bool
    {
        return isset($this->session);
    }

    public function writeTo(Response $response): Response
    {
        $data = [
            'iat' => $this->now->timestamp,
        ] + $this->session->jsonSerialize();
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $jws = $this->jws_builder
            ->create()
            ->withPayload($payload)
            ->addSignature($this->jwk, ['alg' => 'HS256'])
            ->build();

        $this->oven->add($this->cookie_name, $this->serializer->serialize($jws, 0), [
            'expires' => $this->now->timestamp + self::COOKIE_LIFETIME_OFFSET,
        ]);

        return $this->oven->appendTo($response); //psr7準拠のクッキー追加系の処理
    }
}