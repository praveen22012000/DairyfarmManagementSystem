@extends('layouts.admin.master')

@section('content')
       
<div class="container">
    <h2>User Details</h2>
    <form method="POST" action="">
        @csrf
       
{{ $user }}

 @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="form-group">
                <label for="name">First Name:</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="{{ $user->name  }}">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="last_name" id="last_name" name="last_name" class="form-control" 
                       value="{{ $user->lastname }}">
                @error('last_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>



            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" class="form-control" 
                       value="{{ $user->address }}">
                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Address</label>
                <input type="text" id="email" name="email" class="form-control" 
                       value="{{ $user->email  }}">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <label for="nic">NIC</label>
                <input type="text" id="nic" name="nic" class="form-control" 
                       value="{{ $user->nic }}">
                @error('nic') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

          
            

        </div>

        <a href="{{ route('main_user_details.list') }}" class="btn btn-info">Back</a>


       
    </form>




</div>

  
      


@endsection

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

   


</script>


@endsection