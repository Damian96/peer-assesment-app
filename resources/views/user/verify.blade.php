<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Verify Email</title>

    <link rel="stylesheet" href="css/app.css">
    <script src="js/app.js" charset="utf-8"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1>Verify Email</h1>
                <p>We have sent a verification email, to the address that you provided.</p>
                <p>Please check your inbox on <a href="{{ $email }}">{{ $email }}</a>,
                and follow the instructions.</p>
            </div>
        </div>
    </div>
</body>
</html>
