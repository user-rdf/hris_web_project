@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<h3>Tambah Karyawan</h3>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('employees.store') }}">
        @csrf
        @include('employees._form')
    </form>
</div></div>
@endsection