<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", "Grading System")</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('system_images/icon.png') }}">

</head>

<body>
    @yield("content")
</body>

</html>