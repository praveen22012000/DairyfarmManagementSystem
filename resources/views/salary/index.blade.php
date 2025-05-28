@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Salary Details</h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{ route('salary.create') }}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Make Salary
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Role</th>
                        <th>Base Salary</th>
                        <th>Effective From</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($salaries as $salary)
                    <tr>
                      

                        <td>{{$salary->id}}</td>
                        <td>{{$salary->role->role_name}}</td>
                       
                        <td>{{$salary->base_salary}}</td>
                        <td>{{$salary->effective_from}}</td>
                     
                        <td>
                                <a href="{{ route('salary.view', $salary->id) }}" class="btn btn-info">View</a>
                                <a href="{{ route('salary.edit', $salary->id) }}" class="btn btn-primary">Edit</a>
                                <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                        </td>
                 

                        
                    </tr>

                @endforeach
                <tbody>
            
                </tbody>
            </table>

            <form id="deleteForm" method="post" style="display:none;">
            @csrf
            @method('POST')
            </form>
            
            <div class="pt-2">
                <div class="float-right">
                   
            </div>
            </div>
        </div>
    </div>
</div>


</div>

@endsection




@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmCancel(orderId, type) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to cancel this order!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            let formId = (type === 'before') ? 'cancelBeforeForm-' : 'cancelAfterForm-';
            document.getElementById(formId + orderId).submit();
        }
    });
}
</script>
@endsection


