@extends('layouts.admin.master')

@section('content')

<div class="col-md-12">

       
            <h1>Milk Products Form</h1>     
        

    <br>

    <form action="{{route('milk_product.store')}}" method="POST">
    @csrf


    <div class="col-md-12">

        <label for="product_name">Product Name</label>
        <input type="text" name="product_name" class="form-control rounded" id="product_name" >
        @error('product_name') <span class="text-danger">{{ $message }}</span> @enderror

    </div>

  

    <div class="col-md-12">

        <label for="unit_price">Unit Price(Rs.)</label>
        <input type="text" name="unit_price" class="form-control rounded" id="unit_price" >
        @error('unit_price') <span class="text-danger">{{ $message }}</span> @enderror

    </div>

    <div class="col-md-12" id="ingredientFields">

        <label for="ingredients">Ingredients</label>
      
       
       
    </div>
    

    <button type="button" onclick="addIngredient()">Add Ingredient</button>

    <button type="submit">Save Product</button>

    </form>

</div>


  
@endsection

      
@section('js')



<script>
    function addIngredient() {
        let div = document.createElement("div");
        div.innerHTML = '<input type="text" name="ingredients[]" class="col-md-12 form-control rounded" required>  <button type="button" onclick="this.parentNode.remove()">Remove</button> <br><br>';
        document.getElementById("ingredientFields").appendChild(div);
    }
</script>
     



@endsection
