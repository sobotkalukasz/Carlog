@extends('layouts.base')


@section('head')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/info.css') }}"/>
@stop


@section('body')


  @php

    /********************
    * Get car by Car id *
    ********************/

    $car = \App\Car::getCarById(session('car_id'))->toArray();
    //return var_dump($car);

    $costs = array(
        "total" => $car[0]['spendings_fuel'] + $car[0]['spendings_service'] +$car[0]['spendings_others'],
        "total_car" => $car[0]['spendings_fuel'] + $car[0]['spendings_service']
        + $car[0]['spendings_others'] + $car[0]['purchase_price'] - $car[0]['sale_price']
    );

    //return var_dump($costs)




    /**********************************
    * Get all fuel expenses by Car id *
    **********************************/

    $fuel = \App\Fuel_expense::whereCar_id(session('car_id'))
                              ->orderBy('date', 'desc')
                              ->get()->toArray();
    $fuel_size = sizeof($fuel);

    //return var_dump($fuel);




    /*****************************
    * Get all services by Car id *
    *****************************/

    $service = \App\Service::whereCar_id(session('car_id'))
                            ->orderBy('date', 'desc')
                            ->get()->toArray();
    $service_size = sizeof($service);

    //return var_dump($service);





    /***********************************
    * Get all other expenses by Car id *
    ***********************************/

    $expense = \App\Expense::whereCar_id(session('car_id'))
                            ->orderBy('date', 'desc')
                            ->get()->toArray();
    $expense_size = sizeof($expense);

    //return var_dump($expense);





    /***********************************
    * Get all fuel reminders by Car id *
    ***********************************/


    $reminder = \App\Reminder::currentReminders($car[0]['mileage_current']);
    $reminder_size = sizeof($reminder);

    usort($reminder, 'sort_by_date');

    //$reminderByMileage = $reminder;
    //usort($reminderByMileage, 'sort_by_mileage');

    //$sortByMil = array();

    /*for ($i = 0; $i < $reminder_size; $i++){
    *  for ($j = 0; $j < $reminder_size; $j++){
    *    if ($reminder[$i]['id'] == $reminderByMileage[$j]['id']){
    *      array_push($sortByMil, $j);
    *    }
    *  }
    *}
    */

    function sort_by_mileage ($a, $b){
      if ($a['mileage'] == '' && $b['mileage'] != '') return 1;
      if ($b['mileage'] == '' && $a['mileage'] != '') return -1;
      return $a['mileage'] - $b['mileage'];
    }

    function sort_by_date ($a, $b){
      if ($a['date'] == '' && $b['date'] != '') return 1;
      if ($b['date'] == '' && $a['date'] != '') return -1;
      $t1 = strtotime($a['date']);
      $t2 = strtotime($b['date']);
      return $t1 - $t2;
    }


    //return var_dump($reminder);
    //return var_dump($reminderByMileage);
    //return var_dump($sortByMil);






    /*******************************
    * My very usefull functions :) *
    *******************************/

    /*********************
    * Thousand separator *
    **********************/

    function withSpace($number, $i=0){
      return number_format($number, $i, '.', ' ');
    }


    /**********************************************
    * Returns diffrence between two dates in days *
    **********************************************/

    function howManyDays($date){
      $diff = date_diff(date_create($date), date_create(date('Y-m-d')));
      return $diff->days;
    }



  @endphp

  <div id="carsection">

    {{-- Section with info about car and total costs --}}

    <div class="carinfo">
        <h1>{{ $car[0]['production_year']." ".$car[0]['make']." ".$car[0]['model']." "
          .$car[0]['engine']." ".$car[0]['hp']."hp"  }}</h1>
        <div class="info" id="columnOne">
          <table>
              <thead>
                <tr>
                  <th colspan="2">Podstawowe dane</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>średnie spalanie</td>
                    <td>@if ($car[0]['fuel_avg_consumption'] != NULL)
                          {{ $car[0]['fuel_avg_consumption'] }}
                        @else
                          0
                        @endif l/100km</td>
                  </tr>
                  <tr>
                    <td>pokonany dystans</td>
                    <td>@if ($car[0]['fuel_mileage'] != NULL)
                          {{ withSpace($car[0]['fuel_mileage']) }}
                        @else
                          0
                        @endif km</td>
                  </tr>
                  <tr>
                    <td>koszt zakupu</td>
                    <td>{{ withSpace($car[0]['purchase_price'], 2) }} zł</td>
                  </tr>
                    @if ($car[0]['sale_price'] !== NULL)
                      <tr>
                        <td>przychód ze sprzedaży</td>
                        <td>{{ withSpace($car[0]['sale_price'], 2) }} zł</td>
                      </tr>
                    @endif
                </tbody>
          </table>
          <table>
              <thead>
                  <tr>
                    <th colspan="2">koszt całkowity z uwzględnieme zakupu i sprzedaży pojazdu</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>koszt bezwzględny</td>
                    <td>{{ withSpace($costs['total_car'], 2) }} zł</td>
                  </tr>
                  <tr>
                    <td>koszt całkowity na 100km </td>
                    <td>@if ($car[0]['fuel_mileage'] !=0)
                          {{ withSpace(round($costs['total_car']/$car[0]['fuel_mileage']*100, 2), 2) }}
                        @else
                          0
                        @endif zł</td>
                  </tr>
              </tbody>
          </table>

        </div>

        <div class="info" id="columnTwo">

            <table>
              <thead>
                  <tr>
                    <th>Wydatki ogółem</th>
                    <th>{{ withSpace($costs['total'], 2) }} zł</th>
                  </tr>
              </thead>
                <tbody>
                  <tr>
                    <td>koszt paliwa</td>
                    <td>@if ($car[0]['spendings_fuel'] != NULL)
                          {{ withSpace($car[0]['spendings_fuel'], 2) }}
                        @else
                          0
                        @endif zł</td>
                  </tr>
                  <tr>
                    <td>koszt serwisu</td>
                    <td>@if ($car[0]['spendings_service'] != NULL)
                          {{ withSpace($car[0]['spendings_service'], 2) }}
                        @else
                          0
                        @endif zł</td>
                  </tr>
                  <tr>
                    <td>inne wydatki</td>
                    <td>@if ($car[0]['spendings_others'] != NULL)
                          {{ withSpace($car[0]['spendings_others'], 2) }}
                        @else
                          0
                        @endif zł</td>
                  </tr>
                </tbody>
              </table>

              <table>
                <thead>
                  <tr>
                    <th>Średni koszt 100 km</th>
                    <th>@if ($car[0]['fuel_mileage'] !=0)
                          {{ withSpace(round($costs['total']/$car[0]['fuel_mileage']*100, 2), 2) }}
                        @else
                          0
                        @endif zł</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>koszt paliwa</td>
                    <td>@if ($car[0]['spendings_fuel'] != NULL && $car[0]['fuel_mileage'] !=0)
                          {{ withSpace(round($car[0]['spendings_fuel']/$car[0]['fuel_mileage']*100, 2), 2) }}
                        @else
                          0
                        @endif zł</td>
                  </tr>
                  <tr>
                    <td>koszt serwisu</td>
                    <td>@if ($car[0]['spendings_service'] != NULL && $car[0]['fuel_mileage'] !=0)
                          {{ withSpace(round($car[0]['spendings_service']/$car[0]['fuel_mileage']*100, 2), 2) }}
                        @else
                          0
                        @endif zł</td>
                  </tr>
                  <tr>
                    <td>inne wydatki</td>
                    <td>@if ($car[0]['spendings_others'] != NULL && $car[0]['fuel_mileage'] !=0)
                          {{ withSpace(round($car[0]['spendings_others']/$car[0]['fuel_mileage']*100, 2), 2) }}
                        @else
                          0
                        @endif zł</td>
                  </tr>
                </tbody>
              </table>
            </div>
    </div>

    <div style="clear:both;"></div>


    {{-- Section with reminders --}}

    <div class="carinfo">

      @if ($reminder_size > 0)
        <h2>Przypomnienia</h2>
        <div class="entries">

          <div class="title">
            <div class="eDate left">Data</div>
            <div class="eMileage left">Przebieg (km)</div>
            <div class="eReminderComment left">Treść przypomnienia</div>
            <div class="eEdit left"></div>
            <div style="clear:both;"></div>
            <input type="hidden" id="reminderSize" value="{{ $reminder_size }}">
          </div>

          @for ($i=0; $i<$reminder_size; $i++)
            @if ($i >= 10)
                @if($i == $reminder_size-1)
                  <div class="entry hide last" id="r{{ $i }}">
                @else
                  <div class="entry hide" id="r{{ $i }}">
                @endif
            @else
                @if($i == $reminder_size-1)
                  <div class="entry last" id="r{{ $i }}">
                @elseif ($i == 9)
                  <div class="entry last" id="r{{ $i }}">
                @else
                  <div class="entry" id="r{{ $i }}">
                @endif
            @endif

                  @if ($reminder[$i]['date'] == NULL)
                      <div class="eDate left">-</div>
                  @else
                      <div class="eDate left"
                      data-tooltip="Pozostało {{ howManyDays($reminder[$i]['date']) }} dni.">{{ $reminder[$i]['date'] }}</div>
                  @endif

                  @if ($reminder[$i]['mileage'] == NULL)
                      <div class="eMileage left">-</div>
                  @else
                      <div class="eMileage left"
                        data-tooltip="Pozostało do przejechania {{ withSpace($reminder[$i]['mileage']-$car[0]['mileage_current']) }} km.">{{ withSpace($reminder[$i]['mileage']) }}</div>
                  @endif

                  @if (strlen($reminder[$i]['comment']) > 85)
                    <div class="eReminderComment left"
                      data-tooltip="{{ $reminder[$i]['comment'] }}">
                      {{ mb_substr($reminder[$i]['comment'], 0, 85)."..." }}</div>
                  @else
                    <div class="eReminderComment left">{{ $reminder[$i]['comment'] }}</div>
                  @endif

                    <div class="eEdit left">
                      <div class="edit">
                        <a href="{{ route('edit.reminder', $reminder[$i]['id']) }}" data-tooltip="Edytuj przypomnienie" class="careditlink">
                          <i class="icon-pencil"></i>
                        </a>
                      </div>
                      <div class="edit">
                        <a href="{{ route('delete.reminder', $reminder[$i]['id']) }}" data-tooltip="Usuń przypomnienie" class="careditlink"
                          onclick="return confirm('Jesteś pewien, że chcesz usunąć to przypomnienie');">
                          <i class="icon-trash-empty"></i>
                        </a>
                      </div>

                </div>
                <div style="clear:both;"></div>
              </div>
            @if($i == $reminder_size-1 && $reminder_size >10)
              <div class="button">
                  <button type="button" id="reminder_button">pokaż wszystkie</button>
              </div>
              <div style="clear:both;"></div>
            @endif
          @endfor

        </div>
      @endif

    </div>




    {{-- Section with fuel costs --}}

    <div class="carinfo">

      @if ($fuel_size > 0)
        <h2>Wpisy paliwowe</h2>
        <div class="entries">

          <div class="title">
            <div class="eDate left">Data</div>
            <div class="eMileage left">Przebieg (km)</div>
            <div class="eFuelDistance left"> Dystans (km)</div>
            <div class="eFuelPrice left">Kwota</div>
            <div class="eFuelDetails left">Szczegóły</div>
            <div class="eFuelCons left">Spalanie</div>
            <div class="eEdit left"></div>
            <div style="clear:both;"></div>
            <input type="hidden" id="fuelSize" value="{{ $fuel_size }}">
          </div>

          @for ($i=0; $i<$fuel_size; $i++)
            @if ($i >= 10)
                @if($i == $fuel_size-1)
                  <div class="entry hide last" id="f{{ $i }}">
                @else
                  <div class="entry hide" id="f{{ $i }}">
                @endif
            @else
                @if($i == $fuel_size-1)
                  <div class="entry last" id="f{{ $i }}">
                @elseif ($i == 9)
                  <div class="entry last" id="f{{ $i }}">
                @else
                  <div class="entry" id="f{{ $i }}">
                @endif
            @endif
                <div class="eDate left">{{ $fuel[$i]['date'] }}</div>
                <div class="eMileage left">{{ withSpace($fuel[$i]['mileage_current']) }}</div>
                <div class="eFuelDistance left">{{ number_format($fuel[$i]['distance'], 0, ',', ' ') }}</div>
                <div class="eFuelPrice left">{{ $fuel[$i]['price_all'] }} zł</div>
                <div class="eFuelDetails left">{{ $fuel[$i]['litres']. "l x ".$fuel[$i]['price_l']." zł"  }}</div>
                <div class="eFuelCons left">{{ $fuel[$i]['fuel_consumption'] }}</div>
                <div class="eEdit left">
                  <div class="edit">
                    <a href="{{ route('edit.fuel', $fuel[$i]['id']) }}" data-tooltip="Edytuj wpis" class="careditlink">
                      <i class="icon-pencil"></i>
                    </a>
                  </div>
                  <div class="edit">
                    <a href="{{ route('delete.fuel', $fuel[$i]['id']) }}" data-tooltip="Usuń wpis" class="careditlink"
                      onclick="return confirm('Jesteś pewien, że chcesz usunąć ten wpis');">
                      <i class="icon-trash-empty"></i>
                    </a>
                  </div>

                </div>
                <div style="clear:both;"></div>
              </div>
            @if($i == $fuel_size-1 && $fuel_size >10)
              <div class="button">
                  <button type="button" id="fuel_button">pokaż wszystkie</button>
              </div>
              <div style="clear:both;"></div>
            @endif
          @endfor

        </div>
      @endif
    </div>




    {{-- Section with service costs --}}

    <div class="carinfo">

      @if ($service_size > 0)
        <h2>Wpisy serwisowe</h2>
        <div class="entries">

          <div class="title">
            <div class="eDate left">Data</div>
            <div class="eMileage left">Przebieg (km)</div>
            <div class="eDescription left">Opis</div>
            <div class="eServicePrice left">Części</div>
            <div class="eServicePrice left">Robocizna</div>
            <div class="eServicePrice left">Całość</div>
            <div class="eEdit left"></div>
            <div style="clear:both;"></div>
            <input type="hidden" id="serviceSize" value="{{ $service_size }}">
          </div>

          @for ($i=0; $i<$service_size; $i++)
            @if ($i >= 10)
                @if($i == $service_size-1)
                  <div class="entry hide last" id="s{{ $i }}">
                @else
                  <div class="entry hide" id="s{{ $i }}">
                @endif
            @else
                @if($i == $service_size-1)
                  <div class="entry last" id="s{{ $i }}">
                @elseif ($i == 9)
                  <div class="entry last" id="s{{ $i }}">
                @else
                  <div class="entry" id="s{{ $i }}">
                @endif
            @endif
                <div class="eDate left">{{ $service[$i]['date'] }}</div>
                <div class="eMileage left">{{ withSpace($service[$i]['mileage']) }}</div>
                <div class="eDescription left" data-tooltip="{{ 'Komentarz:  '.$service[$i]['comment'] }}">{{ $service[$i]['description'] }}</div>
                <div class="eServicePrice left">{{ $service[$i]['price_parts'] }} zł</div>
                <div class="eServicePrice left">{{ $service[$i]['price_labour'] }} zł</div>
                <div class="eServicePrice left">{{ $service[$i]['price_total'] }} zł</div>
                <div class="eEdit left">
                  <div class="edit">
                    <a href="{{ route('edit.service', $service[$i]['id']) }}" data-tooltip="Edytuj wpis" class="careditlink">
                      <i class="icon-pencil"></i>
                    </a>
                  </div>
                  <div class="edit">
                    <a href="{{ route('delete.service', $service[$i]['id']) }}" data-tooltip="Usuń wpis" class="careditlink"
                      onclick="return confirm('Jesteś pewien, że chcesz usunąć ten wpis');">
                      <i class="icon-trash-empty"></i>
                    </a>
                  </div>

                </div>
                <div style="clear:both;"></div>
              </div>
            @if($i == $service_size-1 && $service_size >10)
              <div class="button">
                  <button type="button" id="service_button">pokaż wszystkie</button>
              </div>
              <div style="clear:both;"></div>
            @endif
          @endfor

        </div>
      @endif
    </div>




    {{-- Section with other expenses --}}

    <div class="carinfo">
      @if ($expense_size > 0)
        <h2>Inne wydatki</h2>
        <div class="entries">

          <div class="title">
            <div class="eDate left">Data</div>
            <div class="eDescription left">Opis</div>
            <div class="eDescription left">Uwagi</div>
            <div class="eServicePrice left">Kwota</div>
            <div class="eEdit left"></div>
            <div style="clear:both;"></div>
            <input type="hidden" id="expenseSize" value="{{ $expense_size }}">
          </div>

          @for ($i=0; $i<$expense_size; $i++)
            @if ($i >= 10)
                @if($i == $expense_size-1)
                  <div class="entry hide last" id="e{{ $i }}">
                @else
                  <div class="entry hide" id="e{{ $i }}">
                @endif
            @else
                @if($i == $expense_size-1)
                  <div class="entry last" id="e{{ $i }}">
                @elseif ($i == 9)
                  <div class="entry last" id="e{{ $i }}">
                @else
                  <div class="entry" id="e{{ $i }}">
                @endif
            @endif
                <div class="eDate left">{{ $expense[$i]['date'] }}</div>
                <div class="eDescription left">{{ $expense[$i]['description'] }}</div>

                @if ($expense[$i]['comment'] == NULL)
                    <div class="eDescription left">Brak uwag</div>
                @else
                    @if (strlen($expense[$i]['comment']) > 45)
                      <div class="eDescription left" data-tooltip="{{ $expense[$i]['comment'] }}">{{ mb_substr($expense[$i]['comment'], 0, 45)."..." }}</div>
                    @else
                      <div class="eDescription left">{{ $expense[$i]['comment'] }}</div>
                    @endif
                @endif

                <div class="eServicePrice left">{{ $expense[$i]['price'] }} zł</div>
                <div class="eEdit left">
                  <div class="edit">
                    <a href="{{ route('edit.expense', $expense[$i]['id']) }}" data-tooltip="Edytuj wpis" class="careditlink">
                      <i class="icon-pencil"></i>
                    </a>
                  </div>
                  <div class="edit">
                    <a href="{{ route('delete.expense', $expense[$i]['id']) }}" data-tooltip="Usuń wpis" class="careditlink"
                      onclick="return confirm('Jesteś pewien, że chcesz usunąć ten wpis');">
                      <i class="icon-trash-empty"></i>
                    </a>
                  </div>

                </div>
                <div style="clear:both;"></div>
              </div>
            @if($i == $expense_size-1 && $expense_size >10)
              <div class="button">
                  <button type="button" id="expense_button">pokaż wszystkie</button>
              </div>
              <div style="clear:both;"></div>
            @endif
          @endfor

        </div>
      @endif

    </div>







  </div>

