<?php

declare(strict_types=1);

namespace App\Middleware\Module;

use App\Container\SettingsAwareTrait;
use App\Event;
use App\Http\ServerRequest;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Interfaces\RouteInterface;
use Slim\Routing\RouteContext;

/**
 * Module middleware for the /station pages.
 */
final class Stations
{
    use SettingsAwareTrait;

    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(ServerRequest $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $view = $request->getView();

        $station = $request->getStation();
        $view->addData(
            [
                'station' => $station,
            ]
        );

        $settings = $this->readSettings();

        $event = new Event\BuildStationMenu($station, $request, $settings);
        $this->dispatcher->dispatch($event);

        $active_tab = null;
        $current_route = RouteContext::fromRequest($request)->getRoute();
        if ($current_route instanceof RouteInterface) {
            $route_parts = explode(':', $current_route->getName() ?? '');
            $active_tab = $route_parts[1];
        }

        $view->getSections()->set(
            'sidebar',
            $view->render(
                'stations/sidebar',
                [
                    'menu' => $event->getFilteredMenu(),
                    'active' => $active_tab,
                ]
            ),
        );

        return $handler->handle($request);
    }
}
