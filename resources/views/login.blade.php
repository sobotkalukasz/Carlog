@extends('layouts.base')

@section('head')
    <link rel="stylesheet" type="text/css" href="css/index.css"/>
@stop

@section('body')


        <div id="loginform">
            <h1>zaloguj sie do dziennika pojazdów</h1>

          <form action="{{ url('LoginFormValidation') }}" method="post">

                {{-- CSRF Token.---------------------}}
                {{-- csrf_field() --}}

                <div id="loginerror">
                  @if($errors->has('login'))
                    {{ $errors->first('login') }}
                  @endif
                  @if (session()->has('error_login'))
                    {{ session('error_login') }}
                  @endif
                </div>

                <input type="text" placeholder="Adres e-mail" class="loginput" name="login"
                @if (session()->has('login'))
                    value="{{ session('login') }}"
                @endif ><br>

                <div id="loginerror">
                  @if($errors->has('password'))
                    {{ $errors->first('password') }}
                  @endif
                </div>

                <input type="password" placeholder="Hasło" class="loginput" name="password"><br>
                <div class="logsubmitdiv">
                    <input type="submit" id="logsubmit" value="Zaloguj">
                </div>
                <div class="clear:both;"></div>

            </form>
        </div>


        <div id="registerform">
            <br><h1>załóż konto</h1>
            <form action= "{{ url('RegisterFormValidation') }}"method="post">

                {{-- CSRF Token.---------------------}}
                {{-- csrf_field() --}}

                {{-- Error divs --}}
                <div class="regerrordivs">
                  @if($errors->has('name'))
                    {{ $errors->first('name') }}
                  @endif
                </div>

                <div class="regerrordivs">
                  @if($errors->has('email'))
                    {{ $errors->first('email') }}
                  @endif
                  @if (session()->has('error_register'))
                    {{ session('error_register') }}
                  @endif
                </div>

                <div style="clear:both;"></div>

                <div class="reginputdivs">
                    <input type="text" placeholder="Imię" class="reginput" name="name"
                        @if (session()->has('name'))
                            value="{{ session('name') }}"
                        @endif >
                </div>

                <div class="reginputdivs">
                    <input type="text" placeholder="Adres e-mail" class="reginput" name="email"
                      @if (session()->has('email'))
                        value="{{ session('email') }}"
                      @endif >
                </div>

                <div style="clear:both;"></div>

                {{-- Error divs --}}

                <div class="regerrordivs">
                  @if($errors->has('pass'))
                    {{ $errors->first('pass') }}
                  @endif
                </div>

                <div class="regerrordivs"></div>

                <div style="clear:both;"></div>


                <div class="reginputdivs">
                    <input type="password" placeholder="Hasło" class="reginput" name="pass">
                </div>

                <div class="reginputdivs">
                    <input type="password" placeholder="Powtórz hasło" class="reginput" name="pass_confirmation">
                </div>

                <div style="clear:both;"></div>


                <div class="regsubmitdiv">
                    <input type="submit" id="regsubmit" value="Załóż nowe konto">
                </div>

                <div class="clear:both;"></div>
            </form>
        </div>
@stop
