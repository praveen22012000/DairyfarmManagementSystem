


@extends('layouts.admin.master')

@section('content')
     
<form method="post" action="{{route('animal_calvings.destroy',$animalcalvings->id)}}">
            @csrf
               <p>Are you sure want to delete?</p>
               <p>following will be deleted</p>
               <p>Calf_name:{{$animalcalvings->calf->animal_name}}</p>
                <input type="submit" value="delete">

        </form>


@endsection
