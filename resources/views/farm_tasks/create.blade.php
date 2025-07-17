@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">


       
            <h1 style="text-align:center;">Tasks Details</h1>     

    <br>

    <form method="POST" action="{{route('tasks.store')}}">
        @csrf
        <div class="col-md-12">

            <label for="title">Task Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control rounded" id="title" >
            @error('title') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        <br>
          <div class="col-md-12">
            <label for="description">Task Description</label>
            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter the task description here">{{ old('description') }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>

</div>

@endsection
