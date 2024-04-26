<!DOCTYPE html>
<html>

<head>
    <title>{{ env('APP_NAME') }}</title>
</head>

<body>
    <p>{{ __('Dear') }} {{ $user->name }} {{ $user->last_name }}:</p>
    <p>{{ __('We inform you that from this moment on you can enter our platform with the following data:') }}</p>
    <p>{{ __('Email:') }} <b>{{ $user->email }}</b></p>
    <p>{{ __('Password:') }}' <b>{{ $user->unhashed_password }}</b></p>
    <p>{{ __('Date:') }}' {{ date_format($user->updated_at, ' H:i:s - d/m/Y') }}</p>

    <p>{{ __('We invite you to enter by clicking on the following link:') }}' <a href="{{ App::make('url')->to('/') }}">link</a></p>

</body>

</html>
