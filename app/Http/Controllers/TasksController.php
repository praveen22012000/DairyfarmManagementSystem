<?php

namespace App\Http\Controllers;

use App\Models\Tasks;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    //

    public function index()
    {
        $tasks=Tasks::all();

        return view('farm_tasks.index',['tasks'=>$tasks]);
    }

    public function create()
    {
        return view('farm_tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
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
        return view('farm_tasks.edit',['task'=>$task]);
    }


    public function update(Request $request,Tasks $task)
    {
        $data= $request->validate([
            'title'=>'required',
            'description'=>'required'
        ]);

        $task->update($data);

        return redirect()->route('tasks.list')->with('success', 'Task record updated successfully!');
    }

    public function view(Tasks $task)
    {
        return view('farm_tasks.view',['task'=>$task]);
    }
}
