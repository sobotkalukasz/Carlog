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

    <h1>dodaj nowy wydatek</h1>

    <form name="expense" action="{{ url('AddEditExpense') }}" method="post"
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



        {{-- Input div - expense_date --}}

          <div class="inputdivs left">
                <h2>Data wydatku</h2>
                <div class="right">
                    <input type="date" name="expense_date"
                    @if (session()->has('expense_date'))
                        value="{{ session('expense_date') }}"
                        @php Session::forget('expense_date') @endphp
                    @endif >
                </div>

                <div style="clear:both;"></div>

          </div>

          @if($errors->has('expense_date'))
            <div class="errordivs">
              {{ $errors->first('expense_date') . PHP_EOL }}
            </div>
          @endif




          {{-- Input and error div - expense_description --}}

          <div class="inputdivs left">
            <h2>Krótki opis</h2>
              <input type="text" placeholder="Krótki opis" class="carinput small" name="expense_description"
                  @if (session()->has('expense_description'))
                      value="{{ session('expense_description') }}"
                      @php Session::forget('expense_description') @endphp
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('expense_description'))
            <div class="errordivs">
              {{ $errors->first('expense_description') }}
            </div>
          @endif




          {{-- Input and error div - expense_price --}}

          <div class="inputdivs left">
            <h2>Kwota wydatku [zł]</h2>
              <input type="text" placeholder="Kwota wydatku [zł]" class="carinput small" name="expense_price"
                  @if (session()->has('expense_price'))
                      value="{{ session('expense_price') }}"
                      @php Session::forget('expense_prica') @endphp
                  @endif >
              <div style="clear:both;"></div>
          </div>

          @if($errors->has('expense_price'))
            <div class="errordivs">
              {{ $errors->first('expense_price') }}
            </div>
          @endif




          {{-- Input and error div - expense_comment --}}

          <div class="inputdivs">
            <h2>Uwagi</h2>
            <textarea placeholder="Pole opcjonalne" name="expense_comment"
                rows="5" class="carinput"></textarea>
          </div>

          @if($errors->has('expense_comment'))
            <div class="errordivs">
              {{ $errors->first('expense_comment') . PHP_EOL }}
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

            <div class="inputdivs left">
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

            <div class="inputdivs left">
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

            <div class="inputdivs">
              <h2>Treść przypomnienia</h2>
              <textarea id="r_comment" placeholder="Wpisz treść przypomnienia" name="reminder_comment"
                  rows="5" class="carinput"></textarea>
            </div>

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

          var checkbox = document.getElementById('check');

          function checkboxFunction(){
            if(checkbox.checked){
                document.getElementById("sub").value = 'dodaj z przypomnieniem';
                document.getElementById("r_date").disabled = false;
                document.getElementById("r_mileage").disabled = false;
                document.getElementById("r_comment").disabled = false;
            }
            else{
              document.getElementById("sub").value = 'zapisz wydatek';
              document.getElementById("r_date").disabled = true;
              document.getElementById("r_mileage").disabled = true;
              document.getElementById("r_comment").disabled = true;
            }
          };

          window.onload = checkboxFunction;

          checkbox.addEventListener('change', function(){
            checkboxFunction();
          });




        function formValidator() {
          var date = document.forms["expense"]["reminder_date"].value;
          var mileage = document.forms["expense"]["reminder_mileage"].value;
          var mileage_last = getMileage();

          if(checkbox.checked){
              if (date =="" && mileage ==""){
                alert("Należy podać datę lub stan licznika przypomnienia!");
                return false;
              }

              if(mileage !="" && mileage < mileage_last){
                alert('Podany przebieg przypomienia jest niższy od aktualnego przebiegu pojazdu: ' + mileage_last + '.');
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



    </script>
@stop
