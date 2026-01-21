<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
        @csrf
        @method('patch')

        <!-- Personal Information Section -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 pb-3 border-b border-white/10">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h4 class="text-base font-semibold text-white">Personal Details</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name Field -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('First Name') }}
                    </label>
                    <input 
                        id="first_name" 
                        name="first_name" 
                        type="text" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15" 
                        value="{{ old('first_name', $user->first_name) }}" 
                        autocomplete="given-name"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>

                <!-- Middle Name Field -->
                <div>
                    <label for="middle_name" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Middle Name') }} <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <input 
                        id="middle_name" 
                        name="middle_name" 
                        type="text" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15" 
                        value="{{ old('middle_name', $user->middle_name) }}"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
                </div>

                <!-- Last Name Field -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Last Name') }}
                    </label>
                    <input 
                        id="last_name" 
                        name="last_name" 
                        type="text" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15" 
                        value="{{ old('last_name', $user->last_name) }}" 
                        autocomplete="family-name"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>

                <!-- Suffix Field -->
                <div>
                    <label for="suffix" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Suffix') }} <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <input 
                        id="suffix" 
                        name="suffix" 
                        type="text" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15" 
                        value="{{ old('suffix', $user->suffix) }}" 
                        placeholder="Jr., Sr., III"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('suffix')" />
                </div>

                <!-- Sex Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-100 mb-3">
                        {{ __('Sex') }}
                    </label>
                    <div class="flex gap-6">
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="radio" 
                                name="sex" 
                                value="Male" 
                                @checked(old('sex', $user->sex) === 'Male') 
                                class="w-5 h-5 text-blue-500 bg-white/10 border-white/30 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer"
                            />
                            <span class="ml-3 text-sm font-medium text-gray-100 group-hover:text-white transition-colors">{{ __('Male') }}</span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="radio" 
                                name="sex" 
                                value="Female" 
                                @checked(old('sex', $user->sex) === 'Female') 
                                class="w-5 h-5 text-blue-500 bg-white/10 border-white/30 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer"
                            />
                            <span class="ml-3 text-sm font-medium text-gray-100 group-hover:text-white transition-colors">{{ __('Female') }}</span>
                        </label>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('sex')" />
                </div>

                <!-- Category Field -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Category') }}
                    </label>
                    <select 
                        id="category" 
                        name="category" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15 cursor-pointer"
                    >
                        <option value="" class="bg-slate-800">{{ __('Select Category') }}</option>
                        <option value="Standard" @selected(old('category', $user->category) === 'Standard') class="bg-slate-800">{{ __('Standard') }}</option>
                        <option value="Premium" @selected(old('category', $user->category) === 'Premium') class="bg-slate-800">{{ __('Premium') }}</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                </div>
            </div>
        </div>

        <!-- Organization Information Section -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 pb-3 border-b border-white/10">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h4 class="text-base font-semibold text-white">Organization Details</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Unit Field -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Unit') }}
                    </label>
                    <select 
                        id="unit" 
                        name="unit" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15 cursor-pointer"
                    >
                        <option value="" class="bg-slate-800">{{ __('Select Unit') }}</option>
                        <option value="BGCU" @selected(old('unit', $user->unit) === 'BGCU') class="bg-slate-800">BGCU</option>
                        <option value="CIU" @selected(old('unit', $user->unit) === 'CIU') class="bg-slate-800">CIU</option>
                        <option value="COMMAND" @selected(old('unit', $user->unit) === 'COMMAND') class="bg-slate-800">COMMAND</option>
                        <option value="ISU" @selected(old('unit', $user->unit) === 'ISU') class="bg-slate-800">ISU</option>
                        <option value="LSO" @selected(old('unit', $user->unit) === 'LSO') class="bg-slate-800">LSO</option>
                        <option value="PAU" @selected(old('unit', $user->unit) === 'PAU') class="bg-slate-800">PAU</option>
                        <option value="PG1" @selected(old('unit', $user->unit) === 'PG1') class="bg-slate-800">PG1</option>
                        <option value="PG3" @selected(old('unit', $user->unit) === 'PG3') class="bg-slate-800">PG3</option>
                        <option value="PG4" @selected(old('unit', $user->unit) === 'PG4') class="bg-slate-800">PG4</option>
                        <option value="PG10" @selected(old('unit', $user->unit) === 'PG10') class="bg-slate-800">PG10</option>
                        <option value="PPBU" @selected(old('unit', $user->unit) === 'PPBU') class="bg-slate-800">PPBU</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('unit')" />
                </div>

                <!-- Account Type Field -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Account Type') }}
                    </label>
                    <select 
                        id="type" 
                        name="type" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15 cursor-pointer"
                    >
                        <option value="" class="bg-slate-800">{{ __('Select Type') }}</option>
                        <option value="customer" @selected(old('type', $user->type) === 'customer') class="bg-slate-800">{{ __('Customer') }}</option>
                        <option value="vendor" @selected(old('type', $user->type) === 'vendor') class="bg-slate-800">{{ __('Vendor') }}</option>
                        <option value="admin" @selected(old('type', $user->type) === 'admin') class="bg-slate-800">{{ __('Administrator') }}</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('type')" />
                </div>
            </div>
        </div>

        <!-- Account Information Section -->
        <div class="space-y-6">
            <div class="flex items-center gap-3 pb-3 border-b border-white/10">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h4 class="text-base font-semibold text-white">Account & Contact Information</h4>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Username') }}
                    </label>
                    <input 
                        id="username" 
                        name="username" 
                        type="text" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15" 
                        value="{{ old('username', $user->username) }}" 
                        autocomplete="username"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('username')" />
                </div>

                <!-- Full Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Full Name') }} <span class="text-red-400">*</span>
                    </label>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email Field -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Email Address') }} <span class="text-red-400">*</span>
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        autocomplete="username"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-4 p-4 bg-amber-500/20 rounded-xl border border-amber-400/40 backdrop-blur-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-amber-200">
                                        {{ __('Your email address is unverified.') }}
                                    </p>
                                    <button 
                                        form="send-verification" 
                                        class="mt-3 inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ __('Send Verification Email') }}
                                    </button>

                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-3 text-sm font-medium text-green-300 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ __('Verification link sent to your email.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Phone Field -->
                <div class="md:col-span-2">
                    <label for="phone" class="block text-sm font-medium text-gray-100 mb-2">
                        {{ __('Phone Number') }} <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <input 
                        id="phone" 
                        name="phone" 
                        type="tel" 
                        class="block w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:bg-white/15" 
                        autocomplete="tel" 
                        placeholder="+1 (555) 000-0000"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    <p class="mt-2 text-xs text-gray-300 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Include country code for better contact') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-6 border-t border-white/10">
            <div class="flex items-center gap-4">
                <button 
                    type="submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Save Changes') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-init="setTimeout(() => show = false, 3000)"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-500/20 border border-green-400/40 rounded-lg"
                    >
                        <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-green-200">{{ __('Profile updated successfully!') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </form>
</section>