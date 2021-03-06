@extends('layouts.base')


@section('head')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/add_edit.css') }}"/>
@stop


@section('body')


  <div class="carform">

    <h1>
      @if(isset($reminder))
        edycja przypomnienia
      @else
        dodaj nowe przypomnienie
      @endif
    </h1>


    <form name="reminder" action="{{ url('AddEditReminder') }}" method="post"
      onsubmit="return formValidator()">

      {{-- CSRF Token.---------------------}}
      {!! csrf_field() !!}


      {{-- If editing existing reminder entry it creates hidden input field with its id --}}

      @if(isset($reminder))
        <input type="hidden" name="reminder_id" value="{{ $reminder->id }}">
      @endif



      {{-- Select div - Car selection --}}

        <div class="inputdivs left">
            <h2>Wybierz pojazd</h2>

            <div class="select">
              <select id="car_id" name="car_id">
                @foreach ($cars as $car)
                  @if($car->sale_date === NULL)
                    <option value ="{{ $car->id }}"
                      @if (isset($reminder) && ($car->id == $reminder->car_id))
                        selected
                      @endif
                    >{{ $car->make." ".$car->model  }}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div style="clear:both;"></div>
        </div>



        {{-- Input div- Reminder date --}}

          <div class="inputdivs left">
                <h2>Data przypomnienia</h2>
                <div class="right">
                    <input type="date" name="reminder_date"
                    @if (session()->has('reminder_date'))
                        value="{{ session('reminder_date') }}"
                        @php Session::forget('reminder_date') @endphp
                    @elseif (isset($reminder) && $reminder->date)
                        value="{{ $reminder->date }}"
                    @endif >
                </div>

                <div style="clear:both;"></div>

          </div>

          @if($errors->has('reminder_date'))
            <div class="errordivs">
              {{ $errors->first('reminder_date') . PHP_EOL }}
            </div>
          @endif





          {{-- Input and error div - Reminder mileage--}}

          <div class="inputdivs left">
            <h2>Przebieg pojazdu [km]</h2>
              <input type="text" placeholder="Przebieg pojazdu [km]" class="carinput small" name="reminder_mileage"
                  @if (session()->has('reminder_mileage'))
                      value="{{ session('reminder_mileage') }}"
                      @php Session::forget('reminder_mileage') @endphp
                  @elseif (isset($reminder) && $reminder->mileage)
                      value="{{ $reminder->mileage }}"
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('reminder_mileage'))
            <div class="errordivs">
              {{ $errors->first('reminder_mileage') }}
              </div>
          @endif




          {{-- Input and error div - reminder_comment --}}

          <div class="inputdivs">
            <h2>Treść przypomnienia</h2>
            <textarea placeholder="Wpisz treść przypomnienia" name="reminder_comment"
                rows="5" class="carinput" id="r_comment"></textarea>
          </div>


          {{-- hidden input for JQuery function --}}

          <input type="hidden" id="text"
          @if (session()->has('reminder_comment'))
              {{  'value=1' }}
          @elseif (isset($reminder) && $reminder->comment)
              {{  'value=1' }}
              @php session()->put('reminder_comment', $reminder->comment); @endphp
          @endif>



          @if($errors->has('reminder_comment'))
            <div class="errordivs">
              {{ $errors->first('reminder_comment') }}
            </div>
          @endif

          <div style="clear:both;"></div>





        {{-- Submit button --}}

          <div class="submitdiv">
              <input type="submit" class="submit"
              @if(isset($reminder))
                value="zapisz zmiany"
              @else
                value="dodaj przypomnienie"
              @endif >
          </div>


        <div style="clear:both;"></div>
    </form>
  </div>

@stop

@section('script')
    <script>
        function formValidator() {
          var date = document.forms["reminder"]["reminder_date"].value;
          var mileage = document.forms["reminder"]["reminder_mileage"].value;
          var mileage_last = getMileage();

          if (date =="" && mileage ==""){
            alert("Należy podać datę lub stan licznika przypomnienia!");
            return false;
          }

          if(mileage !="" && mileage < mileage_last){
            alert('Podany przebieg jest niższy od aktualnego przebiegu pojazdu: ' + mileage_last + '!');
            return false;
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


        //if value == 1 it fills #r_comment and unset session value

        if($("#text").val() == 1){
          var text = <?php echo json_encode(session('reminder_comment')); ?>;
          var field = '#r_comment';
          $(field).val(text);
          <?php Session::forget('reminder_comment') ?>;
        }

    </script>
@stop
