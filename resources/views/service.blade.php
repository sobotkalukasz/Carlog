@extends('layouts.base')


@section('head')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/add_edit.css') }}"/>
@stop


@section('body')


  <div class="carform">

    <h1>
      @if(isset($service))
        edycja wpisu serwisowego
      @else
        dodaj nowy wydatek serwisowy
      @endif
    </h1>

    <form name="service" action="{{ url('AddEditService') }}" method="post"
      onsubmit="return formValidator()">

      {{-- CSRF Token.---------------------}}
      {!! csrf_field() !!}



      {{-- If editing existing fuel entry it creates hidden input field with its id --}}

      @if(isset($service))
        <input type="hidden" name="service_id" value="{{ $service->id }}">
      @endif




      {{-- Select div - Car selection --}}

        <div class="inputdivs left">
            <h2>Wybierz pojazd</h2>

            <div class="select">
              <select id="car_id" name="car_id">
                @foreach ($cars as $car)
                  @if($car->sale_date === NULL)
                    <option value ="{{ $car->id }}"
                      @if (isset($service) && ($car->id == $service->car_id))
                        selected
                      @endif
                    >{{ $car->make." ".$car->model  }}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div style="clear:both;"></div>
        </div>



        {{-- Input div- service_date --}}

          <div class="inputdivs left">
                <h2>Data serwisu</h2>
                <div class="right">
                    <input type="date" name="service_date"
                    @if (session()->has('service_date'))
                        value="{{ session('service_date') }}"
                        @php Session::forget('service_date') @endphp
                    @elseif (isset($service) && $service->date)
                        value="{{ $service->date }}"
                    @endif >
                </div>

                <div style="clear:both;"></div>
          </div>

          @if($errors->has('service_date'))
            <div class="errordivs">
              {{ $errors->first('service_date') . PHP_EOL }}
            </div>
          @endif




          {{-- Input and error div - service_mileage --}}

          <div class="inputdivs left">
            <h2>Przebieg pojazdu [km]</h2>
              <input type="text" placeholder="Przebieg pojazdu [km]" class="carinput small" name="service_mileage"
                  @if (session()->has('service_mileage'))
                      value="{{ session('service_mileage') }}"
                      @php Session::forget('service_mileage') @endphp
                  @elseif (isset($service) && $service->mileage)
                      value="{{ $service->mileage }}"
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('service_mileage'))
            <div class="errordivs">
              {{ $errors->first('service_mileage') }}
            </div>
          @endif




          {{-- Input and error div - service_description --}}

          <div class="inputdivs left">
            <h2>Krótki opis</h2>
              <input type="text" placeholder="Krótki opis" class="carinput small" name="service_description"
                  @if (session()->has('service_description'))
                      value="{{ session('service_description') }}"
                      @php Session::forget('service_description') @endphp
                  @elseif (isset($service) && $service->description)
                      value="{{ $service->description }}"
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('service_description'))
            <div class="errordivs">
              {{ $errors->first('service_description') }}
            </div>
          @endif



          {{-- Input and error div - service_price_parts --}}

          <div class="inputdivs left">
            <h2>Koszt części [zł]</h2>
              <input type="text" id="price_parts" placeholder="Koszt części [zł]" class="carinput small" name="service_price_parts"
                  @if (session()->has('service_price_parts'))
                      value="{{ session('service_price_parts') }}"
                      @php Session::forget('service_price_parts') @endphp
                  @elseif (isset($service) && $service->price_parts)
                      value="{{ $service->price_parts }}"
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('service_price_parts'))
            <div class="errordivs">
              {{ $errors->first('service_price_parts') }}
            </div>
          @endif



          {{-- Input and error div - service_price_labour --}}

          <div class="inputdivs left">
            <h2>Koszt usługi [zł]</h2>
              <input type="text" id="price_labour" placeholder="Koszt usługi [zł]" class="carinput small" name="service_price_labour"
                  @if (session()->has('service_price_labour'))
                      value="{{ session('service_price_labour') }}"
                      @php Session::forget('service_price_labour') @endphp
                  @elseif (isset($service) && $service->price_labour)
                      value="{{ $service->price_labour }}"
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('service_price_labour'))
            <div class="errordivs">
              {{ $errors->first('service_price_labour') }}
            </div>
          @endif



          {{-- Input and error div - service_price_total --}}

          <div class="inputdivs left">
            <h2>Koszt całkowity [zł]</h2>
              <input id="price_total" type="text" placeholder="Koszt całkowity [zł]" class="carinput small smaller" name="service_price_total"
                  @if (session()->has('service_price_total'))
                      value="{{ session('service_price_total') }}"
                      @php Session::forget('service_price_total') @endphp
                  @elseif (isset($service) && $service->price_total)
                      value="{{ $service->price_total }}"
                  @endif >
              <div class="calc"><i onclick="calculate()"class="icon-calc"></i></div>
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('service_price_total'))
            <div class="errordivs">
              {{ $errors->first('service_price_total') }}
            </div>
          @endif



          {{-- Input and error div - service_comment --}}

          <div class="inputdivs">
            <h2>Uwagi</h2>
            <textarea id="service_comment" placeholder="Pole opcjonalne" name="service_comment"
                rows="5" class="carinput"></textarea>
          </div>

          {{-- hidden input for JQuery function --}}

          <input type="hidden" id="text1"
          @if (session()->has('service_comment'))
              {{  'value=1' }}
          @elseif (isset($service) && $service->comment)
              {{  'value=1' }}
              @php session()->put('service_comment', $service->comment); @endphp
          @endif>



          @if($errors->has('service_comment'))
            <div class="errordivs">
              {{ $errors->first('service_comment') }}
            </div>
          @endif


          {{-- Checkbox div - reminder --}}

          <div class="inputdivs left">
            <h2>Dodać przypomnienie?</h2>
            <input type="checkbox" id="check" name="reminder"
                @if (session()->has('reminder'))
                  @if (session('reminder') == 'on')
                    checked
                  @endif
                  @php Session::forget('reminder') @endphp
                @endif>
          </div>



          {{-- Input div- reminder_date --}}

            <div class="inputdivs left hide">
                  <h2>Data przypomnienia</h2>
                  <div class="right">
                      <input type="date" id="r_date" name="reminder_date"
                      @if (session()->has('reminder_date'))
                          value="{{ session('reminder_date') }}"
                          @php Session::forget('reminder_date') @endphp
                      @endif >
                  </div>
                  <div style="clear:both;"></div>
            </div>


            @if($errors->has('reminder_date'))
              <div class="errordivs">
                {{ $errors->first('reminder_date') . PHP_EOL }}
              </div>
            @endif




            {{-- Input and error div - reminder_mileage--}}

            <div class="inputdivs left hide">
              <h2>Przebieg pojazdu [km]</h2>
                <input type="text" id="r_mileage" placeholder="Przebieg pojazdu [km]" class="carinput small" name="reminder_mileage"
                    @if (session()->has('reminder_mileage'))
                        value="{{ session('reminder_mileage') }}"
                        @php Session::forget('reminder_mileage') @endphp
                    @endif >
                <div style="clear:both;"></div>
            </div>

            @if($errors->has('reminder_mileage'))
              <div class="errordivs">
                {{ $errors->first('reminder_mileage') }}
              </div>
            @endif




            {{-- Input and error div - reminder_comment --}}

            <div class="inputdivs hide">
              <h2>Treść przypomnienia</h2>
              <textarea placeholder="Wpisz treść przypomnienia" id="r_comment" name="reminder_comment"
                  rows="5" class="carinput"></textarea>
            </div>

            {{-- hidden input for JQuery function --}}

            <input type="hidden" id="text2"
            @if (session()->has('reminder_comment'))
                {{  'value=1' }}
            @endif>


            @if($errors->has('reminder_comment'))
              <div class="errordivs">
                {{ $errors->first('reminder_comment') . PHP_EOL }}
              </div>
            @endif



        {{-- Submit button --}}

          <div class="submitdiv">
              <input type="submit" class="submit" id="sub" value="">
          </div>



        <div style="clear:both;"></div>
    </form>
  </div>

