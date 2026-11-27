@extends('layouts.app')
@section('title', 'Catat Penyakit')

@section('content')
    <form method="POST" action="{{ route('penyakit.store') }}" autocomplete="off">
        @csrf
        @include('kesehatan.penyakit._form')
    </form>
@endsection
