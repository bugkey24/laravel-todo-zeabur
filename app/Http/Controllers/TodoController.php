<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = \App\Models\Todo::orderBy('created_at', 'desc')->get();
        return view('todos.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        \App\Models\Todo::create([
            'title' => $request->title,
        ]);

        return back()->with('success', 'Todo added successfully!');
    }

    public function update(Request $request, \App\Models\Todo $todo)
    {
        $todo->update([
            'is_completed' => !$todo->is_completed,
        ]);

        return back()->with('success', 'Todo updated!');
    }

    public function destroy(\App\Models\Todo $todo)
    {
        $todo->delete();

        return back()->with('success', 'Todo deleted!');
    }
}
