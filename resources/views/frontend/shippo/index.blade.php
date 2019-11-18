@include('frontend/front_layout/header');
@extends('layouts.main')

@section('content')
<h1>Choose one of the Shipping options</h1><br><br><br>

<!--    <form action="{{URL::to('/frontend/shippo/store')}}">-->
<form action="{{URL::to('/frontend/order/placeOrder')}}">
        {{ csrf_field() }}
        <table class="table">
            <tr>
                <th>Name</th>
                <th>Price</th>
            </tr>
            @foreach ($shippos as $rate)
            <tr>
                <td>
                    <img src="{{ $rate['provider_image_75'] }}" alt="">
                    {{ $rate['provider'] }} ({{ $rate['duration_terms'] }})
                </td>
                <td width="20%">
                    <input type="radio" class="pull-right" name="rate" value="{{ $rate['object_id'] }}${{ $rate['amount'] }}">
                    ${{ $rate['amount'] }}
                </td>
            </tr>
            @endforeach
        </table>
        <button class="btn btn-primary">Continue</button>
    </form>
@endsection

