<!doctype html>
<html lang ="pl">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title>Carlog</title>

    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">

@yield('head')

  </head>
  <body>
    <div class="container">

        <div class="header">
            <div class="logo">
                <img src="images/pixelcar_logo.png" style="float:left;" alt="logo">
                <div id="logo_text"><span style="color: #e98700; font-weight:700;">Car</span>log</div>
                <div style="clear:both;"></div>

            </div>
        </div>

        <div class="nav">
            here will be navigation buttons
        </div>

@yield('body')

        <div class="footer">
            here will be my footer
        </div>

    </div>
  </body>
</html>
