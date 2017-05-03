<?php

namespace Flagrow\AutoConfirmFix\Middleware;

use Flarum\Foundation\Application;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class RedirectsOnConfirmationError
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Catch all errors that happen during further middleware execution.
     *
     * @param Request $request
     * @param Response $response
     * @param callable $out
     * @return Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $response = $out($request, $response);

        if (Str::startsWith($request->getUri()->getPath(), '/confirm/') &&
            $response instanceof HtmlResponse &&
            $response->getBody() == 'Invalid confirmation token') {
            return new RedirectResponse($this->app->url());
        }

        return $response;
    }
}
