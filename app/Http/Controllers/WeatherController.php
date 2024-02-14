<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Stevebauman\Location\Facades\Location;

class WeatherController extends Controller
{
    public function index(Request $request) 
    {
        $ip = $request->getClientIp();
        $client = new Client();

        if($ip == "127.0.0.1") {
            $ip = $client->get('https://api.ipify.org')->getBody();
        }

        $location = Location::get($ip);
        $city = $location->cityName;

        $apiKey = "67eb439d946d3be3273ff030143857c0";
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

        $response = $client->get($apiUrl)->getBody();
        $data = json_decode($response, true);

        $iconCode = $data['weather'][0]['icon'];
        $iconUrl = "icons/" . $iconCode . ".png";

        return view('index', compact('data', 'iconUrl'));
    }

    public function getWeather(WeatherRequest $request) 
    {
        $apiKey = "67eb439d946d3be3273ff030143857c0";
        $city = $request->city;
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

        $client = new Client();

        try {
            $response = $client->get($apiUrl);
        } catch (RequestException $e) {
            return response()->json(['error' => 'City not found']);
        }

        $weather = json_decode($response->getBody(), true);

        $iconCode = $weather['weather'][0]['icon'];
        $iconUrl = asset('storage/icons/' . $iconCode . '.png');

        return response()->json([
            'weather' => $weather, 
            'iconUrl' => $iconUrl,
        ]);
    }

    public function getForecast(string $city) {
        $client = new Client();
        $apiKey = "67eb439d946d3be3273ff030143857c0";
        $responseFuture = $client->get("https://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$apiKey&units=metric");
        $futureWeather = json_decode($responseFuture->getBody(), true);

        return view('forecast', ['futureWeather' => $futureWeather]);
    }
}
