@extends('layouts.app')

@section('content')
    {{-- <home-component></home-component> --}}
    <perfil-component :user="{{ $user }}"></perfil-component>
@endsection
