@extends('layouts.base')


@section('head')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/home.css') }}"/>
@stop


@section('body')

  @php
    $user = new \App\User;
    $cars = $user->getCars(session('id'));
    $size = sizeof($cars);
  @endphp

  <div id="carsection">

    @if ($size == 0)
      <h1>Obecnie nie posiadasz żadnych samochodów</h1>
    @else
      <h1>Twoje aktualnie posiadane samochody</h1>
    @endif

    @for ($i = 0, $count = 0; $i <= $size; $i++)
        @if ($i < $size && $cars[$i]->sale_date === NULL)
            <div class='cartile'>
                <a href="{{ url('/StatsCar/'.$cars[$i]->id) }}" class="carlink">
                    {{ $cars[$i]->make . ' ' . $cars[$i]->model }}
                </a>
                <div class='edit'>
                    <a href="{{ route('edit.car', $cars[$i]->id) }}" class="careditlink">
                      <i class="icon-pencil-squared"></i>
                    </a>
                </div>
                <div class='delete'>
                    <a href="{{ route('delete.car', $cars[$i]->id) }}" class="careditlink"
                      onclick="return confirm('Jesteś pewien, że chcesz usunąć ten samochód?');">
                      <i class="icon-trash"></i>
                    </a>
                </div>
            </div>
            @php $count++ @endphp
        @endif

        @if ($i == $size)
            <div class='cartile'>
                <a href="{{ url('/AddCar') }}" class="carlink">
                  <i class="icon-plus-squared"></i><br/>Dodaj nowy samochód
                </a>
            </div>
        @endif

        @if ($count % 2 == 0)
            <div style="clear:both"></div>
        @endif
    @endfor

      <div style="clear:both"></div>


    @php
      $past = 0;
      for ($i = 0; $i < $size; $i++)
        if ($cars[$i]->sale_date != NULL) $past++;
    @endphp

    @if ($past > 0)
      <h1>Samochody których nie jesteś już właścicielem</h1>

      @for ($i = 0, $count = 0; $i <= $size; $i++)
          @if ($i < $size && $cars[$i]->sale_date != NULL)
              <div class='cartile'>
                  <a href="{{ url('/StatsCar/'.$cars[$i]->id) }}" class="carlink">
                      {{ $cars[$i]->make . ' ' . $cars[$i]->model }}
                  </a>
                  <div class='edit'>
                      <a href="{{ route('edit.car', $cars[$i]->id) }}" class="careditlink">
                        <i class="icon-pencil-squared"></i>
                      </a>
                  </div>
                  <div class='delete'>
                    <a href="{{ route('delete.car', $cars[$i]->id) }}" class="careditlink"
                      onclick="return confirm('Jesteś pewien, że chcesz usunąć ten samochód?');">
                      <i class="icon-trash"></i>
                    </a>
                  </div>
              </div>
              @php $count++ @endphp
          @endif

          @if ($count % 2 != 0)
              <div style="clear:both"></div>
          @endif
      @endfor

    @endif

    <div style="clear:both"></div>

  </div>

@stop
