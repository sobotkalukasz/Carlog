@extends('layouts.base')


@section('head')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/add_edit.css') }}"/>
@stop


@section('body')

  @php
    $user = new \App\User;
    $cars = $user->getCars(session('id'))->toArray();

  @endphp

  <div class="carform">

    <h1>dodaj nowy wpis paliwowy</h1>

    <form action="{{ url('AddEditFuel') }}" method="post">

      {{-- CSRF Token.---------------------}}
      {!! csrf_field() !!}

      {{-- Select div - Car selection --}}

        <div class="inputdivs left">
            <h2>Wybierz pojazd</h2>

            <div class="select">
              <select id="car_id" name="car_id">
                @for ($i=0; $i < sizeof($cars); $i++)
                    @if ($cars[$i]['sale_date'] === NULL)
                        <option value ="{{ $cars[$i]['id'] }}">{{ $cars[$i]['make']." ".$cars[$i]['model']  }}</option>
                    @endif
                @endfor
              </select>
            </div>
            <div style="clear:both;"></div>
        </div>




        {{-- Radio - Fuel version  --}}

        <div class="inputdivs left">
            <h2>Rodzaj paliwa</h2>

            <div class="radioFuel">
                <lable><input type="radio" name="fuel" value="LPG">
                  <span>LPG</span></lable>
            </div>

            <div class="radioFuel">
                <lable><input type="radio" name="fuel" value="ON"><span>ON</span></lable>
            </div>

            <div class="radioFuel">
                <lable><input type="radio" name="fuel" value="PB" checked><span>PB</span></lable>
            </div>

            <div style="clear:both;"></div>
        </div>



        {{-- Input div- Purchase date --}}

          <div class="inputdivs left">
                <h2>Data tankowania</h2>
                <div class="right">
                    <input type="date" name="date"
                    @if (session()->has('date'))
                        value="{{ session('date') }}"
                        @php Session::forget('date') @endphp
                    @endif >
                </div>

                <div style="clear:both;"></div>

          </div>


          @if($errors->has('date'))
            <div class="errordivs">
              {{ $errors->first('date') . PHP_EOL }}
            </div>
          @endif




          {{-- Input and error div - litres --}}

          <div class="inputdivs left">
            <h2>Ilość litrów [l]</h2>
              <input type="text" id="litres" placeholder="Ilość litrów [l]" class="carinput small" name="litres"
                  @if (session()->has('litres'))
                      value="{{ session('litres') }}"
                      @php Session::forget('litres') @endphp
                  @endif >
              <div style="clear:both;"></div>
          </div>


          @if($errors->has('litres'))
            <div class="errordivs">
              {{ $errors->first('litres') }}
            </div>
          @endif




          {{-- Input and error div - price_all --}}

          <div class="inputdivs left">
            <h2>Koszt tankowania [zł]</h2>
              <input type="text" id="price_all" placeholder="Koszt tankowania [zł]" class="carinput small" name="price_all"
                  @if (session()->has('price_all'))
                      value="{{ session('price_all') }}"
                      @php Session::forget('price_all') @endphp
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('price_all'))
            <div class="errordivs">
              {{ $errors->first('price_all') }}
            </div>
          @endif



          {{-- Input and error div - price_l --}}

          <div class="inputdivs left">
            <h2>Cena za litr [zł]</h2>
              <input id="price_l" type="text" placeholder="Cena za litr [zł]" class="carinput small smaller" name="price_l"
                  @if (session()->has('price_l'))
                      value="{{ session('price_l') }}"
                      @php Session::forget('price_l') @endphp
                  @endif >
              <div class="calc"><i onclick="calculate()"class="icon-calc"></i></div>
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('price_l'))
            <div class="errordivs">
              {{ $errors->first('price_l') }}
            </div>
          @endif


          {{-- Input and error div - mileage_current --}}

          <div class="inputdivs left">
            <h2>Przebieg pojazdu [km]</h2>
              <input type="text" id="mileage_c" placeholder="Przebieg pojazdu [km]" class="carinput small" name="mileage_current"
                  @if (session()->has('mileage_current'))
                      value="{{ session('mileage_current') }}"
                      @php Session::forget('mileage_current') @endphp
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('mileage_current'))
            <div class="errordivs">
              {{ $errors->first('mileage_current') }}
            </div>
          @endif


          {{-- Input and error div - distance --}}

          <div class="inputdivs left">
            <h2>Pokonany dystans [km]</h2>
              <input id="distance" type="text" placeholder="Pokonany dystans [km]" class="carinput small smaller" name="distance"
                  @if (session()->has('distance'))
                      value="{{ session('distance') }}"
                      @php Session::forget('distance') @endphp
                  @endif >
              <div class="calc"><i onclick="avgCons()"class="icon-calc"></i></div>
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('distance'))
            <div class="errordivs">
              {{ $errors->first('distance') }}
            </div>
          @endif


          {{-- Input and error div - fuel_consumption --}}

          <div class="inputdivs left">
            <h2>Spalanie [l/100km]</h2>
              <input type="text" id="consumption" placeholder="Spalanie [l/100km]" class="carinput small" name="fuel_consumption"
                  @if (session()->has('fuel_consumption'))
                      value="{{ session('fuel_consumption') }}"
                      @php Session::forget('fuel_consumption') @endphp
                  @endif >
              <div style="clear:both;"></div>
          </div>


          @if($errors->has('fuel_consumption'))
            <div class="errordivs">
              {{ $errors->first('fuel_consumption') }}
            </div>
          @endif


          {{-- Submit button --}}

          <div class="submitdiv">
              <input type="submit" class="submit" value="dodaj wpis">
          </div>



        <div style="clear:both;"></div>
    </form>
  </div>

@stop

@section('script')
    <script>
      function calculate(){
        //gets values entered by user
        var price = document.getElementById("price_all");
        var litres = document.getElementById("litres");

        //changes every commas as dots and force values as Float Number
        price.value = parseFloat(price.value.replace(/,/g, "."));
        litres.value = parseFloat(litres.value.replace(/,/g, "."));

        document.getElementById("price_l").value = (price.value/litres.value).toFixed(2);
      }


      function avgCons(){
        var mileageCur = document.getElementById("mileage_c");
        var mileageAct = getMileage();

        var litres = document.getElementById("litres");
        litres.value = parseFloat(litres.value.replace(/,/g, "."));

        if (mileageCur.value > mileageAct){
          var distance = mileageCur.value - mileageAct;
          var consumption = ((litres.value/distance)*100).toFixed(2);
          document.getElementById("distance").value = distance;
          document.getElementById("consumption").value = consumption;
        }else{
          alert("Podany przebieg jest niższy od ostatniej zapisanej wartości!")
        }
      }


      function getMileage(){
        var select = document.getElementById('car_id');
        var car = select.options[select.selectedIndex].value;
        var cars = <?php echo json_encode($cars); ?>;

        for(var i=0;i<6;i++){
          if (cars[i]['id'] == car){
            return cars[i]['mileage_current'];
          }
        }
      }
    </script>
@stop
