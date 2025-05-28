@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1>Task Assignment Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{ route('tasks_assignment.store') }}">
        @csrf

     


    <!--this is used to list the female_cow_name -->
        <div class="form-group">

            <label for="task_id">Task</label>

            <select name="task_id" id="task_id" class="form-control">
                <option value="">Select the Task</option>
           
                    @foreach($tasks as $task)
                <option value="{{$task->id}}">{{$task->title}}</option>
                    @endforeach
            </select>

            @error('task_id') <span class="text-danger">{{ $message }}</span> @enderror

        </div>
       
        <div class="form-group">

            <label for="assigned_to">Assigned Farm Labore</label>
        
            <select name="assigned_to" id="assigned_to" class="form-control">
                <option value="">Select the Task</option>
           
                @foreach($farm_labores as $farm_labore)
                    <option value="{{$farm_labore->id}}">{{ $farm_labore->user->name }}</option>
                @endforeach
            </select>
            @error('assigned_to') <span class="text-danger">{{ $message }}</span> @enderror

        </div>

        <div class="form-group">
            <label for="due_date">Due Date</label>
                <input type="date" name="due_date" class="form-control rounded" id="due_date">
                @error('due_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        
        <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>
</div>

@endsection
