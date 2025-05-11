<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - 404</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            text-align: center;
            padding: 50px;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        h1 {
            font-size: 72px;
            margin-bottom: 10px;
            color: #dc3545;
        }
        p {
            font-size: 24px;
            margin-bottom: 20px;
        }
        a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Sorry, the page you are looking for could not be found.</p>
    <p><a href="{{ route('home') }}">Return to Home</a></p>
</body>
</html>
