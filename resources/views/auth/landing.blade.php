@extends('layouts.landing')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-900 to-blue-950 flex items-center justify-center p-4">
    
    <!-- Main Container with Glass Effect -->
    <div class="bg-slate-900/70 backdrop-blur-lg border border-slate-700/50 shadow-2xl shadow-blue-900/20 rounded-2xl overflow-hidden flex w-full max-w-6xl min-h-[700px]">
        
        <!-- Left Panel - Branding -->
        <div class="hidden md:flex md:w-2/5 bg-gradient-to-br from-blue-900/80 to-slate-900 p-10 flex-col items-center justify-center relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-40 h-40 bg-blue-500 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 right-10 w-60 h-60 bg-indigo-500 rounded-full blur-3xl"></div>
            </div>
            
            <!-- Logo & Branding -->
            <div class="relative z-10 text-center">
                <img src="{{ asset('images/logo.png') }}" class="w-48 h-48 object-contain mb-6 mx-auto drop-shadow-2xl" alt="AFPPGMC Logo">
                <h1 class="text-3xl font-bold text-white mb-4 tracking-tight">
                    INVENTORY & LOGISTICS<br>MANAGEMENT SYSTEM
                </h1>
            </div>
        </div>

        <!-- Right Panel - Auth Forms -->
        <div class="w-full md:w-3/5 p-8 md:p-12 flex flex-col">
            
            <!-- Tab Navigation - Enhanced -->
            <div class="flex mb-10 rounded-xl overflow-hidden bg-slate-800/50 p-1">
                <button id="loginTab" class="flex-1 py-4 font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform hover:scale-[1.02]">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Log In
                    </div>
                </button>
                <button id="registerTab" class="flex-1 py-4 font-semibold text-gray-300 hover:text-white transition-all duration-300 ease-in-out hover:bg-slate-700/50 rounded-lg">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                        </svg>
                        Register
                    </div>
                </button>
            </div>

            <!-- Login Form -->
            <div id="loginForm">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
                    <p class="text-blue-200">Sign in to your account to continue</p>
                </div>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    Email or Username
                                </div>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="login" 
                                       placeholder="Enter your email or username" 
                                       class="input-dark w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                       required
                                       autofocus>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Password
                                </div>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password" 
                                       placeholder="Enter your password" 
                                       class="input-dark w-full pl-10 pr-10 py-3 bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                                       required>
                                <button type="button" class="absolute right-3 top-3.5 text-gray-400 hover:text-white" onclick="togglePassword(this)">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="mt-2 flex justify-end">
                                <a href="{{ route('password.request') }}" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
                                    Forgot password?
                                </a>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3.5 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Sign In
                        </button>
                    </div>
                </form>
            </div>

            @if ($errors->any())
                <div class="mt-6 p-4 bg-red-900/30 border border-red-700/50 rounded-lg animate-pulse">
                    <div class="flex items-center gap-2 text-red-300 mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">Please check the following:</span>
                    </div>
                    <ul class="text-sm text-red-200 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Register Form -->
            <div id="registerForm" class="hidden">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white mb-2">Create Account</h2>
                    <p class="text-blue-200">Join AFPPGMC Inventory System</p>
                </div>
                
                <form method="POST" action="{{ route('register') }}" id="registerFormElement">
                    @csrf
                    
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">First Name *</label>
                                <input name="first_name" 
                                       placeholder="First Name" 
                                       class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Middle Name</label>
                                <input name="middle_name" 
                                       placeholder="Middle Name" 
                                       class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Last Name *</label>
                                <input name="last_name" 
                                       placeholder="Last Name" 
                                       class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Suffix</label>
                                <input name="suffix" 
                                       placeholder="Jr, Sr, III" 
                                       class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Sex Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Sex *</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sex" value="female" class="w-4 h-4 text-blue-600" required>
                                    <span class="text-gray-300">Female</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sex" value="male" class="w-4 h-4 text-blue-600" required>
                                    <span class="text-gray-300">Male</span>
                                </label>
                            </div>
                        </div>

                        <!-- Unit Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Unit/Department *</label>
                            <div class="relative">
                                <select name="unit" 
                                        class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none pr-10"
                                        required>
                                    <option value="">Select Unit/Department</option>
                                    @foreach (['BDCU','CUI','COMMAND','ISU','LSO','PAU','PG1','PG3','PG4','PG10','PPBU'] as $unit)
                                        <option value="{{ $unit }}">{{ $unit }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-3 top-3 text-gray-400 pointer-events-none">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Account Credentials -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Username *</label>
                                <input name="username" 
                                       placeholder="Choose username" 
                                       class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email *</label>
                                <input name="email" 
                                       type="email"
                                       placeholder="your.email@afppgmc.com" 
                                       class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <!-- Password Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Password *</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password" 
                                           placeholder="Create password" 
                                           class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           required
                                           id="passwordField">
                                    <button type="button" class="absolute right-3 top-3 text-gray-400 hover:text-white" onclick="togglePassword(this)">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-2" id="passwordStrength"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password *</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       placeholder="Confirm password" 
                                       class="input-dark w-full bg-slate-800/50 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start gap-2 mt-4">
                            <input type="checkbox" 
                                   name="terms" 
                                   id="terms"
                                   class="mt-1 w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                                   required>
                            <label for="terms" class="text-sm text-gray-300">
                                I agree to the <a href="#" class="text-blue-400 hover:text-blue-300">Terms of Service</a> and <a href="#" class="text-blue-400 hover:text-blue-300">Privacy Policy</a> *
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-semibold py-3.5 px-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 mt-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab Switching
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    loginTab.onclick = () => {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        loginTab.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'text-white', 'shadow-lg');
        loginTab.classList.remove('text-gray-300', 'hover:bg-slate-700/50');
        registerTab.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'text-white', 'shadow-lg');
        registerTab.classList.add('text-gray-300', 'hover:bg-slate-700/50');
    };

    registerTab.onclick = () => {
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        registerTab.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'text-white', 'shadow-lg');
        registerTab.classList.remove('text-gray-300', 'hover:bg-slate-700/50');
        loginTab.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'text-white', 'shadow-lg');
        loginTab.classList.add('text-gray-300', 'hover:bg-slate-700/50');
    };

    // Password Visibility Toggle
    function togglePassword(button) {
        const input = button.parentElement.querySelector('input');
        const icon = button.querySelector('svg');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `<path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>`;
        } else {
            input.type = 'password';
            icon.innerHTML = `<path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                             <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>`;
        }
    }

    // Password Strength Indicator
    document.getElementById('passwordField')?.addEventListener('input', function(e) {
        const password = e.target.value;
        const strengthBar = document.getElementById('passwordStrength');
        
        if (!strengthBar) return;
        
        let strength = 0;
        let tips = [];
        
        // Check password strength
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Update strength indicator
        const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        const strengthColors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
        
        strengthBar.innerHTML = `
            <div class="flex items-center gap-2">
                <div class="flex-1 h-2 bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full ${strengthColors[strength-1] || 'bg-red-500'} transition-all duration-300" 
                         style="width: ${(strength/5)*100}%"></div>
                </div>
                <span class="text-xs ${strength >= 4 ? 'text-green-400' : strength >= 3 ? 'text-blue-400' : 'text-red-400'}">
                    ${strengthText[strength-1] || 'Very Weak'}
                </span>
            </div>
            ${password.length > 0 ? `
            <div class="mt-2 text-xs text-gray-400">
                ${strength < 3 ? 'Password should be at least 8 characters with uppercase, lowercase, and numbers.' : 'Strong password!'}
            </div>` : ''}
        `;
    });

    // Form validation feedback
    document.querySelectorAll('input, select').forEach(element => {
        element.addEventListener('invalid', function(e) {
            e.preventDefault();
            this.classList.add('border-red-500', 'ring-2', 'ring-red-500/50');
        });
        
        element.addEventListener('input', function() {
            if (this.classList.contains('border-red-500')) {
                this.classList.remove('border-red-500', 'ring-2', 'ring-red-500/50');
            }
        });
    });
</script>
@endsection
