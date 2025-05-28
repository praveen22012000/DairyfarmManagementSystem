@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">


       
            <h1>Assign Delivery Person</h1>     

    <br>

    <form method="POST" action="{{ route('assign_delivery_person.store',$retailor_order->id) }}">
    @csrf

    <label>Select Delivery Person:</label>
    <select name="delivery_person_id" class="form-control rounded">
            <option value="">Select Delivery Person</option>
        @foreach($deliveryPersons as $person)
            <option value="{{ $person->id }}">{{ $person->user->name }} {{ $person->user->phone_number }}</option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-success mt-3">Assign Delivery</button>
    </form>

</div>

@endsection
