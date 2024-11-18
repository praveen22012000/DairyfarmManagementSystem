@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Animals</h2>
                </div>
                <div class="float-right">
                <a  class="btn btn-success btn-md btn-rounded" href="{{route('animal_calving.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add Calving Events</a>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>Calving ID</th>
                        <th>Calf Name</th>
                        <th>Parent Animal Name</th>
                        <th>Calving Date</th>
                        <th>Calving Notes</th>
                        <th></th>
                        <th></th>
                        <th></th>
                      
                    </tr>
                </thead>
                    @foreach($animal_calvings_details as $animal_calvings_detail)
                    <tr>
                        <td>{{$animal_calvings_detail->id}}</td>
                        <td>{{$animal_calvings_detail->calf->animal_name}}</td>
                      
                        <td>{{$animal_calvings_detail->calving_date}}</td>
                        <td>{{$animal_calvings_detail->calving_notes}}</td>

                        <td><a href="{{route('animal_calvings.edit',$animal_calvings_detail->id)}}">Edit</a></td>
                        <td><a href="{{route('animal_calvings.delete',$animal_calvings_detail->id)}}">Delete</a></td>

                       
                        <td></td>
                    </tr>
                    @endforeach
                <tbody>
            
                </tbody>
            </table>
            <div class="pt-2">
                <div class="float-right">
                   
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection