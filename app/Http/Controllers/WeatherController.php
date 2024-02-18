<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Stevebauman\Location\Facades\Location;

class WeatherController extends Controller
{
    private $apiKey;
    private $client;

    public function __construct() 
    {
        $this->apiKey = "67eb439d946d3be3273ff030143857c0";
        $this->client = new Client(['base_uri' => 'https://api.openweathermap.org/data/2.5/']);
    }

    public function index(Request $request) 
    {
        $ip = $request->getClientIp();

        if($ip == "127.0.0.1") {
            $ip = $this->client->get('https://api.ipify.org')->getBody();
        }

        $location = Location::get($ip);
        $city = $location->cityName;

        $response = $this->client->get('weather', [
            'query' => [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]
        ]);

        $weather = json_decode($response->getBody(), true);

        return view('index', compact('weather'));
    }

    public function getWeather(WeatherRequest $request) 
    {
        $city = $request->city;

        try {
            $response = $this->client->get('weather', [
                'query' => [
                    'q' => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric'
                ]
            ]);
        } catch (RequestException $e) {
            return response()->json(['error' => 'City not found']);
        }

        $weather = json_decode($response->getBody(), true);

        return response()->json(['weather' => $weather]);
    }

    public function getForecast(string $city) 
    {
        $response = $this->client->get('forecast', [
            'query' => [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]
        ]);

        $futureWeather = json_decode($response->getBody(), true);

        return view('forecast', compact('futureWeather'));
    }
}
