<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tasks;
use App\Models\FarmLabore;
use App\Models\TaskAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskAssignmentNotification;
use App\Mail\ReAssignFarmLaboreNotification;

class TaskAssignmentController extends Controller
{
    //
    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $assigned_tasks= TaskAssignment::with(['task','user','farm_labore'])->get();

        return view('task_assignment.index',['assigned_tasks'=>$assigned_tasks]);

    }

    public function create()
    {
         if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $tasks=Tasks::with(['task_assignment'])->get();

        $farm_labores=FarmLabore::where('status','Available')->with(['user'])->get();

        return view('task_assignment.create',['tasks'=>$tasks,'farm_labores'=>$farm_labores]);
    }

    public function store(Request $request)
    {
         if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'task_id'=>'required|exists:tasks,id',
            'assigned_to'=>'required|exists:farm_labores,id',
            'due_date'=>'required|after_or_equal:today'
        ]);

        $task_assignment = TaskAssignment::create([
            'task_id'=>$request->task_id,
            'assigned_by'=>Auth::id(),
            'assigned_to'=>$request->assigned_to,
            'due_date'=>$request->due_date,
            'assigned_date'=>Carbon::now(),

            
        ]);

         // Update the status of the assigned farm labore to 'Busy'
        FarmLabore::where('id', $request->assigned_to)->update([
        'status' => 'Busy'
        ]);

           Mail::to('pararajasingampraveen22@gmail.com')->send(new TaskAssignmentNotification($task_assignment));

        return redirect()->route('tasks_assignment.list')->with('success', 'Task assigned successfully!');
    }

    public function view(TaskAssignment $taskassignment)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $tasks=Tasks::with(['task_assignment'])->get();

        $farm_labores=FarmLabore::with(['user'])->get();

        return view('task_assignment.view',['tasks'=>$tasks,'farm_labores'=>$farm_labores,'taskassignment'=>$taskassignment]);
    }

    public function edit(TaskAssignment $taskassignment)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $tasks=Tasks::with(['task_assignment'])->get();

        $farm_labores=FarmLabore::with(['user'])->get();

        return view('task_assignment.edit',['tasks'=>$tasks,'farm_labores'=>$farm_labores,'taskassignment'=>$taskassignment]);
    }

    public function update(TaskAssignment $taskassignment,Request $request)
    {
         if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

            $data=$request->validate
            ([
             'task_id'=>'required|exists:tasks,id',
            'assigned_to'=>'required|exists:farm_labores,id',
            'due_date'=>'required|after_or_equal:today'
            ]);

            $taskassignment->update
            ([
                    'task_id' => $data['task_id'],
                    'assigned_by' => Auth::id(), // You may or may not want to update this
                    'assigned_to' => $data['assigned_to'],
                    'due_date' => $data['due_date'],
                    'assigned_date' => Carbon::now(), // optional: update only if reassigning
            ]);

            return redirect()->route('tasks_assignment.list')->with('success', 'Task assigned updated successfully!');
    }

    public function showReassignForm(TaskAssignment $taskassignment)
    {
       
         if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
     
       // Get the currently assigned labore (even if busy)
        $currentLabore = FarmLabore::with('user')->find($taskassignment->assigned_to);

        // Get all available labores
        $availableLabores = FarmLabore::where('status', 'Available')->with('user')->get();

        // Merge both collections, and make sure no duplicates
        $farm_labores = $availableLabores;


        //Is there a currently assigned laborer? ($currentLabore is not null)
        //Is the currently assigned laborer NOT already in the list of available laborers?
        //This code ensures that the currently assigned farm labore (even if they are busy or not available) is still included in the dropdown list of labors in the task reassignment form.
        if ($currentLabore && !$availableLabores->contains('id', $currentLabore->id)) 
        {
            $farm_labores->push($currentLabore);
        }
 
        return view('re_assign_labore_for_task.create', ['taskassignment' => $taskassignment,'farm_labores' => $farm_labores]);


    }

    
    public function reassign(Request $request,TaskAssignment $taskassignment)
    {
   
     
    
        $request->validate([
        'assigned_to' => 'required|exists:farm_labores,id',
        'due_date' => 'required|after_or_equal:today'
        ]);

         //  $assignment = TaskAssignment::findOrFail($id);

        // Make the old labore available again
        $oldLabore = FarmLabore::find($taskassignment->assigned_to);

        if ($oldLabore) 
        {
            $oldLabore->status = 'Available';
            $oldLabore->save();
        }

        // Update task assignment
        $taskassignment->assigned_to = $request->assigned_to;
        $taskassignment->due_date = $request->due_date;
        $taskassignment->assigned_date = Carbon::now();
        $taskassignment->status = 'pending';
        $taskassignment->rejected_reason = null; // reset old rejection reason
        $taskassignment->save();

        // Mark new labore as busy
        $newLabore = FarmLabore::find($request->assigned_to);

        if ($newLabore) 
        {
            $newLabore->status = 'Busy';
            $newLabore->save();
        }

         Mail::to('pararajasingampraveen22@gmail.com')->send(new ReAssignFarmLaboreNotification($taskassignment));

        return redirect()->route('tasks_assignment.list')->with('success', 'Task is re-assigned successfully!');
   
    }

    
}
