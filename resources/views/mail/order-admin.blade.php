@extends('mail.index')

@section('content')
    <h3>New order received!</h3>
    <p>Name: {{ $name }}</p>
    <p>Phone: {{ $phone }}</p>
    <p>Service: {{ $parent . ( $service ? ( ': ' . $service ) : '' ) }}</p>
    <p>Address @if( $address_2 ) 1 @endif : {{ $address_1 }}</p>
    @if( $address_2 )
        <p>Address 2: {{ $address_2 }}</p>
    @endif
    <p>Date: {{ $date }}</p>
    <p>Hour: {{ $hour }}</p>
@endsection
