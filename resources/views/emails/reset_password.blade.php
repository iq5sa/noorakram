<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
          rel="stylesheet">

    <title></title>
    <style>

        body {
            margin: 0;
            padding: 20px 0;
            font-family: Tajawal, sans-serif;
            font-size: 16px;
            box-sizing: border-box;
            background-color: #f2f2f2;
            direction: rtl;
            text-align: right;
        }

        img {
            width: 100%;
            border: 0;
            outline: none;
        }

        h2 {
            padding: 50px 0 0 0;
            margin: 0;
            font-weight: 700;
            font-size: 25px;
            line-height: 30px;
        }

        p.text-main {
            margin: 0;
            padding-top: 17px;
            font-size: 15.1898734px;
        }

        .wrapper {
            max-width: 470px;
            margin: 0 auto;
            height: 100%;
        }

        .container {
            background-color: #FAFAF9;
            height: inherit;
            -webkit-box-shadow: inset 0px 0px 0px 0.8px #E4E4E4;
            -moz-box-shadow: inset 0px 0px 0px 0.8px #E4E4E4;
            box-shadow: inset 0px 0px 0px 0.8px #E4E4E4;
            border-radius: 2px;
        }

        .header {
            position: relative;
            background: #59a1a9;
            /*height: 80px;*/
            text-align: center;
            color: #FAFAF9;
            font-weight: 700;
            font-size: 18px;
            /*line-height: 80px;*/
            padding: 31px 11% 31px 11%;
            border-radius: 2px 2px 0 0;
        }

        .main-content {
            padding: 28px 11% 20px 11%;
            text-align: center;
            color: #201F2F;
        }

        .button {
            text-decoration: none;
            border-radius: 3px;
            font-size: 15px;
            font-weight: 700;
            color: #FAFAF9;
            outline: 0;
            outline-offset: 0;
            border: 0;
            background-color: #59a1a9;
            padding: 15px 40px;
            display: inline-block;
            margin-top: 31px;
        }

        .footer {
            height: 90px;
            padding-top: 16px;
            padding-left: 11%;
            padding-right: 11%;
            font-size: 13px;
            line-height: 15px;
            text-align: center;
        }

        .footer p,
        .footer a {
            font-size: 11px;
            line-height: 13px;
            margin: 0;
            padding: 0 0 6px;
            color: #a9a9a9;
            text-align: center;
        }

        p.sub-text {
            margin: 0;
            padding-top: 100px;
            font-size: 15px;
            color: #62626d;
        }

        p.long-link {
            font-size: 10px;
            text-align: justify;
            overflow-wrap: anywhere;
            color: #62626d;
        }
    </style>
</head>

<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
            اعادة تعيين كلمة المرور.
        </div>
        <div class="main-content">
            <img src="https://noorakram.b-cdn.net/img/logo.png" style="width: 100px"
                 alt="undraw-festivities-tvvj-transparent" border="0">

            <h2>
                <span>مرحبا </span>
                <span> ،{{$mailData["name"]}}</span>
            </h2>
            <p class="text-main">


                لقد تلقينا طلبًا لإعادة تعيين كلمة المرور الخاصة بك. إذا لم تكن قد قمت بتقديم الطلب، فما عليك سوى تجاهل
                هذه الرسالة الإلكترونية. بخلاف ذلك، يمكنك إعادة تعيين كلمة المرور الخاصة بك من خلال الضغط على زر في
                الاسفل .
            </p>
            <a style="color: white" href="{{$mailData['url']}}" class="button">اعادة تعيين كلمة السر</a>
            <p class="sub-text">او قم بنسخ الرابط في الاسفل وضعه في المتصفح: </p>
            <p class="long-link">
                {{$mailData['url']}}
            </p>
        </div>
    </div>
    <div class="footer">
        <p>إذا لم تكن قد قمت بهذه العملية من قبل، فما عليك سوى تجاهل هذه الرسالة الإلكترونية..</p>
        <p> نور اكرم &#8226; <a href="{{route('index')}}">Noorakram.com</a></p>
    </div>
</div>
</body>

</html>