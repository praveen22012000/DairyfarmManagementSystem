@extends('layouts.admin.master')

@section('content')

<div class="col-md-12">
    <h2>Pay Salary</h2>

    <form action="" method="POST">
        @csrf

        <!-- Month Dropdown -->
        <div class="form-group">
            <label for="month">Select Month</label>
            <select name="month" id="month" class="form-control" required>
                <option value="">-- Select Month --</option>
                @for ($i = 1; $i <= 12; $i++)
                    @php
                        $date = \Carbon\Carbon::create(null, $i, 1);
                        $monthValue = date('Y') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    @endphp
                    <option value="{{ $monthValue }}">{{ $monthValue }}</option>
                @endfor
            </select>
        </div>

        <!-- Employee Dropdown -->
        <div class="form-group">
            <label for="employee_id">Employee</label>
             <select name="employee_id" id="employee_id" class="form-control" disabled required>
                <option value="">Select a month first...</option>
            </select>
        </div>

        <!-- Amount Paid -->
        <div class="form-group">
            <label for="amount_paid">Amount Paid</label>
            <input type="number" name="amount_paid" class="form-control" step="0.01" required>
        </div>

        <!-- Payment Date -->
        <div class="form-group">
            <label for="payment_date">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <!-- Payment Status -->
        <div class="form-group">
            <label for="payment_status">Payment Status</label>
            <select name="payment_status" class="form-control" required>
                <option value="Paid">Paid</option>
                <option value="Unpaid">Unpaid</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>



@endsection

@section('js')

<script>
$(document).ready(function () {
    $('#month').on('change', function () {
        const selectedMonth = $(this).val();
        const $employeeSelect = $('#employee_id');

        if (selectedMonth) {
            $.getJSON(`/monthly_salary/get-eligible-employees?month=${selectedMonth}`, function (data) {
                $employeeSelect.empty(); // Clear existing options

                if (data.length > 0) {
                    $employeeSelect.prop('disabled', false);
                    $employeeSelect.append('<option value="">-- Select Employee --</option>');
                    $.each(data, function (index, emp) {
                        $employeeSelect.append(`<option value="${emp.id}">${emp.name} (${emp.role})</option>`);
                    });
                } else {
                    $employeeSelect.append('<option value="">No unpaid employees for this month</option>');
                    $employeeSelect.prop('disabled', true);
                }
            });
        } else {
            $employeeSelect.html('<option value="">Select a month first...</option>');
            $employeeSelect.prop('disabled', true);
        }
    });
});
</script>



@endsection
