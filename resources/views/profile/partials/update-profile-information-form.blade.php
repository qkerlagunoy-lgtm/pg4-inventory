<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- First Name Field -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-2 block w-full" :value="old('first_name', $user->first_name)" autocomplete="given-name" />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <!-- Middle Name Field -->
            <div>
                <x-input-label for="middle_name" :value="__('Middle Name')" />
                <x-text-input id="middle_name" name="middle_name" type="text" class="mt-2 block w-full" :value="old('middle_name', $user->middle_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
            </div>

            <!-- Last Name Field -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-2 block w-full" :value="old('last_name', $user->last_name)" autocomplete="family-name" />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>

            <!-- Suffix Field -->
            <div>
                <x-input-label for="suffix" :value="__('Suffix')" />
                <x-text-input id="suffix" name="suffix" type="text" class="mt-2 block w-full" :value="old('suffix', $user->suffix)" placeholder="Jr., Sr., III" />
                <x-input-error class="mt-2" :messages="$errors->get('suffix')" />
            </div>

            <!-- Sex Field -->
            <div>
                <x-input-label for="sex" :value="__('Sex')" />
                <div class="mt-2 flex gap-4">
                    <label class="flex items-center">
                        <input type="radio" name="sex" value="Male" @checked(old('sex', $user->sex) === 'Male') class="rounded border-gray-300" />
                        <span class="ml-2">{{ __('Male') }}</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="sex" value="Female" @checked(old('sex', $user->sex) === 'Female') class="rounded border-gray-300" />
                        <span class="ml-2">{{ __('Female') }}</span>
                    </label>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('sex')" />
            </div>

            <!-- Category Field -->
            <div>
                <x-input-label for="category" :value="__('Category')" />
                <select id="category" name="category" class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">{{ __('Select Category') }}</option>
                    <option value="Standard" @selected(old('category', $user->category) === 'Standard')>{{ __('Standard') }}</option>
                    <option value="Premium" @selected(old('category', $user->category) === 'Premium')>{{ __('Premium') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('category')" />
            </div>

            <!-- Unit Field -->
            <div>
                <x-input-label for="unit" :value="__('Unit')" />
                <select id="unit" name="unit" class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">{{ __('Select Unit') }}</option>
                    <option value="BGCU" @selected(old('unit', $user->unit) === 'BGCU')>BGCU</option>
                    <option value="CIU" @selected(old('unit', $user->unit) === 'CIU')>CIU</option>
                    <option value="COMMAND" @selected(old('unit', $user->unit) === 'COMMAND')>COMMAND</option>
                    <option value="ISU" @selected(old('unit', $user->unit) === 'ISU')>ISU</option>
                    <option value="LSO" @selected(old('unit', $user->unit) === 'LSO')>LSO</option>
                    <option value="PAU" @selected(old('unit', $user->unit) === 'PAU')>PAU</option>
                    <option value="PG1" @selected(old('unit', $user->unit) === 'PG1')>PG1</option>
                    <option value="PG3" @selected(old('unit', $user->unit) === 'PG3')>PG3</option>
                    <option value="PG4" @selected(old('unit', $user->unit) === 'PG4')>PG4</option>
                    <option value="PG10" @selected(old('unit', $user->unit) === 'PG10')>PG10</option>
                    <option value="PPBU" @selected(old('unit', $user->unit) === 'PPBU')>PPBU</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('unit')" />
            </div>

            <!-- Username Field -->
            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" name="username" type="text" class="mt-2 block w-full" :value="old('username', $user->username)" autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>

            <!-- Name Field -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-2 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email Field -->
            <div class="md:col-span-2">
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" name="email" type="email" class="mt-2 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                        <p class="text-sm text-amber-800 dark:text-amber-200">
                            ⚠️ {{ __('Your email address is unverified.') }}
                        </p>

                        <button form="send-verification" class="mt-2 inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors">
                            {{ __('Send Verification Email') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400 flex items-center gap-2">
                                ✓ {{ __('Verification link sent to your email.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- User Type Field -->
            <div>
                <x-input-label for="type" :value="__('Account Type')" />
                <select id="type" name="type" class="mt-2 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">{{ __('Select Type') }}</option>
                    <option value="customer" @selected(old('type', $user->type) === 'customer')>{{ __('Customer') }}</option>
                    <option value="vendor" @selected(old('type', $user->type) === 'vendor')>{{ __('Vendor') }}</option>
                    <option value="admin" @selected(old('type', $user->type) === 'admin')>{{ __('Administrator') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('type')" />
            </div>

            <!-- Phone Field -->
            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" name="phone" type="tel" class="mt-2 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" placeholder="+1 (555) 000-0000" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Optional: Include country code for better contact') }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <x-primary-button>
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Save Changes') }}
                </x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 dark:text-green-400 font-medium flex items-center gap-2"
                    >
                        ✓ {{ __('Profile updated successfully!') }}
                    </p>
                @endif
            </div>
        </div>
    </form>
</section>
