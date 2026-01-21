<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-slate-800 to-slate-900 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Profile Summary Card -->
            <div class="mb-8 p-8 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl shadow-2xl hover:bg-white/10 transition-all duration-300">
                <div class="flex items-center gap-6">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold text-3xl shadow-lg ring-4 ring-blue-500/20">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-white mb-2">{{ $user->name }}</h3>
                        <p class="text-base text-gray-200 mb-3">{{ $user->email }}</p>
                        <div class="flex flex-wrap gap-3">
                            @if($user->type)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-500/30 text-blue-100 border border-blue-400/50 shadow-sm">
                                    {{ ucfirst($user->type) }}
                                </span>
                            @endif
                            @if($user->phone)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-500/30 text-green-100 border border-green-400/50 shadow-sm">
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
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 shadow-2xl rounded-2xl overflow-hidden hover:border-white/20 transition-all duration-300">
                    <div class="p-6 border-b border-white/10 bg-gradient-to-r from-blue-500/10 to-purple-500/10">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white mb-1">
                                    {{ __('Profile Information') }}
                                </h3>
                                <p class="text-sm text-gray-200 leading-relaxed">
                                    {{ __('Update your account profile details and contact information.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 bg-white/5">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 shadow-2xl rounded-2xl overflow-hidden hover:border-white/20 transition-all duration-300">
                    <div class="p-6 border-b border-white/10 bg-gradient-to-r from-green-500/10 to-emerald-500/10">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white mb-1">
                                    {{ __('Security') }}
                                </h3>
                                <p class="text-sm text-gray-200 leading-relaxed">
                                    {{ __('Keep your account secure by updating your password regularly.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 bg-white/5">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="bg-white/5 backdrop-blur-sm border border-red-400/30 shadow-2xl rounded-2xl overflow-hidden hover:border-red-400/50 transition-all duration-300">
                    <div class="p-6 border-b border-red-400/30 bg-gradient-to-r from-red-500/20 to-orange-500/20">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
                                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-red-300 mb-1">
                                    {{ __('Danger Zone') }}
                                </h3>
                                <p class="text-sm text-red-200 leading-relaxed">
                                    {{ __('Permanently delete your account and all associated data.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 bg-white/5">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>