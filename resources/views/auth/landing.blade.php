<<<<<<<<< Temporary merge branch 1
@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-slate-900 to-blue-950">

    <div class="w-full max-w-md bg-slate-900 text-white rounded-2xl shadow-2xl p-8">
=========
<x-guest-layout>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-slate-900 to-blue-950">

    <div class="w-full max-w-md bg-slate-900 text-gray-100 rounded-2xl shadow-2xl p-8">
>>>>>>>>> Temporary merge branch 2

        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo.png') }}" class="w-20" alt="Logo">
        </div>

        {{-- Tabs --}}
        <div class="flex mb-6 rounded-lg overflow-hidden border border-slate-700">
<<<<<<<<< Temporary merge branch 1
            <button id="loginTab"
                class="w-1/2 py-2 font-semibold bg-blue-600">
                Log in
            </button>
            <button id="registerTab"
                class="w-1/2 py-2 font-semibold bg-slate-800">
=========
            <button id="loginTab" class="w-1/2 py-2 font-semibold text-white bg-blue-600">
                Log in
            </button>
            <button id="registerTab" class="w-1/2 py-2 font-semibold text-white bg-slate-800">
>>>>>>>>> Temporary merge branch 2
                Register
            </button>
        </div>

            <!-- LOGIN -->
            <div id="loginForm">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                <div class="mb-4">
<<<<<<<<< Temporary merge branch 1
                    <label class="text-sm text-gray-300">Email</label>
                    <input type="email" name="email"
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="text-sm text-gray-300">Password</label>
                    <input type="password" name="password"
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white focus:ring-blue-500">
=========
                    <label class="text-sm font-medium text-white">Email</label>
                    <input type="email" name="email"   
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-gray-100 placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="text-sm text-white">Password</label>
                    <input type="password" name="password"
                        class="w-full mt-1 rounded-md
                        bg-slate-800
                        border border-slate-700
                        text-gray-100
                        placeholder-gray-400
                        focus:border-blue-500
                        focus:ring-blue-500
                        autofill:bg-slate-800
                        autofill:text-gray-100">
>>>>>>>>> Temporary merge branch 2
                </div>

                <button class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded-lg font-semibold">
                    LOGIN
                </button>
            </form>
        </div>

            <!-- REGISTER -->
            <div id="registerForm" class="hidden">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                <div class="mb-3">
<<<<<<<<< Temporary merge branch 1
                    <label class="text-sm text-gray-300">Name</label>
                    <input type="text" name="name"
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white">
                </div>

                <div class="mb-3">
                    <label class="text-sm text-gray-300">Email</label>
                    <input type="email" name="email"
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white">
=========
                    <label class="text-sm text-white">Name</label>
                    <input type="text" name="name"
                        class="w-full mt-1 rounded-md
                        bg-slate-800
                        border border-slate-700
                        text-gray-100
                        placeholder-gray-400
                        focus:border-blue-500
                        focus:ring-blue-500
                        autofill:bg-slate-800
                        autofill:text-gray-100">
                </div>

                <div class="mb-3">
                    <label class="text-sm text-white">Email</label>
                    <input type="email" name="email"
                        class="w-full mt-1 rounded-md
                        bg-slate-800
                        border border-slate-700
                        text-gray-100
                        placeholder-gray-400
                        focus:border-blue-500
                        focus:ring-blue-500
                        autofill:bg-slate-800
                        autofill:text-gray-100">
>>>>>>>>> Temporary merge branch 2
                </div>

                <div class="mb-3">
                    <label class="text-sm text-gray-300">Password</label>
                    <input type="password" name="password"
<<<<<<<<< Temporary merge branch 1
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white">
=========
                        class="w-full mt-1 rounded-md
                        bg-slate-800
                        border border-slate-700
                        text-gray-100
                        placeholder-gray-400
                        focus:border-blue-500
                        focus:ring-blue-500
                        autofill:bg-slate-800
                        autofill:text-gray-100">
>>>>>>>>> Temporary merge branch 2
                </div>

                <div class="mb-4">
                    <label class="text-sm text-gray-300">Confirm Password</label>
                    <input type="password" name="password_confirmation"
<<<<<<<<< Temporary merge branch 1
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white">
=========
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-gray-100">
>>>>>>>>> Temporary merge branch 2
                </div>

                    <button
                        class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-lg font-semibold">
                        SIGN UP
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>


<<<<<<<<< Temporary merge branch 1
{{-- Tab switch script --}}
=========
>>>>>>>>> Temporary merge branch 2
<script>
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginTab.onclick = () => {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');

        loginTab.classList.add('bg-blue-600','text-white');
        loginTab.classList.remove('bg-slate-800','text-gray-300');

        registerTab.classList.add('bg-slate-800','text-gray-300');
        registerTab.classList.remove('bg-blue-600','text-white');
    };

    registerTab.onclick = () => {
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');

        registerTab.classList.add('bg-blue-600','text-white');
        registerTab.classList.remove('bg-slate-800','text-gray-300');

        loginTab.classList.add('bg-slate-800','text-gray-300');
        loginTab.classList.remove('bg-blue-600','text-white');
    };
</script>
<<<<<<<<< Temporary merge branch 1
@endsection
=========

</x-guest-layout>
>>>>>>>>> Temporary merge branch 2
