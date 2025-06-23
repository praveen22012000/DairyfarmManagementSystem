@extends('layouts.admin.master')
@section('title','Animal List')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <h2>Retailor Order Details</h2>
                </div>

                  @if (Auth::user()->role_id == 1 || Auth::user()->role_id == 3)
                <div class="float-right">

                    <a class="btn btn-success btn-md btn-rounded" href="{{ route('retailor_order_items.create') }}">
                        <i class="mdi mdi-plus-circle mdi-18px"></i> Make Order
                    </a>
               
                </div>
                @endif
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
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Retailor</th>
                        <th>Status </th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                       
                      
                    </tr>
                </thead>
                 
                @foreach($retailor_orders as $retailor_order)

              
                    <tr>
                     

                        <td>{{$retailor_order->id}}</td>
                        <td>{{$retailor_order->ordered_date}}</td>
                       
                        <td>{{$retailor_order->total_amount}}</td>
                        <td>{{$retailor_order->user->name}}</td>
                        <td >{{$retailor_order->status }}</td>
                        <td>{{$retailor_order->payment_status}}</td>
                       
                  

                        <td>

                    
                        @if( ($retailor_order->status == 'Pending' && Auth::user()->role_id == 7) ||  ($retailor_order->status == 'Pending' && Auth::user()->role_id == 1))
                            <a href="{{ route('manager.orders.review',$retailor_order->id)  }}">Review</a>
                        @endif

                        @if(  ($retailor_order->status == 'Pending' && Auth::user()->role_id == 1) || ($retailor_order->status == 'Pending' && Auth::user()->role_id == 3 && $retailor_order->user->id == auth()->id() )   )
                            <a href="{{ route('retailor_order_items.edit',$retailor_order->id) }}">Edit</a> 
                            |

                                <form action="{{ route('order.cancel_before_approval', $retailor_order->id) }}" id="cancelBeforeForm-{{ $retailor_order->id }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="button" class="btn btn-danger" onclick="confirmCancel({{ $retailor_order->id }}, 'before')">Cancel Before Approved</button>
                                </form>

                        @endif
                        
                        @if( ($retailor_order->status == 'Approved' && $retailor_order->payment_status == 'Unpaid' && $retailor_order->user->id == auth()->id()) || ($retailor_order->status == 'Approved' && $retailor_order->payment_status == 'Unpaid' && Auth::user()->role_id == 1) )

                        
                        <form action="{{ route('order.cancel_after_approval', $retailor_order->id) }}" id="cancelAfterForm-{{ $retailor_order->id }}" method="POST" style="display:inline;">
                            @csrf
                                <button type="button" class="btn btn-danger" onclick="confirmCancel({{ $retailor_order->id }}, 'after')">Cancel After Approved</button>
                        </form>
                                <a href="{{ route('upload_payment.receipt.create', $retailor_order->id) }}">Upload Payment</a>
                        @endif

                        @if($retailor_order->status == 'Approved' && $retailor_order->payment_status == 'Under Review')

                                @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 7)
                                <a href="{{ route('verify_payment.view',$retailor_order->id) }}">Veify Payment</a>
                                @endif

                                 @if(Auth::user()->role_id == 1 || $retailor_order->user->id == auth()->id())
                                <a href="{{ route('upload_payment.receipt.edit', $retailor_order->id) }}" >Edit Payment</a>
                                @endif

                            
                        @endif


                         @if($retailor_order->status == 'Approved' && $retailor_order->payment_status == 'Under Review' && $retailor_order->payment_attempts > 1)

                               @if(Auth::user()->role_id == 1 || $retailor_order->user->id == auth()->id())
                                {{-- Retailor: Cancel Payment --}}
                                {{ $retailor_order->user->id }}
                            <form action="{{ route('cancel_payment_receipt',$retailor_order->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-danger">Cancel Payment</button>
                            </form>
                                @endif
                            
                        @endif
                    

                        @if($retailor_order->payment_status == 'Paid' && $retailor_order->status == 'Ready for Delivery')
                            @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 7)
                            <a href="{{ route('assign_delivery_person.create',$retailor_order->id)  }}" >Assign Delivery Person</a>
                            @endif
                        @endif

                        @if($retailor_order->status == 'Assigned')
                             @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 7)
                            <a href="{{ route('re_assign_delivery_person.create', $retailor_order->id) }}" class="btn btn-warning">Re-Assign Delivery Person</a>
                            @endif
                        @endif


                        @if($retailor_order->status == 'Assigned')
                            @if(Auth::user()->role_id == 1 || $retailor_order->farm_labore->user->id == auth()->id())
                            <form action="{{ route('orders.startDelivery', $retailor_order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Start Delivery</button>
                            </form>
                            @endif
                        @endif


                        @if($retailor_order->status == 'Out for Delivery')
                              @if(Auth::user()->role_id == 1 || $retailor_order->farm_labore->user->id == auth()->id())
                            <form action="{{ route('orders.successful_delivery', $retailor_order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Delivered</button>
                            </form>
                            @endif
                        @endif

                        @if($retailor_order->payment_status == 'Paid')

                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 7 || $retailor_order->farm_labore->user->id == auth()->id() || $retailor_order->user->id == auth()->id())
                            <a href="{{ route('retailor_order.invoice',$retailor_order->id) }}">View Invoice</a>
                            @endif

                        @endif


                            <a href="{{ route('retailor_order_items.view',$retailor_order->id) }}">View</a>
                            
                    
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


