@extends('layout')
@section('title', 'Weather')
@section('content')
    <div class="weather">
        <p id="city">{{ $weather['name'] }}</p>
        <p id="temp">{{ $weather['main']['temp'] }} °C</p>
        <img src="{{ asset('storage/icons/' . $weather['weather'][0]['icon'] . '.png') }}" alt="icon">
        <p id="weather">{{ $weather['weather'][0]['main'] }}</p>
        <a href="{{ route('forecast', $weather['name']) }}">5-day forecast</a>
        <form action="{{ route('weather') }}" method="POST">
            @csrf
            <input type="text" name="city" id="input" placeholder="Enter a city"> <br>
            <div style="color: red; font-size: 20px; margin-bottom: 20px; text-align: center; display: none" id="city-error"></div>
            <button type="submit">Weather</button>
        </form>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('body').find('form').submit(function (event) {
                event.preventDefault();

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (data) {
                        if(typeof data.error === 'undefined') {
                            $('#city').text(data.weather['name']);
                            $('#temp').text(data.weather['main']['temp'] + ' °C');
                            $('#weather').text(data.weather['weather'][0]['main']);
                            $('img').attr('src', "{{ asset('storage/icons/:icon.png') }}".replace(':icon', data.weather['weather'][0]['icon']));
                            $('#input').val('');
                            $('#city-error').hide();
                            $('a').attr('href', "{{ route('forecast', ':city') }}".replace(':city', data.weather['name']));
                        } else {
                            $('#city-error').show().text(data.error);
                        }
                    },
                    error: function (err) {
                        let error = err.responseJSON;
                        $('#city-error').show();
                        $.each(error.errors, function (index, value) {
                            $('#city-error').text(value);
                        });
                    }
                });
            });
        });
    </script>
@endsection