@stop

@section('script')
    <script>

      /*****************************************
      * Same size of #columnOne and #columnTwo *
      *****************************************/

      $(document).ready(function(){
        if ($("#columnOne").height() > $("#columnTwo").height()){
          $("#columnTwo").height($("#columnOne").height())
        }else{
          $("#columnOne").height($("#columnTwo").height())
        }
      });



      /************************************
      * Show more tan 10 entries function *
      ************************************/

      var fuel = ["f", parseInt($("#fuelSize").val()), "#fuel_button"];
      var service = ["s", parseInt($("#serviceSize").val()), "#service_button"];
      var expense = ["e", parseInt($("#expenseSize").val()), "#expense_button"];
      var reminder = ["r", parseInt($("#reminderSize").val()), "#reminder_button"];


        $('#fuel_button').click(function(){
          buttonShow(fuel[0], fuel[1], fuel[2]);
        });

        $('#service_button').click(function(){
          buttonShow(service[0], service[1], service[2]);
        });

        $('#expense_button').click(function(){
          buttonShow(expense[0], expense[1], expense[2]);
        });

        $('#reminder_button').click(function(){
          buttonShow(reminder[0], reminder[1], reminder[2]);
        });


        function buttonShow(id, size, button){
          if($(button).val()){
            $(button).val('');
            $(button).html("pokaż wszystkie");
            for ($i=10; $i < size; $i++){
              $('#'+id+$i).addClass("hide");
            }
            $('#'+id+'9').addClass("last");
          }
          else{
            $(button).val("on");
            $(button).html("schowaj");
            for ($i=10; $i < size; $i++){
              $('#'+id+$i).removeClass("hide");
            }
            $('#'+id+'9').removeClass("last");
          }
        };



    </script>
@stop
