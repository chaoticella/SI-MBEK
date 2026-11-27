@extends('layouts.app')
@section('title', 'Edit Kondisi Fisik')

@section('content')
    <form method="POST" action="{{ route('kondisi-fisik.update', $kondisi) }}" autocomplete="off">
        @csrf
        @method('PUT')
        @include('kesehatan.kondisi-fisik._form')
    </form>
@endsection
