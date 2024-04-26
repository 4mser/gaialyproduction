<!DOCTYPE html>
<html>

<head>
    <title>{{ env('APP_NAME') }}</title>
</head>

<body>
    <p>{{ __('Name') }}: {{ $data['name'] }}</p>
    <p>{{ __('Email address') }}: {{ $data['email'] }}</p>
    <p>{{ $data['message'] }}</p>
</body>

</html>
