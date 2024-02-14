@extends('layout')
@section('title', 'Forecast')
@section('content')
    <h1>{{ $futureWeather['city']['name'] }}</h1>
    <div class="forecast-container">
        @foreach($futureWeather['list'] as $key => $weather)
            @if($key % 8 == 0)
                <div class="weather-forecast">
                    <h2>{{ \Carbon\Carbon::createFromTimestamp($weather['dt'])->format('D') }}</h2>
                    <p id="temp">{{ $weather['main']['temp'] }} °C</p>
                    <img src="{{ asset('storage/icons/' . $weather['weather'][0]['icon'] . '.png') }}" alt="weather">
                    <p id="weather">{{ $weather['weather'][0]['main'] }}</p>
                </div>
            @endif
        @endforeach
    </div>
@endsection