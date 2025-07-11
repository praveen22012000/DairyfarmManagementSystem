
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')

<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
    <h1 style="text-align:center">Dispose Feed Items Report</h1>

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
           
    <div>
        <button type="submit" class="btn btn-success mt-3">
            Generate Report
        </button>
    </div>
    <br>
</form>
<br><br>

@if (!empty($disposeFeedData) && count($disposeFeedData))
    <form method="GET" action="{{ route('dispose_feed_items_pdf.report') }}" target="_blank">
        <input type="hidden" name="start_date" value="{{ $start }}">
        <input type="hidden" name="end_date" value="{{ $end }}">
        <button type="submit" class="btn btn-danger mb-3">Download as PDF</button>
    </form>
@endif

 


@if(count($disposeFeedData))
<p>From <strong>{{ $start }}</strong> to <strong>{{ $end }}</strong></p>
      <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th class="px-4 py-2 border">Feed Name</th>
                <th class="px-4 py-2 border">Dispose Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($disposeFeedData as $item)
                <tr>
                    <td class="px-4 py-2 border">{{ $item->feed_name }}</td>
                    <td class="px-4 py-2 border">{{ number_format($item->total_dispose_quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@elseif($start && $end)
    <p>No purchase Feed items within this date range.</p>
@endif
</div>
</div>
@endsection

@section('js')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
