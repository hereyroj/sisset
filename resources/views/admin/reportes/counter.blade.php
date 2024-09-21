<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <style type="text/css">
            .box {
                background-color: #337ab7;
                border-color: #337ab7;
                color: #fff;
                display: flex;
                justify-content: flex-start;
                align-items: center;
                width: 100%;
            }

            .box div {
                max-width: 100%;
                padding: 15px;
            }

            .title-box {
                font-size: 26px;
            }

            .number-box {
                background-color: #649dcd;
                flex: 1 1 auto;
                font-size: 50px;
                text-align: center;
            }
        </style>
    </head>

    <body>
        <div class="box">
            <div class="title-box">
                {{ $title }}
            </div>
            <div class="number-box">
                {{ $number }}
            </div>
        </div>
    </body>
</html>