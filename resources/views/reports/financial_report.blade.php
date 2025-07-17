
@extends('layouts.admin.master')
@section('title', 'Fiancial Report')

@section('content')

<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
    <h1 style="text-align:center">Financial Report</h1>

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
        <input type="date" value="{{ old('start_date', $start) }}" name="start_date"  
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
</form>

<br><br>

@if ($final_value)
    <form method="GET" action="{{ route('farm_financial_report.pdf') }}" target="_blank">
        <input type="hidden" name="start_date" value="{{ $start }}">
        <input type="hidden" name="end_date" value="{{ $end }}">
        <button type="submit" class="btn btn-danger mb-3">Download as PDF</button>
    </form>
@endif
<br>
@if($final_value)


<p>From <strong>{{ $start }}</strong> to <strong>{{ $end }}</strong></p>
<h3 style="text-align:left;">Income</h3>
 <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th class="px-4 py-2 border">Income Way</th>
                <th class="px-4 py-2 border">Total Income</th>
            </tr>
        </thead>
        <tbody>
          
                <tr>
                    <td class="px-4 py-2 border">Milk Product Sales</td>
                    <td class="px-4 py-2 border">{{ $milk_product_total_income }}</td>
                </tr>
          
        </tbody>
    </table>
     <p style="color: blue; font-weight: bold;font-size:18px;">
    Total Income: Rs. {{ number_format($total_income, 2) }}
</p>
<br>


    <h3 style="text-align:left;">Expenses</h3>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th class="px-4 py-2 border">Expenses Way</th>
                <th class="px-4 py-2 border">Total Expenses</th>
            </tr>
        </thead>
        <tbody>
          
                <tr>
                    <td class="px-4 py-2 border">Feed Purchases</td>
                    <td class="px-4 py-2 border">{{ $purchase_feed_items_expenses }}</td>
                </tr>

                <tr>
                    <td class="px-4 py-2 border">Vaccine Purchases</td>
                    <td class="px-4 py-2 border">{{ $purchase_vaccine_items_expenses }}</td>
                </tr>

                <tr>
                    <td class="px-4 py-2 border">Staff Salary</td>
                    <td class="px-4 py-2 border">{{ $total_salary_expenses }}</td>
                </tr>
          
        </tbody>
    </table>
   <p style="color: blue; font-weight: bold;font-size:18px;">
    Total Expenses: Rs. {{ number_format($total_expenses, 2) }}
</p>
</div>


@if($final_value > 0)

     <p style="color:green;font-size:20px;font-weight:bold;">Profit: Rs. {{ number_format($final_value, 2) }}</p>

@else

     <p style="color:red;font-size:20px;font-weight:bold;">Loss: Rs. {{ number_format(abs($final_value), 2) }}</p>

@endif


@elseif($start && $end)
    <p>No financial records found for the selected date range.</p>
@endif
  



@endsection

@section('js')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection
