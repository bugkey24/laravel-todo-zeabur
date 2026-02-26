<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Todo App</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <!-- Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                    }
                }
            }
        </script>
    </head>
    <body class="bg-slate-100 font-sans antialiased text-slate-800 min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden p-8 border border-slate-100">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600 mb-6 text-center">Tasks</h1>
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-lg text-sm mb-6 border border-emerald-100 transition-all">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('todos.store') }}" method="POST" class="mb-8">
                @csrf
                <div class="flex gap-2">
                    <input type="text" name="title" required placeholder="What needs to be done?" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-sm placeholder-slate-400">
                    <button type="submit" 
                        class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors shadow-sm shadow-indigo-600/30 flex-shrink-0">
                        Add
                    </button>
                </div>
                @error('title')
                    <span class="text-sm text-red-500 mt-2 block">{{ $message }}</span>
                @enderror
            </form>

            <!-- Todo List -->
            <ul class="space-y-3">
                @forelse($todos as $todo)
                    <li class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors border border-slate-100 group">
                        <div class="flex items-center gap-4 flex-1">
                            <form action="{{ route('todos.update', $todo) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center w-6 h-6 rounded-full border-2 transition-colors {{ $todo->is_completed ? 'bg-indigo-500 border-indigo-500' : 'border-slate-300 hover:border-indigo-400 bg-white' }}">
                                    @if($todo->is_completed)
                                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                            <span class="text-sm font-medium transition-colors {{ $todo->is_completed ? 'line-through text-slate-400' : 'text-slate-700' }}">
                                {{ $todo->title }}
                            </span>
                        </div>
                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="m-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Delete">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="p-6 text-center text-slate-500 border-2 border-dashed border-slate-200 rounded-xl">
                        No tasks yet. Enjoy your day!
                    </li>
                @endforelse
            </ul>
            
            @if($todos->count() > 0)
                <div class="mt-6 text-center text-xs text-slate-400 font-medium">
                    {{ $todos->where('is_completed', true)->count() }} of {{ $todos->count() }} completed
                </div>
            @endif
        </div>
    </body>
</html>
