<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - StaffSync</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-[Inter] bg-gray-50">
    <div class="min-h-screen flex">
        <div class="hidden lg:block w-1/2 bg-indigo-600 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-blue-800 opacity-90"></div>
            <div class="relative z-10 flex flex-col justify-center h-full px-12 text-white">
                <h1 class="text-4xl font-bold mb-4">Manage Your Workforce Efficiently</h1>
                <p class="text-lg text-indigo-100">Integrated employee management system for maximum productivity. Sign in to access your dashboard.</p>
            </div>
            <div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 border-white/10 rounded-full"></div>
            <div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 border-white/10 rounded-full"></div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-slate-800">Welcome Back</h2>
                    <p class="text-slate-500 mt-2">Please sign in with your Email or Phone Number.</p>
                </div>

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email or Phone Number</label>
                        <input type="text" name="identity" required autofocus
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            placeholder="e.g. 0812xxx or admin@staffsync.com">
                        @error('identity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            placeholder="••••••••">
                    </div>

                    <button type="submit" 
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg shadow-lg transition duration-200 transform hover:-translate-y-0.5">
                        Sign In
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-slate-400">
                    &copy; 2026 StaffSync System
                </p>
            </div>
        </div>
    </div>
</body>
</html>