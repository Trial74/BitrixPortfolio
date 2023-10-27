<?
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 300');//300 seconds
?>
<!DOCTYPE html>
<html>
<head>
    <title>EXTREME LOOK</title>
    <meta charset="utf-8"/>
    <style>
        body
        {
            margin: 0;
            padding: 0;
            font: 14px Arial, Helvetica, sans-serif;
            background: #f1f1f1;
        }
        .message
        {
            -webkit-background-size: 40px 40px;
            -moz-background-size: 40px 40px;
            background-size: 40px 40px;
            background-image: -webkit-gradient(linear, left top, right bottom,
            color-stop(.25, rgba(255, 255, 255, .05)), color-stop(.25, transparent),
            color-stop(.5, transparent), color-stop(.5, rgba(255, 255, 255, .05)),
            color-stop(.75, rgba(255, 255, 255, .05)), color-stop(.75, transparent),
            to(transparent));
            background-image: -webkit-linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
            transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
            transparent 75%, transparent);
            background-image: -moz-linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
            transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
            transparent 75%, transparent);
            background-image: -ms-linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
            transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
            transparent 75%, transparent);
            background-image: -o-linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
            transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
            transparent 75%, transparent);
            background-image: linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
            transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
            transparent 75%, transparent);

            -moz-box-shadow: inset 0 -1px 0 rgba(255,255,255,.4);
            -webkit-box-shadow: inset 0 -1px 0 rgba(255,255,255,.4);
            box-shadow: inset 0 -1px 0 rgba(255,255,255,.4);
            width: 50%;
            border: 1px solid;
            color: #fff;
            padding: 15px;
            margin-left: 25%;
            margin-top: 15%;
            position: fixed;
            _position: absolute;
            text-shadow: 0 1px 0 rgba(0,0,0,.5);
            -webkit-animation: animate-bg 5s linear infinite;
            -moz-animation: animate-bg 5s linear infinite;
        }
        .success
        {
            background-color: #19954B;
            border-color: #55a12c;
        }
        .message h3
        {
            margin: 0 0 5px 0;
        }
        .message p
        {
            margin: 0;
        }
        @-webkit-keyframes animate-bg
        {
            from {
                background-position: 0 0;
            }
            to {
                background-position: -80px 0;
            }
        }
        @-moz-keyframes animate-bg
        {
            from {
                background-position: 0 0;
            }
            to {
                background-position: -80px 0;
            }
        }

    </style>
</head>
<body>
<div class="success message">
    <h3>Сайт EXTREME LOOK находится на техническом обслуживании</h3>
    <p>Приносим извинения за неудобства. Для оформления заказа воспользуйтесь нашим мобильным приложением</p>
</div>
</body>
</html>
