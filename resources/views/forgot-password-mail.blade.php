<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password</title>
</head>
<body>
    <h3>Your new password is: <span>{{$mailData['password']}}</span></h3>
    <h4>You should immediately change your password.</h4>
    <h4><a href="http://127.0.0.1:8000/resetPassword">click here to change</a></h4>

</body>
</html>