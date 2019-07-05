# Wether Weather
Wether Weather is an application that allows the user to view the observed (in the past 30 days) or forecasted (in the future) daily weather conditions for a given location using the [Dark Sky API](https://darksky.net/dev/docs).

## Demo

Check the demo here [madewithlove-exam.devcaffeinated.com](http://madewithlove-exam.devcaffeinated.com/)

## Views

* **home page**: This is where to input the location
* **forecast page**: This is where the current week forecast is displayed in °F
* **historical forecast page**: This is where you can see the past 30 days forecast for that location in a line chart graph.

### Frameworks used

* Laravel Lumen 5.8 (Backend)
* Vuejs (Frontend)

### Plugins installed
* [barryvdh/laravel-cors](https://github.com/barryvdh/laravel-cors)
    For CORS Handling
* [bref/bref](https://bref.sh/)
    For AWS Serverless Deployment of PHP applications
* [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)
    For sending concurrent requests
* [laravel/lumen-framework](https://lumen.laravel.com)
    Base microframework used
* [naughtonium/laravel-dark-sky](https://packagist.org/packages/naughtonium/laravel-dark-sky) 
    Provides a Wrapper for the DarkSky API
* [sentry/sentry-laravel](https://github.com/getsentry/sentry-laravel)
    Laravel integration for Sentry error logging

### APIs used

* [Dark Sky API](https://darksky.net/dev)
* [Google Maps JavaScript API](https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform)

### Architecture

* Backend
Backend is running on a serverless application using the bref library that runs on top of the nodejs serverless library. I opted on choosing this because I am using RESTful api's for my frontend to consume. Thus a need for CORS handling to reject any requests not whitelisted on the allowed origins this allows us to use Web applications within browsers when domains aren't the same. I used the Dark sky Laravel wrapper to use its API and the Guzzle library to send concurrenct requests to time machine endpoint of the Dark sky api.

* Frontend
It is built with Vuejs with a css template I got from [w3schools](https://w3layouts.com/weather-report-widget-flat-responsive-widget-template/). This end is consuming these endpoints. Postman Collection link to be imported is [here](https://www.getpostman.com/collections/0fe8a20d2114fb9c9e93).

| Method        | Endpoint           | Description  |
| ------------- |:------------------:| ------------:|
| GET           | /forecasts         | Show weather forecasts for the week |
| GET           | /time-machine/forecasts      | Show 30 Day weather forecast back track  |



### Environment configurations

* gogleMapsApiKey, on main.js of UI Vuejs
* DARKSKY_API_KEY, on .env of Lumen
