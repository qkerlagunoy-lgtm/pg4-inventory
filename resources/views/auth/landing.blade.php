@extends('layouts.landing')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-black via-slate-900 to-blue-950 flex items-start py-12">

    <!-- MAIN CONTAINER -->
    <div class="w-full mx-auto bg-slate-900 shadow-2xl grid grid-cols-1 md:grid-cols-2 max-w-7xl max-h-[85vh]">

        <!-- LEFT SIDE : LOGO + TITLE -->
        <div class="flex flex-col items-center justify-center p-10 bg-slate-800 h-full">
            <img src="{{ asset('images/logo.png') }}" class="w-40 mb-6" alt="Logo">

            <h1 class="text-white text-2xl font-bold text-center tracking-wide">
                AFPPGMC<br>INVENTORY
            </h1>
        </div>

        <!-- RIGHT SIDE : FORMS -->
        <div class="p-12 text-gray-100 flex flex-col overflow-y-auto scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-slate-800">

            <!-- TABS -->
            <div class="flex mb-8 rounded-lg overflow-hidden border border-slate-700">
                <button id="loginTab"
                    class="w-1/2 py-3 font-semibold text-white bg-blue-600 transition">
                    Log in
                </button>
                <button id="registerTab"
                    class="w-1/2 py-3 font-semibold text-gray-300 bg-slate-800 transition">
                    Register
                </button>
            </div>

            <!-- LOGIN -->
            <div id="loginForm">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-sm mb-1">Email</label>
                        <input type="email" name="email"
                            class="w-full rounded-md bg-slate-800 border border-slate-700
                                   text-gray-100 placeholder-gray-400
                                   focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm mb-1">Password</label>
                        <input type="password" name="password"
                            class="w-full rounded-md bg-slate-800 border border-slate-700
                                   text-gray-100 placeholder-gray-400
                                   focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <button
                        class="w-full bg-blue-600 hover:bg-blue-700 py-3 rounded-lg font-semibold">
                        LOGIN
                    </button>
                </form>
            </div>

            <!-- REGISTER -->
            <div id="registerForm" class="hidden">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- NAME ROW -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <input type="text" name="first_name" required
                        placeholder="First Name"
                        class="input-dark">

                    <input type="text" name="middle_name" required
                        placeholder="Middle Name"
                        class="input-dark">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <input type="text" name="last_name" required
                        placeholder="Last Name"
                        class="input-dark">

                    <input type="text" name="suffix"
                        placeholder="Suffix (optional)"
                        class="input-dark">
                </div>

                <!-- SEX -->
                <div class="mb-5">
                    <label class="block text-sm mb-2">Sex</label>
                    <div class="flex gap-8">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="sex" value="female" required>
                            Female
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="sex" value="male" required>
                            Male
                        </label>
                    </div>
                </div>

                <!-- CATEGORY -->
                <div class="mb-5">
                    <select name="category_id" required class="input-dark w-full">
                        <option value="">Select Category</option>
                    </select>
                </div>

                <!-- UNIT -->
                <div class="mb-5">
                    <select name="unit" required class="input-dark w-full">
                        <option value="">Select Unit</option>
                        @foreach (['BDCU','CUI','COMMAND','ISU','LSO','PAU','PG1','PG3','PG4','PG10','PPBU'] as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- USERNAME -->
                <div class="mb-5">
                    <input type="text" name="username" required
                        placeholder="Username"
                        class="input-dark">
                </div>

                <!-- EMAIL -->
                <div class="mb-5">
                    <input type="email" name="email" required
                        placeholder="Email"
                        class="input-dark">
                </div>

                <!-- PASSWORD -->
                <div class="mb-6">
                    <input type="password" name="password" required
                        placeholder="Password"
                        class="input-dark">
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


<!-- TAB TOGGLE SCRIPT -->
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

@endsection
