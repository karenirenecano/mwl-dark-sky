<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchForecastRequest;
use App\Repositories\ForecastRepository;

class WeatherForecastController extends Controller
{
    private $forecastRepository;

    public function __construct(ForecastRepository $forecastRepository)
    {
        $this->forecastRepository = $forecastRepository;
    }

    public function forecast(Request $request)
    {
        $this->validate($request, [
            'lng' => 'required',
            'lat' => 'required',
        ]);


        $lat = $request->get('lat');
        $lng = $request->get('lng');

        $results = $this->forecastRepository->getForecast($lat, $lng);
        
        return $results;
    }

    public function timeMachine(Request $request)
    {
        $this->validate($request, [
            'lng' => 'required',
            'lat' => 'required',
        ]);


        $lat = $request->get('lat');
        $lng = $request->get('lng');

        $results = $this->forecastRepository->getTimeMachineForecast($lat, $lng);

        return $results;
    }
}
