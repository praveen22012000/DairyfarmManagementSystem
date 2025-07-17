
@extends('layouts.admin.master')
@section('title', 'Monthly Manufacturing Report')

@section('content')

<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
    <h1 style="text-align:center">Salary Allocation by Role Report</h1>

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
    <br>    
    <div>
        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date:</label>
        <input type="date" name="end_date" value="{{ old('end_date', $end) }}" 
            class="form-control rounded w-full" required>
    </div>
    <br>
    <div class="form-group">
                    <label for="role_id">Role</label>
                    <select name="role_id" class="form-control" required>
                        <option value="">Select the role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->role_name }}
                                </option>
                            @endforeach
                    </select>
            @error('role_id')<span class="text-danger">{{ $message }}</span>@enderror
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

    <form method="GET" action="{{ route('monthly_salary_for_role.pdf') }}" target="_blank">
        <input type="hidden" name="start_date" value="{{ $start }}">
        <input type="hidden" name="end_date" value="{{ $end }}">
        <input type="hidden" name="role_id" value="{{ old('role_id', request('role_id')) }}">
        <button type="submit" class="btn btn-danger mb-3">Download as PDF</button>
    </form>


 



<p>From <strong>{{ $start }}</strong> to <strong>{{ $end }}</strong></p>
      <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th class="px-4 py-2 border">Month</th>
                <th class="px-4 py-2 border">Total Amount(Rs.)</th>
            </tr>
        </thead>
        <tbody>
                @foreach($salaryPerMonth as $sal)
                <tr>
                    <td class="px-4 py-2 border">{{ $sal->month }}</td>
                    <td class="px-4 py-2 border">{{ $sal->total_salary}}</td>
                </tr>
               @endforeach
         
        </tbody>
    </table>

</div>
</div>
@endsection

@section('js')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function () {
        var $roleDropdown = $('select[name="role_id"]');
        var $hiddenRoleInput = $('form[action="{{ route('monthly_salary_for_role.pdf') }}"] input[name="role_id"]');

        // Update hidden input when role is selected
        $roleDropdown.on('change', function () {
            $hiddenRoleInput.val($(this).val());
        });

        // Initialize the hidden field with the selected value on page load
        $hiddenRoleInput.val($roleDropdown.val());
    });
</script>


@endsection
