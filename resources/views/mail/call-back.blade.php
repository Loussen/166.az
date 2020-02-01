@extends('mail.index')

@section('content')
    <h3>Call back!</h3>
    <p>Name: {{ $name }}</p>
    <p>Phone: {{ $phone }}</p>
    <p>Service: {{ $service }}</p>
    <p>City: {{ $city }}</p>
@endsection
