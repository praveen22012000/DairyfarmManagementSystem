@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Re-Assign Farm Labore</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{ route('task-assignments.reassign',$taskassignment->id) }}">
        @csrf

         @method('PUT')
     

        <div class="form-group">

            <label for="assigned_to">Assigned Farm Labore</label>
        
            <select name="assigned_to" id="assigned_to" class="form-control">
                <option value="">Select the Task</option>
           
                @foreach($farm_labores as $farm_labore)
                    <option value="{{$farm_labore->id}}"
                    {{$taskassignment->assigned_to == $farm_labore->id ? 'selected': '' }}
                    >{{ $farm_labore->user->name }}</option>
                @endforeach
            </select>
            @error('assigned_to') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        <div class="form-group">
            <label for="due_date">Due Date</label>
                <input type="date" name="due_date" class="form-control rounded" value="{{ $taskassignment->due_date  }}" id="due_date">
                @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        
        <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>
</div>

@endsection
