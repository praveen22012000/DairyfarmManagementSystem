
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')

<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
    <h1 style="text-align:center">MilK Allocated For Products</h1>

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
        <input type="date" name="start_date" value="{{ old('start_date', $startDate) }}" 
            class="form-control rounded w-full" required>
    </div>
    
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date:</label>
        <input type="date" name="end_date" value="{{ old('end_date', $endDate) }}" 
            class="form-control rounded w-full" required>
    </div>

     <div class="form-group">
           
    <div>
        <button type="submit" class="btn btn-success mt-3">
            Generate Report
        </button>
    </div>
</form>

  <h2 style="text-align:center">Milk Allocation Report</h2>
<p>From <strong>{{ $startDate }}</strong> to <strong>{{ $endDate }}</strong></p>

@if(count($allocatedMilk))

      <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th class="px-4 py-2 border">Product Name</th>
                <th class="px-4 py-2 border">Total Allocated Milk (Liters)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allocatedMilk as $item)
                <tr>
                    <td class="px-4 py-2 border">{{ $item->product_name }}</td>
                    <td class="px-4 py-2 border">{{ number_format($item->total_allocated_quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <p>No milk allocation records found for the selected date range.</p>
@endif
</div>
</div>
@endsection

@section('js')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
