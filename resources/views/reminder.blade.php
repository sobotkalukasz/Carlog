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

    <h1>dodaj nowe przypomnienie</h1>

    <form name="reminder" action="{{ url('AddEditReminder') }}" method="post"
      onsubmit="return formValidator()">

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



        {{-- Input div- Reminder date --}}

          <div class="inputdivs left">
                <h2>Data przypomnienia</h2>
                <div class="right">
                    <input type="date" name="reminder_date"
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





          {{-- Input and error div - Reminder mileage--}}

          <div class="inputdivs left">
            <h2>Przebieg pojazdu [km]</h2>
              <input type="text" placeholder="Przebieg pojazdu [km]" class="carinput small" name="reminder_mileage"
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

          <div class="inputdivs">
            <h2>Treść przypomnienia</h2>
            <textarea placeholder="Wpisz treść przypomnienia" name="reminder_comment"
                rows="5" class="carinput"
                  @if (session()->has('reminder_comment'))
                      value="{{ session('reminder_comment') }}"
                      @php Session::forget('reminder_comment') @endphp
                  @endif ></textarea>
          </div>

          <div style="clear:both;"></div>





        {{-- Submit button --}}

          <div class="submitdiv">
              <input type="submit" class="submit" value="zapisz przypomnienie">
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

    </script>
@stop
