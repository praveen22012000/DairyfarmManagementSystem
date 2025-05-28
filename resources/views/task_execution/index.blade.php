@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Task Execution Details</h2>
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
                       
                        <th>Task</th>
                        <th>Farm Labore</th>
                        <th>Assigned Date</th>
                        <th>Due Date </th>
                        <th>Status</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach( $assigned_tasks as  $assigned_task)
                    <tr>
                      

                        
                        <td>{{$assigned_task->task->title}}</td>
                       
                        <td>{{$assigned_task->farm_labore->user->name  }}</td>
                      
                        <td>{{$assigned_task->assigned_date}}</td>
                        <td>{{$assigned_task->due_date }}</td>
                        <td>{{$assigned_task->status}}</td>
                       
                 

                        <td>

                        @if ($assigned_task->status == 'pending')
                                    <!-- Reject Button that triggers the modal -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            Reject Task
                        </button>

                    <!-- Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('task_execution.reject', $assigned_task->id) }}" method="POST">
                @csrf
              
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reason for Rejection</h5>
                        
                    </div>
                    <div class="modal-body">
                        <textarea name="rejected_reason" class="form-control" rows="4" required placeholder="Explain why you're rejecting this task..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Submit Rejection</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif



                            @if($assigned_task->status == 'pending')
                                <form method="POST" action="{{ route('task_execution.start', $assigned_task->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-primary">Start Task</button>
                                </form>
                            @elseif($assigned_task->status == 'in_progress')
                                <form method="POST" action="{{ route('task_execution.complete', $assigned_task->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-warning">Mark as Done</button>
                                </form>
                            @else
                                <em>No actions</em>
                            @endif
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



<!-- Add this before the closing </body> tag -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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


