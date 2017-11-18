@php
  if (session()->has('id')){
    $cars = \App\Car::menuCars(session('id'))->get();
    $size = sizeof($cars);
  }
@endphp

<div class="nav">
  <ol>
    <li>{{ Html::linkAction('CarlogController@home','Strona główna') }}</li>
    <li>{{ Html::linkAction('CarController@View','Dodaj auto') }}</li>
    @if (session()->has('id') && $size == 0)
      <li data-tooltip="Brak samochodów">+ Nowy wpis
    @else
      <li>+ Nowy wpis
    @endif
      <ul>
        <li>{{ Html::linkAction('Fuel_expenseController@View','Paliwo') }}</li>
        <li>{{ Html::linkAction('ServiceController@View','Serwis') }}</li>
        <li>{{ Html::linkAction('ExpenseController@View','Inne wydatki') }}</li>
        <li>{{ Html::linkAction('ReminderController@View','Przypomnienie') }}</li>
      </ul>
    </li>
    @if (session()->has('id') && $size != 0)
      <li>Statystyki
        <ul>
          @for ($i = 0; $i < $size; $i++)
            <li>{{ Html::linkRoute('info.car', $cars[$i]['make'].' '. $cars[$i]['model'],['id' => $cars[$i]['id'] ]) }}</li>
          @endfor
        </ul>
      </li>
    @elseif (session()->has('id') && $size == 0)
      <li data-tooltip="Brak samochodów">Statystyki</li>
    @else
      <li data-tooltip="Najpierw musisz się zalogować">Statystyki</li>
    @endif
    <li>{{ Html::linkAction('CarlogController@logout','Wyloguj mnie') }}</li>
  </ol>
</div>

@php if (isset($cars)) unset($cars); @endphp
