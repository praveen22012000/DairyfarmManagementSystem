@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Task Assignment Details</h2>
                </div>

                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{ route('tasks_assignment.create') }}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Assign Task
                    </a>
               
                </div>

            </div>
            
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            <table id="tasksAssignmentTable" class="table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
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
                      

                        <td>{{$assigned_task->id}}</td>
                        <td>{{$assigned_task->task->title}}</td>
                       
                        <td>{{$assigned_task->farm_labore->user->name}}</td>
                        <td>{{$assigned_task->assigned_date}}</td>
                        <td>{{$assigned_task->due_date }}</td>
                        <td>{{$assigned_task->status}}</td>
                       
                 

                        <td>

                        
                            <a href="{{ route('tasks_assignment.view',$assigned_task->id) }}" class="btn btn-info">View</a>
                           
                            <a href="{{ route('tasks_assignment.edit',$assigned_task->id) }}" class="btn btn-primary">Edit</a>
                            <button class="btn btn-danger" onclick="">Delete</button>

                            @if($assigned_task->status== 'pending' || $assigned_task->status== 'rejected')
                                    @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 6 )
                                        <a href="{{ route('task-assignments.reassign-form', $assigned_task->id) }}" class="btn btn-info">Re-Assign Labore</a>
                                    @endif
                            @endif

                            @if ($assigned_task->status == 'waiting_approval')

                                @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 6)
                                    <!-- Reject Button that triggers the modal -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            Approve Task
                        </button>

                        <!-- Modal -->
                            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('task_execution.approve', $assigned_task->id) }}" method="POST">
                                        @csrf
              
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel">Review Task</h5>
                                        </div>

                                    <div class="modal-body">
                                        <textarea name="review" class="form-control" rows="4" required placeholder="Give feedback about this task"></textarea>
                                    </div>

                                    <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Submit Review</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                            </div>
                    </form>
                </div>
                </div>
                    @endif
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Add this before the closing </body> tag -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

<script>
$(document).ready(function() {
    $('#tasksAssignmentTable').DataTable({
        "pageLength": 10,  // Optional: Sets how many rows per page
        "lengthMenu": [5, 10, 25, 50, 100],
        "language": {
            "search": "Search For Task Assignment records:"
        }
    });
});
</script>

@endsection


