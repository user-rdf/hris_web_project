@extends('layouts.app')

@section('title', 'Edit Departemen')

@section('content')
<h3>Edit Departemen</h3>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('departments.update', $department) }}">
        @csrf
        @method('PUT')
        @include('departments._form')
    </form>
</div></div>
@endsection