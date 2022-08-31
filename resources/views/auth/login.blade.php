<x-auth-layout>

    {{-- Page Title --}}
    <x-page-title :title="'Login'" />
    {{-- End Page Title --}}

    <div class="absolute top-0 w-full h-full bg-blueGray-800 bg-full bg-no-repeat"
        style="background-image: url({{ url('dist/notus-js/img/register_bg_2.png') }})">
    </div>
    <div class="container mx-auto px-4 h-full">
        <div class="flex content-center items-center justify-center h-full">
            <div class="w-full lg:w-6/12 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-200 border-0">
                    <div class="rounded-t mb-0 px-6 py-6">
                        <div class="text-center mb-3">
                            <h6 class="text-blueGray-500 text-sm font-bold">
                                Sign in with
                            </h6>
                        </div>
                        <div class="btn-wrapper text-center">
                            <a href="{{ url('login/github') }}"
                                class="bg-white active:bg-blueGray-50 text-blueGray-700 font-normal px-4 py-2 rounded outline-none focus:outline-none mr-2 mb-1 uppercase shadow hover:shadow-md inline-flex items-center font-bold text-xs ease-linear transition-all duration-150"
                                type="button">
                                <img alt="{{ __('Sign in with Github') }}" class="w-5 mr-1"
                                    src="{{ url('/dist/notus-js/img/github.svg') }}" />
                                Github
                            </a>
                            <a href="{{ url('login/google') }}"
                                class="bg-white active:bg-blueGray-50 text-blueGray-700 font-normal px-4 py-2 rounded outline-none focus:outline-none mr-2 mb-1 uppercase shadow hover:shadow-md inline-flex items-center font-bold text-xs ease-linear transition-all duration-150"
                                type="button">
                                <img alt="{{ __('Sign in with Google') }}" class="w-5 mr-1"
                                    src="{{ url('dist/notus-js/img/google.svg') }}" />
                                Google
                            </a>
                            <a href="{{ url('login/facebook') }}"
                                class="bg-white active:bg-blueGray-50 text-blueGray-700 font-normal px-4 py-2 rounded outline-none focus:outline-none mr-1 mb-1 uppercase shadow hover:shadow-md inline-flex items-center font-bold text-xs ease-linear transition-all duration-150"
                                type="button">
                                <img alt="{{ __('Sign in with Facebook') }}" class="w-5 mr-1"
                                    src="{{ url('/dist/notus-js/img/icons8-facebook.svg') }}" />
                                Facebook
                            </a>
                        </div>
                        {{-- Error --}}
                        <div class="text-center mt-4">
                            <x-auth.error-with-session />
                        </div>
                        {{-- End Error --}}
                        <hr class="mt-6 border-b-1 border-blueGray-300" />
                    </div>
                    <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                        <div class="text-blueGray-400 text-center mb-3 font-bold">
                            <small>Or sign in with credentials</small>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Email --}}
                            <div class="relative w-full mb-3">
                                <x-auth.label for="email" :value="__('Email')" />
                                <x-auth.input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email')" placeholder="{{ __('Email') }}" />
                                @if ($errors->has('email'))
                                <x-auth.error-input message="{{ $errors->first('email') }}" />
                                @endif
                            </div>

                            {{-- Password --}}
                            <div class="relative w-full mb-3">
                                <x-auth.label for="password" :value="__('Password')" />
                                <x-auth.input id="password" class="block mt-1 w-full" type="password" name="password"
                                    autocomplete="current-password" placeholder="{{ __('Password') }}" />
                                @if ($errors->has('password'))
                                <x-auth.error-input message="{{ $errors->first('password') }}" />
                                @endif
                            </div>

                            <div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input id="remember_me" type="checkbox"
                                        class="form-checkbox border-0 rounded text-blueGray-700 ml-1 w-5 h-5 ease-linear transition-all duration-150" />
                                    <span class="ml-2 text-sm font-semibold text-blueGray-600">Remember me</span>
                                </label>
                            </div>

                            <div class="text-center mt-6">
                                <x-auth.button>
                                    {{ __('Sign In') }}
                                </x-auth.button>
                            </div>

                            <div class="flex flex-wrap mb-10">
                                <div class="w-1/2">
                                    <a href="{{ route('password.request') }}" class="text-blueGray-700">
                                        <small>{{ __('Forgot your password?') }}</small>
                                    </a>
                                </div>
                                <div class="w-1/2 text-right">
                                    <a href="{{ route('register') }}" class="text-blueGray-700">
                                        <small>{{ __('Create new account') }}</small>
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>