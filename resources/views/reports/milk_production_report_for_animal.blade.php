
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')

<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
    <h1 style="text-align:center">Milk Production Report For Animals</h1>

    {{-- Display errors if any --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form to select date range --}}
    <form action="" method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date:</label>
        <input type="date" name="start_date" value="{{ old('start_date', $start) }}" 
            class="form-control rounded w-full" required>
    </div>
    
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date:</label>
        <input type="date" name="end_date" value="{{ old('end_date', $end) }}" 
            class="form-control rounded w-full" required>
    </div>

     <div class="form-group">
            <label for="animal_id">Animal</label>
           
        <!-- this is used to list the animal types-->
        <select name="animal_id" id="animal_id" class="form-control" >
                <option value="">Select Animal</option>
             
                @foreach ($female_animals as $female_animal)
                <option value="{{ $female_animal->id }}">{{ $female_animal->animal_name}}</option>
                @endforeach
            </select>
            @error('animal_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    
    <div>
        <button type="submit" class="btn btn-success mt-3">
            Generate Report
        </button>
    </div>
</form>

<br><br>
@if (!empty($milkData) && count($milkData))
    <form method="GET" action="{{ route('milk.production.report.animal.pdf') }}" target="_blank">
        <input type="hidden" name="start_date" value="{{ $start }}">
        <input type="hidden" name="end_date" value="{{ $end }}">
        <input type="hidden" name="animal_id" value="{{ $animalID }}">
        <button type="submit" class="btn btn-danger mb-3">Download as PDF</button>
    </form>
@endif


    {{-- Show the report only if data is available --}}
    @if (!empty($milkData) && count($milkData))
        <div class="mb-4">
            <h2 style="text-align:center">Report</h2>
            <p class="text-sm text-gray-600">From <strong>{{ $start }}</strong> to <strong>{{ $end }}</strong></p>
            <p class="text-sm text-gray-600">Total Amount of Milk:<strong> {{$total_quantity}} </strong> Liters</p>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                       
                        <th class="px-4 py-2 border-b">Animal Name</th>
                        <th class="px-4 py-2 border-b">Shift</th>
                        <th class="px-4 py-2 border-b">Production Date</th>
                        <th class="px-4 py-2 border-b">Total Milk (Liters)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($milkData as $data)
                        <tr class="hover:bg-gray-50">
                         
                            <td class="px-4 py-2 border-b">{{ $data->AnimalDetail->animal_name ?? 'Unknown' }}</td>
                            <td class="px-4 py-2 border-b">{{ $data->shift }}</td>
                            <td class="px-4 py-2 border-b">{{ $data->production_date }}</td>
                            <td class="px-4 py-2 border-b">{{ $data->Quantity_Liters }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif($start && $end)
        <p class="text-gray-600 italic">No milk production records found for the selected date range.</p>
    @endif
</div>
@endsection

@section('js')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
