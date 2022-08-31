<x-base-layout>

    {{-- Page Title --}}
    <x-page-title :title="__('Verify Email')" />
    {{-- End Page Title --}}

    <section class="mt-48 md:mt-40 relative bg-blueGray-100">
        <div class="-mt-20 top-0 bottom-auto left-0 right-0 w-full absolute h-20" style="transform: translateZ(0)">
            <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                <polygon class="text-blueGray-100 fill-current" points="2560 0 2560 100 0 100"></polygon>
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
                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by
                            clicking on the
                            link we just emailed to you? If you didn\'t receive the email, we will gladly send you
                            another.') }}
                        </div>

                        @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to the email address you provided during
                            registration.') }}
                        </div>
                        @endif

                        <div class="mt-4 flex items-center justify-between">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf

                                <div>
                                    <x-auth.button>
                                        {{ __('Resend Verification Email') }}
                                    </x-auth.button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </x-auth.card>
                </div>
            </div>
        </div>
    </section>
</x-base-layout>