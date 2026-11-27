@extends('layouts.app')
@section('title', 'Edit Pencatatan Penyakit')

@section('content')
    <form method="POST" action="{{ route('penyakit.update', $penyakit) }}" autocomplete="off">
        @csrf
        @method('PUT')
        @include('kesehatan.penyakit._form')
    </form>
@endsection
