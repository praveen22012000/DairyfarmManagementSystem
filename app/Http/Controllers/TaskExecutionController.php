<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class TaskExecutionController extends Controller
{
    //this is for the index page of the task execution table
    public function myTasks()
    {
        $user = Auth::user();//this line is used to get the currently logged in user.
        $farmLabore = $user->farm_labore; // relationship from User model

    // Allow only farm owners or farm_labores to access
    if (!($user->role_id == 1 || $farmLabore || $user->role_id == 6)) {
        abort(403, 'You are not authorized to access these tasks.');
    }
        
        // If the user is a farm labore, show their tasks
    if ($farmLabore) 
    {
        // in here,task_assignment and task are relationships // this used to get the tasks only associated with the paricular taboe
        $assigned_tasks = $farmLabore->task_assignment()->with('task')->get();
    } 
    // If the user is a farm owner (role_id == 1), show all tasks
    else if ($user->role_id == 1) 
    {
        $assigned_tasks = \App\Models\TaskAssignment::with(['task', 'farm_labore'])->get();     
    }

     else if ($user->role_id == 6) 
    {
        $assigned_tasks = \App\Models\TaskAssignment::with(['task', 'farm_labore'])->get();     
    }
        
        return view('task_execution.index', compact('assigned_tasks'));
    }

    
    public function startTask($id)
    {
        $user = Auth::user();//this line is used to get the currently logged in user.
        $farmLabore = $user->farm_labore; // relationship from User model

        // Allow only farm owners or farm_labores to access
        if (!($user->role_id == 1 || $farmLabore)) 
        {
        abort(403, 'You are not authorized to access these tasks.');
        }

        $assignment = TaskAssignment::findOrFail($id);

        // Ensure the logged-in user is the one assigned
        if (Auth::user()->farm_labore->id !== $assignment->assigned_to) 
        {
        abort(403, 'Unauthorized');
        }

        if ($assignment->status !== 'pending') 
        {
        return back()->with('error', 'Task cannot be started.');
        }

        $assignment->status = 'in_progress';
        $assignment->started_at = now(); // optional timestamp
        $assignment->save();

        return back()->with('success', 'Task started successfully.');
    }

    public function submitForApproval($id)
    {
         $user = Auth::user();//this line is used to get the currently logged in user.
        $farmLabore = $user->farm_labore; // relationship from User model

        // Allow only farm owners or farm_labores to access
        if (!($user->role_id == 1 || $farmLabore)) 
        {
        abort(403, 'You are not authorized to access these tasks.');
        }
        
        $assignment = TaskAssignment::findOrFail($id);

        if (Auth::user()->farm_labore->id !== $assignment->assigned_to) 
        {
        abort(403, 'Unauthorized');
        }
        if ($assignment->status !== 'in_progress') 
        {
        return back()->with('error', 'Task is not in progress.');
        }

        $assignment->status = 'waiting_approval';
        $assignment->completed_at = now(); // optional timestamp
        $assignment->save();

        return back()->with('success', 'Task submitted for approval.');
    }

    public function approveTask(Request $request,$id)
    {

          // Validate the input
          $request->validate([
            'review' => 'required|string|max:1000',
            ]);



        $assignment = TaskAssignment::findOrFail($id);

        // Make sure only managers can approve
        /*  if (!Auth::user()->hasRole('general_manager')) 
        {
        abort(403, 'Unauthorized');
        }
            */
        if ($assignment->status !== 'waiting_approval') 
        {
        return back()->with('error', 'Task is not waiting for approval.');
        }

        $assignment->status = 'approved';
        $assignment->review = $request->review; // store manager review
        $assignment->approved_at = now(); // optional
        $assignment->save();

        // Set labore as available again
        $assignment->farm_labore->update(['status' => 'Available']);

        return back()->with('success', 'Task is approved successfully.');
    
    }

    public function reject(Request $request, $id)
    {
        // Validate the input
        $request->validate([
        'rejected_reason' => 'required|string|max:1000',
        ]);

        // Find the task assignment
        $assignment = TaskAssignment::findOrFail($id);

        // Ensure only the assigned labore can reject it
        if (Auth::user()->farm_labore->id !== $assignment->assigned_to) 
        {
        abort(403, 'Unauthorized action.');
        }

        // Check if task is still pending
        if ($assignment->status !== 'pending') 
        {
        return back()->with('error', 'Only pending tasks can be rejected.');
        }

        // Update the status and save rejection reason
        $assignment->status = 'rejected';
        $assignment->rejected_reason = $request->rejected_reason;
        $assignment->save();

        $assignment->farm_labore->update(['status' => 'Available']);

        return redirect()->back()->with('success', 'Task rejected successfully.');
    }


    public function reassign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:farm_labores,id',
            'due_date' => 'required|date',
        ]);

        $assignment = TaskAssignment::findOrFail($id);

        // Make sure the task was rejected
        if ($assignment->status !== 'rejected') 
        {
        return back()->with('error', 'Only rejected tasks can be reassigned.');
        }

        // Update assignment
        $assignment->assigned_to = $request->assigned_to;
        $assignment->due_date = $request->due_date;
        $assignment->assigned_date = now();
        $assignment->status = 'pending';
        $assignment->rejected_reason = null; // Clear previous rejection reason
        $assignment->save();

        // Update labore status to "Busy"
        $newLabore = FarmLabore::find($request->assigned_to);
        $newLabore->status = 'Busy';
        $newLabore->save();

    return redirect()->route('task-assignments.index')->with('success', 'Task reassigned successfully.');
    }


}
