@extends('layouts.admin.master')

@section('content')

<!-- this code work properly but some issues -->
<div class="col-md-12">
    <h1 class="text-3xl font-bold mb-10 text-center text-gray-700">Salary Details</h1>

    <form action="{{ route('salary.update',$salary->id) }}" method="POST" class="space-y-8">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- Date and Time Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <br>
                <!-- this is used to list the animal types-->
            <div class="form-group">
                <label for="role_id">Role</label>
           
                    <!-- this is used to list the animal types-->
                    <select name="role_id" id="role_id" class="form-control" >
                        <option value="">Select Role</option>
             
                                @foreach ($available_roles as $role)
                            <option value="{{ $role->id }}"
                            {{ $salary->role_id == $role->id ? 'selected' : '' }}
                            >{{ $role->role_name}}</option>
                                @endforeach
                    </select>
                @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


           

            <div>
                <label for="base_salary">Base Salary(Rs.)</label>
                <input type="text" name="base_salary" id="base_salary"  class="form-control rounded"  value="{{ $salary->base_salary  }}">
                @error('base_salary') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


                  
            <div class="form-group">
                <label for="effective_from">Effective From</label>
                <input type="date" name="effective_from" class="form-control rounded" value="{{ $salary->effective_from }}" id="effective_from">
                @error('effective_from') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <label for="effective_to">Effective To</label>
                <input type="date" name="effective_to" class="form-control rounded" value="{{ $salary->effective_to }}" id="effective_to">
                @error('effective_to') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


        </div>

        <br>

       

      
    </form>
</div>












@endsection

@section('js')





</script>


<script>



</script>

@endsection
