<?php

declare(strict_types=1);

namespace App\Controller\Stations;

use App\Container\SettingsAwareTrait;
use App\Http\Response;
use App\Http\ServerRequest;
use App\Service\AzuraCastCentral;
use Psr\Http\Message\ResponseInterface;

final class StreamersAction
{
    use SettingsAwareTrait;

    public function __construct(
        private readonly AzuraCastCentral $acCentral,
    ) {
    }

    public function __invoke(
        ServerRequest $request,
        Response $response,
        string $station_id
    ): ResponseInterface {
        $station = $request->getStation();

        $settings = $this->readSettings();
        $backendConfig = $station->getBackendConfig();

        $router = $request->getRouter();

        return $request->getView()->renderVuePage(
            response: $response,
            component: 'Vue_StationsStreamers',
            id: 'station-streamers',
            title: __('Streamer/DJ Accounts'),
            props: [
                'listUrl' => $router->fromHere('api:stations:streamers'),
                'newArtUrl' => $router->fromHere('api:stations:streamers:new-art'),
                'scheduleUrl' => $router->fromHere('api:stations:streamers:schedule'),
                'stationTimeZone' => $station->getTimezone(),
                'connectionInfo' => [
                    'serverUrl' => $settings->getBaseUrl(),
                    'streamPort' => $backendConfig->getDjPort(),
                    'ip' => $this->acCentral->getIp(),
                    'djMountPoint' => $backendConfig->getDjMountPoint(),
                ],
            ]
        );
    }
}
