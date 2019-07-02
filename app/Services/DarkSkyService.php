<?php


namespace App\Services;


use Naughtonium\LaravelDarkSky\DarkSky;

class DarkSkyService extends DarkSky
{
    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Includes base uri & api key
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }
}