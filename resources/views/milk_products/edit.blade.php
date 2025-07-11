@extends('layouts.admin.master')

@section('content')

<form action="{{route('milk_product.update',$milkProduct->id)}}" method="POST">
    @csrf
    <h1 style="text-align:center">Milk Products Details</h1>

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
       
       
    </div>
    
    <br>
    <button type="button" class="btn btn-primary" onclick="addIngredient()">Add Ingredient</button>

    <button type="submit" class="btn btn-success">Update Product</button>

</form>


  
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
