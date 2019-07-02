<?php

namespace App\Repositories;


use Cache;
use DarkSky;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Services\DarkSkyService;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Promise\EachPromise;

class ForecastRepository
{
    const CACHE_EXPIRY_IN_HOURS = 2;
    const CACHE_PREFIX = 'FORECAST_';
    const TIME_MACHINE_TIME_IN_DAYS = 30;

    /**
     * Get forecasted based from location with default 7 days back track
     * @param $lat
     * @param $lng
     * @return mixed
     */
    public function getForecast($lat, $lng)
    {
        $expiry = Carbon::now()->addHours(self::CACHE_EXPIRY_IN_HOURS);
        $cacheKey = self::CACHE_PREFIX . "_{$lat}_{$lng}";
        
        $results = Cache::remember($cacheKey, $expiry, function() use ($lat, $lng) {
             return DarkSky::location($lat, $lng)
                            ->includes(['daily'])
                            ->get();
         });
        
        return $results->daily->data;
    }

    /**
     * Get forecasted based from location and specified time in days
     * @param $lat
     * @param $lng
     * @param int $timeInDays
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimeMachineForecast($lat, $lng, $timeInDays = self::TIME_MACHINE_TIME_IN_DAYS)
    {
        $expiry = Carbon::now()->addHours(self::CACHE_EXPIRY_IN_HOURS);
        $cacheKey = self::CACHE_PREFIX . "_{$lat}_{$lng}_{$timeInDays}";
        $forecasts = [];

        $results = Cache::remember($cacheKey, $expiry, function() use ($lat, $lng, $timeInDays, $forecasts) {
            $requests = $this->sendMultipleRequestsConcurrently($lat, $lng, $timeInDays);
            (new EachPromise($requests, [
                'concurrency' => 4,
                'fulfilled' => function ($forecast) use (&$forecasts) {
                    $forecasts[] = $forecast;
                },
            ]))->promise()->wait();

            return $forecasts;
        });

        return response()->json($results, 200);
    }

    /**
     * Concurrent request for Time Machine endpoint
     * @param $lat
     * @param $lng
     * @param $timeInDays
     * @return \Generator
     */
    private function sendMultipleRequestsConcurrently($lat, $lng, $timeInDays)
    {
        $parameters = [
            'excludes' => 'currently,minutely,hourly,alerts',
            'includes' => 'daily'
        ];
        $darkSkyService = new DarkSkyService;
        $client = new Client;
        $date = Carbon::now()->subDays($timeInDays);
        $i = 0;

        while($i < $timeInDays) {
                yield $client->requestAsync('GET', $darkSkyService->getEndpoint() . "/{$lat},{$lng},{$date->timestamp}?" . urldecode(http_build_query($parameters)))
                    ->then(function (ResponseInterface $response) {
                        return json_decode($response->getBody()->getContents(), true);
                    });
            $date->addDay();
            $i++;
        }
    }
}

