<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>
<body>
    <p>Hello,{{$name}}</p>

    <p>Please click the link to verify your account</p>

    <a href='{{Config::get("app.frontend_url")}}/user/signUp/{{$verification_code}}' style="text-decoration: solid">Please click here</a>
    <p>Thank you.</p>
    <p>Cairo Team</p>
</body>
</html>