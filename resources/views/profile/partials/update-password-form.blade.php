<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-6">
            <!-- Current Password -->
            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input 
                    id="update_password_current_password" 
                    name="current_password" 
                    type="password" 
                    class="mt-2 block w-full" 
                    autocomplete="current-password" 
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Enter your current password for verification') }}</p>
            </div>

            <!-- New Password -->
            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input 
                    id="update_password_password" 
                    name="password" 
                    type="password" 
                    class="mt-2 block w-full" 
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-700 dark:text-blue-300 font-medium mb-2">{{ __('Password Requirements:') }}</p>
                    <ul class="text-xs text-blue-600 dark:text-blue-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <span class="inline-block w-1.5 h-1.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                            {{ __('At least 8 characters long') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="inline-block w-1.5 h-1.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                            {{ __('Mix of uppercase and lowercase letters') }}
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="inline-block w-1.5 h-1.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                            {{ __('Numbers and special characters recommended') }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" />
                <x-text-input 
                    id="update_password_password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    class="mt-2 block w-full" 
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <x-primary-button>
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('Update Password') }}
                </x-primary-button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 dark:text-green-400 font-medium flex items-center gap-2"
                    >
                        ✓ {{ __('Password updated successfully!') }}
                    </p>
                @endif
            </div>
        </div>
    </form>
</section>
