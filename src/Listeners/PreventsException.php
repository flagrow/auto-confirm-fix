<?php

namespace Flagrow\AutoConfirmFix\Listeners;

use Flagrow\AutoConfirmFix\Middleware\RedirectsOnConfirmationError;
use Flarum\Event\ConfigureMiddleware;
use Flarum\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;

class PreventsException
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureMiddleware::class, [$this, 'prevent']);
    }

    public function prevent(ConfigureMiddleware $event)
    {
        if ($event->isForum()) {
            $event->pipe($this->app->make(RedirectsOnConfirmationError::class));
        }
    }
}
