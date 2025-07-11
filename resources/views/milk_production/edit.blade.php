@extends('layouts.admin.master')

@section('content')
       
<div class="col-md-12">

       
            <h1 style="text-align:center;">Milk Production Form</h1>     
        

    <br>

    <form  method="POST" enctype="multipart/form-data" action="{{ route('production_milk.update',$productionmilk->id) }}">
        @csrf

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">General Information</legend>

   


         <!-- this is used to list the female animal-->
        <div class="form-group">
            <label for="animal_id">Animal </label>
           
        <!-- this is used to list the  female animals-->
        <select name="animal_id" id="animal_id" class="form-control" >
                <option value="">Select the Animal</option>
             
                @foreach ($female_Animals as $female_Animal)
                <option value="{{ $female_Animal->id }}"
               {{ $productionmilk->animal_id == $female_Animal->id ? 'selected':'' }}
                >{{ $female_Animal->animal_name}}</option>
                @endforeach
            </select>
            @error('animal_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        
        <!--this is get the milk production date-->
        <div class="form-group">
            <label for="production_date">Milk Production Date</label>
                <input type="date" name="production_date" class="form-control rounded" id="production_date" value="{{$productionmilk->production_date}}">
                @error('production_date') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!--this is to get the milk production in liters-->
        <div class="form-group">
            <label for="Quantity_Liters">Quantity Liters</label>
            <input type="text" name="Quantity_Liters" class="form-control rounded" id="Quantity_Liters" value="{{ $productionmilk->Quantity_Liters }}">
            @error('Quantity_Liters') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

         <!-- this is used to list the  farm labore-->
         <div class="form-group">
            <label for="user_id">Farm Labore </label>
        <select name="user_id" id="user_id" class="form-control" >
                <option value="">Select the Farm Labore</option>
             
                @foreach ($farm_labors as $farm_labor)
                <option value="{{ $productionmilk->user_id }}"
                {{$productionmilk->user_id==$farm_labor->id ? 'selected':''}}
                >{{ $productionmilk->user->name}}</option>
                @endforeach
            </select>
            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>


        <div class="form-group">

                <label for="shift">Shift</label><br>

                <input type="radio" id="morning" name="shift" value="Morning"
                {{ $productionmilk->shift == 'Morning' ? 'checked' : ''}}
                >
                <label for="male">Morning</label><br>

                <input type="radio" id="afternoon" name="shift" value="Afternoon"
                {{ $productionmilk->shift == 'Afternoon' ? 'checked' : ''}}
                >
                <label for="female">Afternoon</label><br>

                <input type="radio" id="evening" name="shift" value="Evening"
                {{ $productionmilk->shift == 'Evening' ? 'checked' : ''}}
                >
                <label for="others">Evening</label>
                @error('shift') <span class="text-danger">{{ $message }}</span> @enderror

        </div>



        </fieldset>

      

        <fieldset class="border p-4 mb-4">
        <legend class="w-auto px-2">Descriptive Information</legend>



         
        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fat_percentage" class="form-label">Fat Percentage (%)</label>
                    <input type="text" class="form-control rounded" id="fat_percentage" name="fat_percentage" placeholder="Enter Fat Percentage"
                    value="{{$productionmilk->fat_percentage}}"
                    >
                    @error('fat_percentage') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

       
                <div class="col-md-6">
                    <label for="protein_percentage" class="form-label">Protein Percentage (%)</label>
                    <input type="text" class="form-control rounded" id="protein_percentage" name="protein_percentage" placeholder="Enter Protein Percentage"
                    value="{{$productionmilk->protein_percentage}}"
                    >
                    @error('protein_percentage') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

               

                
        </div>

        

        <div class="row mb-3">
                <div class="col-md-6">
                    <label for="somatic_cell_count" class="form-label">Somatic Cell Count</label>
                    <input type="text" class="form-control rounded" id="somatic_cell_count" name="somatic_cell_count" placeholder="Enter somatic cell count"
                    value="{{$productionmilk->somatic_cell_count }}"
                    >
                    @error('somatic_cell_count') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

       
                <div class="col-md-6">
                    <label for="lactose_percentage" class="form-label">Lactose Percentage (%)</label>
                    <input type="text" class="form-control rounded" id="lactose_percentage" name="lactose_percentage" placeholder="Enter the lactose Percentage"
                    value="{{$productionmilk->lactose_percentage}}"
                    >
                    @error('lactose_percentage') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                   <!-- Hidden stock_quantity field -->
                   <input type="hidden" name="stock_quantity" id="stock_quantity" value="">

                
        </div>
       

        
        </fieldset>
       
        <button type="submit" class="btn btn-success mt-3">Update</button>

       

       
       
        
       

        
    </form>
</div>

@endsection

      
@section('js')
<script>
    document.getElementById('Quantity_Liters').addEventListener('input', function () {
        document.getElementById('stock_quantity').value = this.value;
    });
</script>

@endsection
