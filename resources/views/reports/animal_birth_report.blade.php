
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')

<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
    <h1 style="text-align:center">Animal Birth Report</h1>

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

   
    <div>
        <button type="submit" class="btn btn-success mt-3">
            Generate Report
        </button>
    </div>
</form>

    {{-- Show the report only if data is available --}}
    @if (!empty($AnimalData) && count($AnimalData))
        <div class="mb-4">
          
            <p class="text-sm text-gray-600">From <strong>{{ $start }}</strong> to <strong>{{ $end }}</strong></p>
            <p class="text-sm text-gray-600">Total Animals:{{$totalAnimals}}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th class="px-4 py-2 border-b">Animal ID</th>
                        <th class="px-4 py-2 border-b">Animal Name</th>
                        <th class="px-4 py-2 border-b">Animal Birth Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($AnimalData as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $data->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $data->animal_name ?? 'Unknown' }}</td>
                            <td class="px-4 py-2 border-b">{{ $data->animal_birthdate}}</td>
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
