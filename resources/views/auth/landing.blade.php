@extends('layouts.landing')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-black via-slate-900 to-blue-950 flex items-start py-12">

    <div class="mx-auto bg-slate-900 shadow-2xl flex w-[1100px] max-w-[95vw] min-h-[650px] rounded-xl overflow-hidden">

        <div class="w-[45%] flex flex-col items-center justify-center p-10 bg-slate-800">
            <img src="{{ asset('images/logo.png') }}" class="w-40 mb-6" alt="Logo">
            <h1 class="text-white text-2xl font-bold text-center tracking-wide">
                AFPPGMC<br>INVENTORY
            </h1>
        </div>

        <div class="w-[55%] p-12 text-gray-100 flex flex-col overflow-y-auto">

            <div class="flex mb-8 rounded-lg overflow-hidden border border-slate-700">
                <button id="loginTab" class="w-1/2 py-3 font-semibold text-white bg-blue-600 transition">
                    Log in
                </button>
                <button id="registerTab" class="w-1/2 py-3 font-semibold text-gray-300 bg-slate-800 transition">
                    Register
                </button>
            </div>

            <!-- LOGIN -->
            <div id="loginForm">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm mb-1">Email</label>
                        <input type="text" name="login" placeholder="Email or Username" class="input-dark w-full" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm mb-1">Password</label>
                        <input type="password" name="password" class="input-dark w-full">
                    </div>

                    <button class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-lg font-semibold">
                        LOGIN
                    </button>
                </form>
            </div>

            @if ($errors->any())
                <div class="mb-4 text-red-400 text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- REGISTER -->
            <div id="registerForm" class="hidden">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <input name="first_name" placeholder="First Name" class="input-dark" required>
                        <input name="middle_name" placeholder="Middle Name" class="input-dark" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <input name="last_name" placeholder="Last Name" class="input-dark" required>
                        <input name="suffix" placeholder="Suffix (optional)" class="input-dark">
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm mb-2">Sex</label>
                        <div class="flex gap-8">
                            <label><input type="radio" name="sex" value="female" required> Female</label>
                            <label><input type="radio" name="sex" value="male" required> Male</label>
                        </div>
                    </div>
                    
                    <select name="category_id" class="input-dark w-full mb-5">
                        <option value="">Select Category</option>
                    </select>

                    <select name="unit" class="input-dark w-full mb-5">
                        <option value="">Select Unit</option>
                        @foreach (['BDCU','CUI','COMMAND','ISU','LSO','PAU','PG1','PG3','PG4','PG10','PPBU'] as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>

                    <input name="username" placeholder="Username" class="input-dark mb-5" required>
                    <input name="email" placeholder="Email" class="input-dark mb-5" required>
                    <input type="password" name="password" placeholder="Password" class="input-dark mb-6" required>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="input-dark mb-6" required>

                    <button
                        class="w-full bg-green-600 hover:bg-green-700 py-3 rounded-lg font-semibold">
                        SIGN UP
                    </button>
                </form>
            </div>
        </div>



<script>
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginTab.onclick = () => {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        loginTab.classList.add('bg-blue-600','text-white');
        registerTab.classList.remove('bg-blue-600','text-white');
    };

    registerTab.onclick = () => {
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        registerTab.classList.add('bg-blue-600','text-white');
        loginTab.classList.remove('bg-blue-600','text-white');
    };
</script>

@endsection