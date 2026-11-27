@extends('layouts.app')
@section('title', 'Catat Kondisi Fisik')

@section('content')
    <form method="POST" action="{{ route('kondisi-fisik.store') }}" autocomplete="off">
        @csrf
        @include('kesehatan.kondisi-fisik._form')
    </form>
@endsection
