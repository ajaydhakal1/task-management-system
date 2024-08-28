@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Your Tasks</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Add Task</a>

                    @if(isset($tasks) && $tasks->isEmpty())
                        <p>You have no tasks.</p>
                    @elseif(isset($tasks))
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{ $task->name }}</td>
                                        <td>{{ $task->description }}</td>
                                        <td>{{ $task->status }}</td>
                                        <td>{{ $task->time }}</td>
                                        <td>
                                            <a href="{{ route('tasks.edit', $task->id) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Please Login to view your tasks.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection