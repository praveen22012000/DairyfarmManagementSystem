@extends('layouts.admin.master')

@section('content')


<div class="col-md-12">

       
            <h1>Milk Products Form</h1>     
        

    <br>

<form action="" method="POST">
    @csrf


    <div class="col-md-12">

        <label for="product_name">Product Name</label>
        <input type="text" name="product_name" class="form-control rounded" id="product_name" value="{{$milkProduct->product_name}}">
        @error('product_name') <span class="text-danger">{{ $message }}</span> @enderror

    </div>

  

    <div class="col-md-12">

        <label for="unit_price">Unit Price(Rs.)</label>
        <input type="text" name="unit_price" class="form-control rounded" id="unit_price" value="{{$milkProduct->unit_price}}">
        @error('unit_price') <span class="text-danger">{{ $message }}</span> @enderror

    </div>

    <div class="col-md-12" id="ingredientFields">

        <label for="ingredients">Ingredients</label>
      
        @foreach($milkProduct->ingredients as $ingredient)
        <input type="text" name="ingredients[]" class="col-md-12 form-control rounded" value="{{$ingredient->ingredients}}" required>  
                @endforeach
       <br>
       
    </div>
    

  

   
</form>


</div>



       

  
@endsection

      
@section('js')



@endsection
