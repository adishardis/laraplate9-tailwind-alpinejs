<x-auth-layout>
    {{-- Page Title --}}
    <x-page-title :title="'Forgot Password'" />
    {{-- End Page Title --}}

    <section class="relative w-full h-full py-40 min-h-screen">
        <div class="absolute top-0 w-full h-full bg-slate-800 bg-full bg-no-repeat"
            style="background-image: url({{ url('notus-js/img/register_bg_2.png') }})"></div>
        <div class="container mx-auto px-4 h-full">
            <div class="flex content-center items-center justify-center h-full">
                <div class="w-full lg:w-6/12 px-4">
                    <div
                        class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-slate-200 border-0">

                        {{-- Error --}}
                        <div class="text-center mt-4">
                            <x-auth.error-with-session />
                        </div>
                        {{-- End Error --}}

                        <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                            <div class="text-slate-400 text-center mb-3 font-bold">
                                <h6 class="text-slate-500 text-sm font-bold">
                                    {{ __('Forgot Password') }}
                                </h6>


                                <hr class="mt-6 border-b-1 border-slate-300" />
                            </div>
                            <div class="text-center">
                                {{-- Session Status --}}
                                <x-auth.session-status class="text-gray-700" :status="session('status')" />
                            </div>
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                {{-- Email --}}
                                <div class="relative w-full mb-3">
                                    <x-auth.label for="email" :value="__('Email')" />
                                    <x-auth.input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" placeholder="{{ __('Email') }}" autofocus />
                                    @if ($errors->has('email'))
                                    <x-auth.error-input message="{{ $errors->first('email') }}" />
                                    @endif
                                </div>

                                <div class="text-center mt-6">
                                    <x-auth.button>
                                        {{ __('Email Password Reset Link') }}
                                    </x-auth.button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-auth-layout>