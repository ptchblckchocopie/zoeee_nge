<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskCompletedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
        public function index()
    {
        $userId = Auth::id();
        $todos = Task::where('user_id', $userId)->where('completed', false)->get();
        $completedTodos = Task::where('user_id', $userId)->where('completed', true)->get();

        $dailyCompletion = $this->calculateCompletionRate($userId, 'day');
        $weeklyCompletion = $this->calculateCompletionRate($userId, 'week');
        $monthlyCompletion = $this->calculateCompletionRate($userId, 'month');

        $completedTasks = $completedTodos->map(function($task) {
            return [
                'title' => $task->title,
                'start' => $task->updated_at->toDateString(),
                'description' => $task->description,
                'id' => $task->id
            ];
        });

        return view('task.index', [
            'todos' => $todos,
            'completedTodos' => $completedTodos,
            'dailyCompletion' => $dailyCompletion,
            'weeklyCompletion' => $weeklyCompletion,
            'monthlyCompletion' => $monthlyCompletion,
            'completedTasks' => $completedTasks
        ]);
    }


    private function calculateCompletionRate($userId, $period)
    {
        $start = match ($period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            default => now()
        };

        $totalTasks = Task::where('user_id', $userId)->where('created_at', '>=', $start)->count();
        $completedTasks = Task::where('user_id', $userId)
            ->where('completed', true)
            ->where('created_at', '>=', $start)
            ->count();

        return $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
    }

    public function store(TodoRequest $request)
    {
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'completed' => false,
            'user_id' => Auth::id()
        ]);
    
        $request->session()->flash('alert-success', 'Task Created Successfully');
        return redirect()->route('tasks.index');
    }

    public function show($id)
    {
        $todo = $this->getUserTaskById($id);
        if (!$todo) {
            session()->flash('error', 'Unable to locate the task');
            return redirect()->route('tasks.index');
        }
        return view('Task.show', compact('todo'));
    }

    public function edit($id)
    {
        $todo = $this->getUserTaskById($id);
        if (!$todo) {
            session()->flash('error', 'Unable to locate the task');
            return redirect()->route('tasks.index');
        }
        return view('Task.edit', compact('todo'));
    }

    public function update(TodoRequest $request, $id)
{
    $todo = $this->getUserTaskById($id);
    if (!$todo) {
        $request->session()->flash('error', 'Unable to locate the task');
        return redirect()->route('tasks.index');
    }

    // Update title and description
    $todo->title = $request->title;
    $todo->description = $request->description;

    // Check if 'completed' checkbox is checked
    $todo->completed = $request->input('completed') == 1 ? true : false;

    // Save the updated task
    $todo->save();

    $request->session()->flash('alert-info', 'Task Updated Successfully');
    return redirect()->route('tasks.index');
}



public function destroy($id)
{
    $todo = $this->getUserTaskById($id);
    if (!$todo) {
        session()->flash('error', 'Unable to locate the task');
        return redirect()->route('tasks.index');
    }

    $todo->delete();
    session()->flash('alert-success', 'Task Deleted Successfully');
    return redirect()->route('tasks.index');
}


    public function destroyCompleted($id)
    {
        $userId = Auth::id();
        Log::info('Attempting to delete completed task', ['task_id' => $id, 'user_id' => $userId]);

        $todo = Task::where('id', $id)->where('user_id', $userId)->where('completed', true)->first();

        if (!$todo) {
            Log::error('Unable to locate the completed task', ['task_id' => $id, 'user_id' => $userId]);
            session()->flash('error', 'Unable to locate the completed task');
            return redirect()->route('tasks.completed');
        }

        $todo->delete();
        Log::info('Completed task deleted successfully', ['task_id' => $id]);
        session()->flash('alert-success', 'Completed task deleted successfully');
        return redirect()->route('tasks.completed');
    }

    public function complete($id)
    {
        $userId = Auth::id();
        Log::info('User ID: ' . $userId . ' is completing task with ID: ' . $id);
        $todo = Task::where('id', $id)->where('user_id', $userId)->first();
    
        if (!$todo) {
            session()->flash('error', 'Unable to locate the task');
            return redirect()->route('tasks.index');
        }

        $todo->completed = true;
        $todo->save();

        // Send the email
        try {
            Mail::to(Auth::user()->email)->send(new TaskCompletedMail($todo->title, $todo->description));
            Log::info('Email sent for completed task: ' . $todo->title);
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }

        session()->flash('alert-success', 'Task marked as completed and email sent!');
        return redirect()->route('tasks.completed');
    }

    public function completed()
    {
        $userId = Auth::id();
        $completedTodos = Task::where('user_id', $userId)->where('completed', true)->get();
        return view('task.completed', ['completedTodos' => $completedTodos]);
    }

    private function getUserTaskById($id)
    {
        return Task::where('id', $id)->where('user_id', Auth::id())->first();
    }
}