@stop

@section('script')
    <script>


        function checkboxFunction(){
          if($('#check').prop("checked")){
              $("#sub").val('dodaj z przypomnieniem');
              $(".hide").show(200);
          }else{
            $("#sub").val('dodaj wydatek');
            $(".hide").hide(200);
          }
        };

        $(document).ready(checkboxFunction);

        $('#check').on('change', function(){
          checkboxFunction();
        });


        function calculate(){

          //gets values entered by user
          var price_parts = document.getElementById("price_parts");
          var price_labour = document.getElementById("price_labour");


          //changes every commas as dots and force values as Float Number
          price_parts.value = parseFloat(price_parts.value.replace(/,/g, "."));
          price_labour.value = parseFloat(price_labour.value.replace(/,/g, "."));

          document.getElementById("price_total").value = Number(price_parts.value)+Number(price_labour.value);
        }

        $("#price_total").focus(function(){calculate()});



        function formValidator() {
          var date = document.forms["service"]["reminder_date"].value;
          var mileage = document.forms["service"]["reminder_mileage"].value;
          var mileage_last = getMileage();

          if($('#check').prop("checked")){
              if (date =="" && mileage ==""){
                alert("Należy podać datę lub stan licznika przypomnienia!");
                return false;
              }

              if(mileage !="" && mileage < mileage_last){
                alert('Podany przebieg przypomienia jest niższy od aktualnego przebiegu pojazdu: ' + mileage_last + ' km.');
                return false;
              }
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


        //if value == 1 it fills #service_comment and unset session value

        if($("#text1").val() == 1){
          var text = <?php echo json_encode(session('service_comment')); ?>;
          var field = '#service_comment';
          $(field).val(text);
          <?php Session::forget('service_comment') ?>;
        }


        //if value == 1 it fills #r_comment and unset session value

        if($("#text2").val() == 1){
          var text = <?php echo json_encode(session('reminder_comment')); ?>;
          var field = '#r_comment';
          $(field).val(text);
          <?php Session::forget('reminder_comment') ?>;
        }







    </script>
@stop
