@extends('layouts.base')


@section('head')
@stop


@section('body')
  <h1>Hi {{ session('name') }} !</h1></br>
  @if (session()->has('message_new_user'))
    {{ session('message_new_user') }}
    @php Session::forget('message_new_user') @endphp
  @endif
@stop
