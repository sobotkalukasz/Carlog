@extends('layouts.base')

@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}"/>
@endsection

@section('body')


        <div id="loginform">
            <h1>zaloguj sie do dziennika pojazdów</h1>

            <form action="{{ url('LoginFormValidation') }}" method="post">

                {{-- CSRF Token.---------------------}}
                {{-- csrf_field() --}}

                <div class="loginerror">
                  @if($errors->has('login'))
                    {{ $errors->first('login') }}
                  @endif
                  @if (session()->has('error_login'))
                    {{ session('error_login') }}
                    @php Session::forget('error_login') @endphp
                  @endif
                </div>

                <input type="text" placeholder="Adres e-mail" class="loginput" name="login"
                @if (session()->has('login'))
                    value="{{ session('login') }}"
                    @php Session::forget('login') @endphp
                @endif ><br>

                <div class="loginerror">
                  @if($errors->has('password'))
                    {{ $errors->first('password') }}
                  @endif
                </div>

                <input type="password" placeholder="Hasło" class="loginput" name="password"><br>
                <div class="submitdiv">
                    <input type="submit" class="log" value="Zaloguj">
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

                @if($errors->has('name'))
                  <div class="regerrordivs">
                    {{ $errors->first('name') }}
                  </div>
                @endif


                <div class="regerrordivs">
                  @if($errors->has('email'))
                    {{ $errors->first('email') }}
                  @endif
                  @if (session()->has('error_register'))
                    {{ session('error_register') }}
                    @php Session::forget('error_register') @endphp
                  @endif
                </div>

                <div style="clear:both;"></div>

                <div class="reginputdivs">
                    <input type="text" placeholder="Imię" class="reginput" name="name"
                        @if (session()->has('name'))
                            value="{{ session('name') }}"
                            @php Session::forget('name') @endphp
                        @endif >
                </div>

                <div class="reginputdivs">
                    <input type="text" placeholder="Adres e-mail" class="reginput" name="email"
                      @if (session()->has('email'))
                        value="{{ session('email') }}"
                        @php Session::forget('email') @endphp
                      @endif >
                </div>

                <div style="clear:both;"></div>

                {{-- Error divs --}}

                @if($errors->has('pass'))
                  <div class="regerrordivs">
                    {{ $errors->first('pass') }}
                  </div>
                @endif


                <div class="regerrordivs"></div>

                <div style="clear:both;"></div>


                <div class="reginputdivs">
                    <input type="password" placeholder="Hasło" class="reginput" name="pass">
                </div>

                <div class="reginputdivs">
                    <input type="password" placeholder="Powtórz hasło" class="reginput" name="pass_confirmation">
                </div>

                <div style="clear:both;"></div>


                <div class="submitdiv">
                    <input type="submit" class="reg" value="Załóż nowe konto">
                </div>

                <div class="clear:both;"></div>
            </form>
        </div>


        </body>
@endsection
