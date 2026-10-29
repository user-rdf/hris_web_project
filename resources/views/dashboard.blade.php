@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h3>Selamat datang, {{ auth()->user()->name }}</h3>
<p>Role Anda: <strong>{{ auth()->user()->role }}</strong></p>

@if (auth()->user()->isAdmin())
    <div class="alert alert-info">Anda login sebagai Admin HR.</div>
@elseif (auth()->user()->isManager())
    <div class="alert alert-info">Anda login sebagai Manager.</div>
@else
    <div class="alert alert-info">Anda login sebagai Karyawan.</div>
@endif
@endsection