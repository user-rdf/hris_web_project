@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
    <h3>Edit Karyawan</h3>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('employees.update', $employee) }}">
                @csrf
                @method('PUT')
                @include('employees._form')
            </form>
        </div>
    </div>
@endsection