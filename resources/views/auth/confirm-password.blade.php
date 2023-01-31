<x-base-layout>

    {{-- Page Title --}}
    <x-page-title :title="__('Confirm Password')" />
    {{-- End Page Title --}}

    <section class="mt-48 md:mt-40 relative bg-slate-100">
        <div class="-mt-20 top-0 bottom-auto left-0 right-0 w-full absolute h-20" style="transform: translateZ(0)">
            <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                <polygon class="text-slate-100 fill-current" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
        <div class="container mx-auto">
            <div class="flex flex-wrap items-center">
                <div class="w-full px-4">
                    <x-auth.card>
                        <x-slot name="logo">
                            <a href="/">
                                <x-auth.application-logo class="w-20 h-20 fill-current text-gray-500" />
                            </a>
                        </x-slot>

                        <div class="mb-4 text-sm text-gray-600">
                            {{ __('This is a secure area of the application. Please confirm your password before
                            continuing.') }}
                        </div>

                        <!-- Validation Errors -->
                        <x-auth.validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <!-- Password -->
                            <div>
                                <x-auth.label for="password" :value="__('Password')" />

                                <x-auth.input id="password" class="block mt-1 w-full" type="password" name="password"
                                    required autocomplete="current-password" />
                            </div>

                            <div class="flex justify-end mt-4">
                                <x-auth.button>
                                    {{ __('Confirm') }}
                                </x-auth.button>
                            </div>
                        </form>
                    </x-auth.card>
                </div>
            </div>
        </div>
    </section>
</x-base-layout>