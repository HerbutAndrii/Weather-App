@extends('layout')
@section('title', 'Weather')
@section('content')
    <div class="weather">
        <p>{{ $data['name'] }}</p>
        <p>{{ $data['main']['temp'] }}°C</p>
        <img src="{{ asset('storage/' . $iconUrl) }}" alt="weather">
        <p>{{ $data['weather'][0]['main'] }}</p>
        <form action="{{ route('weather') }}" method="POST">
            @csrf
            <input type="text" name="city"> <br>
            @error('city')
                <div style="color: red; font-size: 17px; margin-bottom: 20px">{{ $message }}</div>
            @enderror
            <button type="submit">Weather</button>
        </form>
    </div>
@endsection