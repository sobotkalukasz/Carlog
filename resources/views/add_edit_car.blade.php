@extends('layouts.base')


@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/add_edit.css') }}"/>
@endsection


@section('body')


@php
  if(session()->has('car_id')){
    $car = new \App\Car;
    $car = $car->getCarById(session('car_id'));}

@endphp

<div class="carform">
    <h1>
      @if(session()->has('car_id'))
          edycja pojazdu
      @else
          dodaj nowy samochód
      @endif
    </h1>

    <form action="{{ url('AddEditCar') }}" method="post">

      {{-- CSRF Token.---------------------}}
      {!! csrf_field() !!}


      {{-- If editing existing fuel entry it creates hidden input field with it id --}}

      @if(session()->has('car_id'))
        <input type="hidden" name="car_id" value="{{ session('car_id') }}">
      @endif


      {{-- Input and error div - Make --}}

      <div class="inputdivs">
        <h2>Marka pojazdu</h2>
        <input type="text" placeholder="Marka pojazdu" class="carinput big" name="make"
              @if (session()->has('make'))
                  value="{{ session('make') }}"
                  @php Session::forget('make') @endphp
              @elseif (session()->has('car_id') && $car[0]->make)
                value="{{ $car[0]->make }}"
              @endif >
      </div>

      <div class="errordivs">
        @if($errors->has('make'))
          {{ $errors->first('make') }}
        @endif
      </div>



      {{-- Input and error div - Model --}}

      <div class="inputdivs">
        <h2>Model pojazdu</h2>
          <input type="text" placeholder="Model pojazdu" class="carinput big" name="model"
              @if (session()->has('model'))
                  value="{{ session('model') }}"
                  @php Session::forget('model') @endphp
                  @elseif (session()->has('car_id') && $car[0]->model)
                    value="{{ $car[0]->model }}"
              @endif >
      </div>

      <div class="errordivs">
        @if($errors->has('model'))
          {{ $errors->first('model') }}
        @endif
      </div>




      {{-- Input and error div - Year of production --}}

      <div class="inputdivs">
          <h2>Rok produkcji</h2>
          <input type="text" placeholder="Rok produkcji" class="carinput big" name="production_year"
              @if (session()->has('production_year'))
                  value="{{ session('production_year') }}"
                  @php Session::forget('production_year') @endphp
                  @elseif (session()->has('car_id') && $car[0]->production_year)
                    value="{{ $car[0]->production_year }}"
              @endif >
      </div>

      <div class="errordivs">
        @if($errors->has('production_year'))
          {{ $errors->first('production_year') }}
        @endif
      </div>



      {{-- Input and error div - Engine version --}}

      <div class="inputdivs">
        <h2>Wersja silnikowa</h2>
          <input type="text" placeholder="Wersja silnikowa" class="carinput big" name="engine"
              @if (session()->has('engine'))
                  value="{{ session('engine') }}"
                  @php Session::forget('engine') @endphp
              @elseif (session()->has('car_id') && ($car[0]->engine))
                    value="{{ $car[0]->engine }}"
              @endif >
      </div>

      <div class="errordivs">
        @if($errors->has('engine'))
          {{ $errors->first('engine') }}
        @endif
      </div>




      {{-- Input and error div - Horsepower  --}}

      <div class="inputdivs">
        <h2>Moc silnika [KM]</h2>
          <input type="text" placeholder="Moc silnika [KM]" class="carinput big" name="hp"
              @if (session()->has('hp'))
                  value="{{ session('hp') }}"
                  @php Session::forget('hp') @endphp
                  @elseif (session()->has('car_id') && $car[0]->hp)
                    value="{{ $car[0]->hp }}"
              @endif >
      </div>

      <div class="errordivs">
        @if($errors->has('hp'))
          {{ $errors->first('hp') }}
        @endif
      </div>


      {{-- Input and error div - Mileage_start  --}}

      <div class="inputdivs">
        <h2>Przebieg pojazdu - początkowy [km]</h2>
          <input type="text" placeholder="Przebieg pojazdu [km]" class="carinput big" name="mileage_start"
              @if (session()->has('mileage_start'))
                  value="{{ session('mileage_start') }}"
                  @php Session::forget('mileage_start') @endphp
                  @elseif (session()->has('car_id') && $car[0]->mileage_start)
                    value="{{ $car[0]->mileage_start }}"
              @endif >
      </div>

      <div class="errordivs">
        @if($errors->has('mileage_start'))
          {{ $errors->first('mileage_start') }}
        @endif
      </div>


      {{-- Input and error div - mileage_current  --}}

      <div class="inputdivs">
        <h2>Przebieg pojazdu - aktualny [km]</h2>
          <input type="text" placeholder="Przebieg pojazdu [km]" class="carinput big" name="mileage_current"
              @if (session()->has('mileage_current'))
                  value="{{ session('mileage_current') }}"
                  @php Session::forget('mileage_current') @endphp
              @elseif (session()->has('car_id') && $car[0]->mileage_current)
                  value="{{ $car[0]->mileage_current }}"
              @endif >
      </div>

      <div class="errordivs">
        @if($errors->has('mileage_current'))
          {{ $errors->first('mileage_current') }}
        @endif
      </div>



      {{-- Radio - Fuel version  --}}

      <div class="inputdivs">
        <h2 style="float:left;">Rodzaj paliwa</h2>

        <div class="radioFuel">
          <lable><input type="radio" name="fuel" value="LPG"
            @if(session()->has('car_id') && $car[0]->fuel == 'LPG')
              checked
            @endif
            ><span>LPG</span></lable>
        </div>

        <div class="radioFuel">
          <lable><input type="radio" name="fuel" value="ON"
            @if(session()->has('car_id') && $car[0]->fuel == 'ON')
              checked
            @endif
            ><span>ON</span></lable>
        </div>

        <div class="radioFuel">
          <lable><input type="radio" name="fuel" value="PB"
            @if(session()->has('car_id'))
              @if ($car[0]->fuel == 'PB')
                checked
              @endif
            @else
              checked
            @endif
            ><span>PB</span></lable>
        </div>

        <div style="clear:both;"></div>

      </div>



    {{-- Input divs - Purchase date and price --}}

      <div class="inputdivs">

            <div class="datediv">
                <h2>Data zakupu</h2>
                <input type="date" name="purchase_date"
                @if (session()->has('purchase_date'))
                    value="{{ session('purchase_date') }}"
                    @php Session::forget('purchase_date') @endphp
                @elseif (session()->has('car_id') && $car[0]->purchase_date)
                    value="{{ $car[0]->purchase_date }}"
                @endif >
            </div>

            <div class="pricediv">
                <h2>Cena zakupu [zł]</h2>
                <input type="text" placeholder="Cena [zł]" class="price" name="purchase_price"
                    @if (session()->has('purchase_price'))
                        value="{{ session('purchase_price') }}"
                        @php Session::forget('purchase_price') @endphp
                    @elseif (session()->has('car_id') && $car[0]->purchase_price)
                        value="{{ $car[0]->purchase_price }}"
                    @endif >
            </div>

            <div style="clear:both;"></div>

      </div>

      <div class="errordivs">
        @if($errors->has('purchase_date'))
          {{ $errors->first('purchase_date') . PHP_EOL }}
          <div style="clear:both;"></div>
        @endif

        @if($errors->has('purchase_price'))
          {{ $errors->first('purchase_price') }}
        @endif
      </div>



      {{-- Submit button --}}

      <div class="submitdiv">
          <input type="submit" class="submit"
              @if(session()->has('car_id'))
                value="zapisz zmiany"
              @else
                value="dodaj pojazd"
              @endif >
      </div>

      <div style="clear:both;"></div>

    </form>




    @if(session()->has('car_id'))

        <h1>sprzedaż pojazdu</h1>

        <form action="{{ url('SellCar') }}" method="post">

          {{-- CSRF Token.---------------------}}
          {!! csrf_field() !!}

          {{-- Input divs - Sell date and price --}}

            <div class="inputdivs">

                  <div class="datediv">
                      <h2>Data sprzedaży</h2>
                      <input type="date" name="sale_date"
                      @if (session()->has('sale_date'))
                          value="{{ session('sale_date') }}"
                          @php Session::forget('sale_date') @endphp
                      @elseif (session()->has('car_id') && $car[0]->sale_date)
                          value="{{ $car[0]->sale_date }}"
                      @endif >
                  </div>

                  <div class="pricediv">
                      <h2>Cena sprzedaży [zł]</h2>
                      <input type="text" placeholder="Cena [zł]" class="price" name="sale_price"
                          @if (session()->has('sale_price'))
                              value="{{ session('sale_price') }}"
                              @php Session::forget('sale_price') @endphp
                          @elseif (session()->has('car_id') && $car[0]->sale_price)
                              value="{{ $car[0]->sale_price }}"
                          @endif >
                  </div>

                  <div style="clear:both;"></div>

            </div>

            <div class="errordivs">
              @if($errors->has('sale_date'))
                {{ $errors->first('sale_date') . PHP_EOL }}
                <div style="clear:both;"></div>
              @endif

              @if($errors->has('sale_price'))
                {{ $errors->first('sale_price') }}
              @endif
            </div>


            {{-- Input and error div - mileage_current  --}}

            <div class="inputdivs">
              <h2>Przebieg pojazdu [km]</h2>
                <input type="text" placeholder="Przebieg pojazdu [km]" class="carinput big" name="mileage_current2"
                    @if (session()->has('mileage_current2'))
                        value="{{ session('mileage_current2') }}"
                        @php Session::forget('mileage_current2') @endphp
                    @elseif (session()->has('car_id') && $car[0]->sale_date != NULL)
                            value="{{ $car[0]->mileage_current }}"
                    @endif >
            </div>

            <div class="errordivs">
              @if($errors->has('mileage_current2'))
                {{ $errors->first('mileage_current2') }}
              @endif
            </div>


            {{-- Submit button --}}

            <div class="submitdiv">
                <input type="submit" class="submit"
                    @if($car[0]->sale_price)
                      value="zapisz zmiany"
                    @else
                      value="potwietdź sprzedaż"
                    @endif >
            </div>


            {{-- Deletes car_id from session() --}}

            @if(session()->has('car_id'))
              @php session()->forget('car_id') @endphp
            @endif

            <div style="clear:both;"></div>

        <form>
    @endif


</div>


@endsection
