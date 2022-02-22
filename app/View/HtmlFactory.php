<?php

namespace Playground\Web\View;

use Playground\Web\Http\SessionStorage;
use Twig\Environment as Twig;

final class HtmlFactory
{
    private SessionStorage $session_storage;
    private Twig $twig;

    public function __construct(Twig $twig, SessionStorage $session_storage)
    {
        $this->session_storage = $session_storage;
        $this->twig = $twig;
    }

    /**
     * @param array<string,mixed> $params
     */
    public function __invoke(string $name, array $params): string
    {
        //初期化した→セッション入手
        $session = $this->session_storage->initialized() ? $this->session_storage->getSession() : null;


        //ここで該当するtwigテンプレートをレンダリングして返している
        return $this->twig->render("{$name}.html.twig", [
            'session' => $session,
        ] + $params);
    }
}