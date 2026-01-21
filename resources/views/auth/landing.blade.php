<<<<<<< HEAD
@extends('layouts.guest')

@section('content')
=======
<x-guest-layout>

>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-black via-slate-900 to-blue-950">

    <div class="w-full max-w-md bg-slate-900 text-white rounded-2xl shadow-2xl p-8">

        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/logo.png') }}" class="w-20" alt="Logo">
        </div>

        {{-- Tabs --}}
        <div class="flex mb-6 rounded-lg overflow-hidden border border-slate-700">
<<<<<<< HEAD
            <button id="loginTab"
                class="w-1/2 py-2 font-semibold bg-blue-600">
                Log in
            </button>
            <button id="registerTab"
                class="w-1/2 py-2 font-semibold bg-slate-800">
=======
            <button id="loginTab" class="w-1/2 py-2 font-semibold bg-blue-600">
                Log in
            </button>
            <button id="registerTab" class="w-1/2 py-2 font-semibold bg-slate-800">
>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
                Register
            </button>
        </div>

        {{-- LOGIN --}}
        <div id="loginForm">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="text-sm text-gray-300">Email</label>
                    <input type="email" name="email"
<<<<<<< HEAD
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white focus:ring-blue-500">
=======
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-black">
>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
                </div>

                <div class="mb-4">
                    <label class="text-sm text-gray-300">Password</label>
                    <input type="password" name="password"
<<<<<<< HEAD
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white focus:ring-blue-500">
=======
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-black">
>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
                </div>

                <button class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded-lg font-semibold">
                    LOGIN
                </button>
            </form>
        </div>

        {{-- REGISTER --}}
        <div id="registerForm" class="hidden">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="text-sm text-gray-300">Name</label>
                    <input type="text" name="name"
<<<<<<< HEAD
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white">
=======
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-black">
>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
                </div>

                <div class="mb-3">
                    <label class="text-sm text-gray-300">Email</label>
                    <input type="email" name="email"
<<<<<<< HEAD
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white">
=======
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-black">
>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
                </div>

                <div class="mb-3">
                    <label class="text-sm text-gray-300">Password</label>
                    <input type="password" name="password"
<<<<<<< HEAD
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white">
=======
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-black">
>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
                </div>

                <div class="mb-4">
                    <label class="text-sm text-gray-300">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full mt-1 rounded-md bg-slate-800 border border-slate-700 text-white">
                </div>

                <button class="w-full bg-green-600 hover:bg-green-700 py-2 rounded-lg font-semibold">
                    SIGN UP
                </button>
            </form>
        </div>

    </div>
</div>

<<<<<<< HEAD
{{-- Tab switch script --}}
=======
>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
<script>
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginTab.onclick = () => {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        loginTab.classList.add('bg-blue-600');
        registerTab.classList.remove('bg-blue-600');
        registerTab.classList.add('bg-slate-800');
    };

    registerTab.onclick = () => {
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        registerTab.classList.add('bg-blue-600');
        loginTab.classList.remove('bg-blue-600');
        loginTab.classList.add('bg-slate-800');
    };
</script>
<<<<<<< HEAD
@endsection
=======

</x-guest-layout>
>>>>>>> cfdab82532137dcf11357ba9e5497a2d9a6418d1
