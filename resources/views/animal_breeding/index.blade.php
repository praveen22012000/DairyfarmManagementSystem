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
                <a  class="btn btn-success btn-md btn-rounded" href="{{route('animal_breedings.create')}}"><i class="mdi mdi-plus-circle mdi-18px"></i>Add Breeding Events</a>
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
                        <th>Breeding ID</th>
                        <th>Female Cow Name</th>
                        <th>Male Name</th>
                        <th>insemination Type</th>
                        <th>Breeding Notes</th>
                        <th></th>
                        <th></th>
                        <th></th>
                      
                    </tr>
                </thead>
                
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                        <td><a href="">Edit</a></td>
                        <td><a href="">Delete</a></td>
                        <td></td>
                    </tr>
                  
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