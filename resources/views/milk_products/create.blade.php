@extends('layouts.admin.master')

@section('content')

<div class="col-md-12">

       
            <h1 style="text-align:center">Milk Products Form</h1>     
        

    <br>

    <form action="{{route('milk_product.store')}}" method="POST">
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

    <div class="col-md-12">

        <label for="product_name">Product Name</label>
        <input type="text" name="product_name" class="form-control rounded" value="{{ old('product_name') }}" id="product_name" >
        @error('product_name') <span class="text-danger">{{ $message }}</span> @enderror

    </div>

  

    <div class="col-md-12">

        <label for="unit_price">Unit Price(Rs.)</label>
        <input type="text" name="unit_price" class="form-control rounded" value="{{ old('unit_price') }}" id="unit_price" >
        @error('unit_price') <span class="text-danger">{{ $message }}</span> @enderror

    </div>

    <div class="col-md-12" id="ingredientFields">
    <label for="ingredients">Ingredients</label>

    @if(old('ingredients'))<!-- This checks if the form was previously submitted and if it contained any values for the ingredients[] input field.Laravel’s old() helper retrieves old input values from the session  -->
        @foreach(old('ingredients') as $ingredient)<!-- If there are old values, this foreach loop runs through each individual ingredient value.old('ingredients') returns an array, so we loop through it. -->
            <div>
                <input type="text" name="ingredients[]" class="col-md-12 form-control rounded" value="{{ $ingredient }}" required>
                <button type="button" class="btn btn-danger" onclick="this.parentNode.remove()">Remove</button>
                <br><br>
            </div>
        @endforeach
    @else
        <div>
            <input type="text" name="ingredients[]" class="col-md-12 form-control rounded" required>
            <br><br>
        </div>
    @endif

    @error('ingredients')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

    <button type="button" class="btn btn-primary" onclick="addIngredient()">Add Ingredient</button>

    <button type="submit" class="btn btn-success">Save Product</button>

    </form>

</div>


  
@endsection

      
@section('js')



<script>
    function addIngredient() {
        let div = document.createElement("div");
        div.innerHTML = '<input type="text" name="ingredients[]" class="col-md-12 form-control rounded" required>  <button type="button" class="btn btn-danger" onclick="this.parentNode.remove()">Remove</button> <br><br>';
        document.getElementById("ingredientFields").appendChild(div);
    }
</script>
     



@endsection
