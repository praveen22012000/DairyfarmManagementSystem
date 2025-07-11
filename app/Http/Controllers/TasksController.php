<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    //

    public function index()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $tasks=Tasks::all();

        return view('farm_tasks.index',['tasks'=>$tasks]);
    }

    public function create()
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        return view('farm_tasks.create');
    }

    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }


        $request->validate([
            'title'=>'required|unique:tasks,title',
            'description'=>'required'
        ]);

        Tasks::create([
            'title'=>$request->title,
            'description'=>$request->description
        ]);

        return redirect()->route('tasks.list')->with('success', 'Task create successfully!');
    }

    public function edit(Tasks $task)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        return view('farm_tasks.edit',['task'=>$task]);
    }


    public function update(Request $request,Tasks $task)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }

        $data= $request->validate([
            'title' => 'required|unique:tasks,title,' . $task->id,// Ignore the current record with this id when checking for duplicates.
            'description'=>'required'
        ]);

        $task->update($data);

        return redirect()->route('tasks.list')->with('success', 'Task record updated successfully!');
    }

    public function view(Tasks $task)
    {
        if (!in_array(Auth::user()->role_id, [1,6])) 
        {
            abort(403, 'Unauthorized action.');
        }
        return view('farm_tasks.view',['task'=>$task]);
    }
}
