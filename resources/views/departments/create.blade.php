@extends('layouts.app')

@section('title', 'Tambah Departemen')

@section('content')
<h3>Tambah Departemen</h3>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('departments.store') }}">
        @csrf
        @include('departments._form', ['department' => null])
    </form>
</div></div>
@endsection