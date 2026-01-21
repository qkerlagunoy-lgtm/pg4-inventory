<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-white leading-tight">
                    {{ __('Profile Settings') }}
                </h2>
                <p class="text-sm text-gray-400 mt-1">
                    {{ __('Manage your account and security settings') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-black via-slate-900 to-blue-950 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Profile Summary Card -->
            <div class="mb-6 p-6 bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl">
                <div class="flex items-center gap-6">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold text-2xl shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ $user->email }}</p>
                        <div class="mt-3 flex gap-3">
                            @if($user->type)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                    {{ ucfirst($user->type) }}
                                </span>
                            @endif
                            @if($user->phone)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400 border border-green-500/30">
                                    📞 {{ $user->phone }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Sections -->
            <div class="space-y-6">
                
                <!-- Update Profile Information -->
                <div class="bg-slate-900 border border-slate-700 shadow-2xl rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-slate-700 bg-slate-800/50">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">
                                    {{ __('Profile Information') }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-400">
                                    {{ __('Update your account profile details and contact information.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="bg-slate-900 border border-slate-700 shadow-2xl rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-slate-700 bg-slate-800/50">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white">
                                    {{ __('Security') }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-400">
                                    {{ __('Keep your account secure by updating your password regularly.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="bg-slate-900 border border-red-500/30 shadow-2xl rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-red-500/30 bg-red-500/10">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-400">
                                    {{ __('Danger Zone') }}
                                </h3>
                                <p class="mt-1 text-sm text-red-300">
                                    {{ __('Permanently delete your account and all associated data.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>