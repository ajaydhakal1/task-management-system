<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * TaskController constructor.
     * Apply the authentication middleware.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's tasks.
     */
    public function index()
    {
        // Retrieve only the tasks of the authenticated user
        $tasks = Auth::user()->tasks;

        // Pass the $tasks variable to the 'tasks.index' view
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'time' => 'nullable|date',
        ]);

        // Create the task and associate it with the authenticated user
        Auth::user()->tasks()->create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        // Ensure the authenticated user can only view their own tasks
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this task.');
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        // Ensure the authenticated user can only edit their own tasks
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this task.');
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Ensure the authenticated user can only update their own tasks
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this task.');
        }

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'time' => 'nullable|date',
        ]);

        // Update the task with the new data
        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        // Ensure the authenticated user can only delete their own tasks
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this task.');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
