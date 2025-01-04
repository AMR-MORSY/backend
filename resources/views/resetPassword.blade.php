<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>
<body>
    <h1>You have requested Password Reset</h1>
    <a href="{{Config::get('app.frontend_url')}}/user/{{$token}}" style="text-decoration: solid">Please click the link to reset your password</a>
    <p>Thank you.</p>
    <p>Cairo Team</p>
</body>
</html>