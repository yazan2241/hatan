<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .input {
            height: 32px;
            width: 100%;
            background-color: white;
            color: black;
            border-radius: 8px;
            border: 1px rred solid;
        }
    </style>
</head>
<body>
    <div class="container" style="width: 100% ; text-align: center;">
        <div class="row" style="width: 50%; ">
            <img src="{{public_path('public/images/bloodBank.jpeg')}}" height="400px" />
            <form method="POST" action="">
                <div>
                    <input class="input" type="text" placeholder="+9999999999999" name="number" value=""/>
                    <input class="input" type="password" name="password" value=""/>
                </div>
            </form>
        </div>
    </div>
</body>
</html>