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
                <a  class="btn btn-primary btn-md btn-rounded" href="{{route('animal.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add Animal</a>
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
                        <th>Animal ID</th>
                        <th>Animal Name</th>
                        <th>Animal Type</th>
                        <th></th>
                      
                    </tr>
                </thead>
                    @foreach($animals as $animal)
                    <tr>
                        <td>{{$animal->id}}</td>
                        <td>{{$animal->animal_name}}</td>
                        <td>{{$animal->AnimalType->animal_type}}</td>
                        <td><a href="{{route('animal.edit',$animal->id)}}">Edit</a></td>
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