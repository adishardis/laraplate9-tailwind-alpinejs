<x-profile-base-layout>
    {{-- Page Title --}}
    <x-page-title :title="__('Profile')" />
    {{-- End Page Title --}}

    <main class="profile-page">
        <section class="relative block h-500-px">
            <div x-data="{
                image: $store.helper.defaultBackgroundImage,
                userId: `{{ auth()->id() }}`,

                async getBackground() {
                    path = await $store.helper.getBackground();
                    if (path) {
                        this.image = path;
                    }
                },
                async listen() {
                    Echo.private(`update-background.${this.userId}`).listen('UpdateBackgroundNotification', (e) => {
                        this.getBackground();
                    });
                },
                async initialize() {
                    this.getBackground();
                    this.listen();
                },
            }" x-init="initialize" x-cloak class="absolute top-0 w-full h-full bg-center bg-cover"
                :style="`background-image: url(`+image+`);`">
                <span id="blackOverlay" class="w-full h-full absolute opacity-50 bg-black"></span>
            </div>
            <div class="top-auto bottom-0 left-0 right-0 w-full absolute pointer-events-none overflow-hidden h-70-px"
                style="transform: translateZ(0px)">
                <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg"
                    preserveAspectRatio="none" version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                    <polygon class="text-slate-200 fill-current" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </section>
        <section class="relative py-16 bg-slate-200">
            <div class="container mx-auto px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">
                    <div class="px-6">
                        <div class="flex flex-wrap justify-center">
                            <div class="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center">
                                <div x-data="{
                                    image: $store.helper.defaultImage,
                                    userId: `{{ auth()->id() }}`,
        
                                    async getThumb() {
                                        path = await $store.helper.getAvatarThumb();
                                        if (path) {
                                            this.image = path;
                                        }
                                    },
                                    async listen() {
                                        Echo.private(`update-avatar.${this.userId}`).listen('UpdateAvatarNotification', (e) => {
                                            this.getThumb();
                                        });
                                    },
                                    async initialize() {
                                        this.getThumb();
                                        this.listen();
                                    },
                                }" x-init="initialize" x-cloak class="relative">
                                    <img alt="..." :src="image"
                                        class="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px" />
                                </div>
                            </div>
                            <div class="w-full lg:w-4/12 px-4 lg:order-3 lg:text-right lg:self-center">
                                <div class="py-6 px-3 mt-32 sm:mt-0">
                                </div>
                            </div>
                            <div class="w-full lg:w-4/12 px-4 lg:order-1">
                                <div class="flex justify-center py-4 lg:pt-4 pt-8" x-data="{
                                        url: '/user/fetch/profile?mode=summary',
                                        totalLikes: 0,
                                        totalDislikes: 0,
                                        totalComments: 0,

                                        // Functions
                                        async getSummary(url) {
                                            fetch(url)
                                            .then(response => response.json())
                                            .then(response => {
                                                data = response.data;
                                                this.totalLikes = data.likes;
                                                this.totalDislikes = data.dislikes;
                                                this.totalComments = data.comments;
                                            })
                                        },
                                        async initialize() {
                                            this.getSummary(this.url);
                                        }
                                    }" x-init="initialize" x-cloak>
                                    <div class="mr-4 p-3 text-center">
                                        <span class="text-xl font-bold block uppercase tracking-wide text-slate-600"
                                            x-text="totalLikes"></span>
                                        <span class="text-sm text-slate-400">{{ __('Liked') }}</span>
                                    </div>
                                    <div class="mr-4 p-3 text-center">
                                        <span class="text-xl font-bold block uppercase tracking-wide text-slate-600"
                                            x-text="totalDislikes"></span>
                                        <span class="text-sm text-slate-400">{{ __('Disliked') }}</span>
                                    </div>
                                    <div class="lg:mr-4 p-3 text-center">
                                        <span class="text-xl font-bold block uppercase tracking-wide text-slate-600"
                                            x-text="totalComments"></span>
                                        <span class="text-sm text-slate-400">{{ __('Comments') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div x-data="{
                            userName: '{{ $user->name ?? '' }}',
                            userEmail: '{{ $user->email ?? '' }}',
                        }" x-cloak>
                            <div class="text-center mt-12">
                                <h3 class="text-4xl font-semibold leading-normal mb-2 text-slate-700"
                                    x-model="userName" x-text="userName">
                                </h3>
                                <div class="text-sm leading-normal mt-0 mb-2 text-slate-400 font-bold uppercase">
                                    <i class="fas fa-envelope mr-2 text-lg text-slate-400"></i>
                                    <span x-model="userEmail" x-text="userEmail"></span>
                                </div>
                            </div>
                            <div class="mt-10 py-10 border-t border-slate-200 text-center">
                                <div class="flex flex-wrap justify-center">
                                    <div class="w-full lg:w-9/12 px-4">
                                        {{ Form::model($user, ['route' => ['user.profile.update']]) }}
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <div class="flex flex-wrap">
                                            <div class="w-full lg:w-6/12 px-4">
                                                <div class="relative w-full mb-3">
                                                    <x-label-input-form for="username">
                                                        {{ __('Username') }}
                                                    </x-label-input-form>
                                                    <input type="text"
                                                        class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                                        placeholder="{{ __('Username') }}" name="username"
                                                        value="{{ old('username', $user->username ?? '') }}" />
                                                    @if ($errors->has('username'))
                                                    <x-error-input message="{{ $errors->first('username') }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full lg:w-6/12 px-4">
                                                <div class="relative w-full mb-3">
                                                    <x-label-input-form for="name">
                                                        {{ __('Name') }}
                                                    </x-label-input-form>
                                                    <input type="text"
                                                        class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                                        placeholder="{{ __('Name') }}" x-model="userName" name="name"
                                                        value="{{ old('name', $user->name ?? '') }}" />
                                                    @if ($errors->has('name'))
                                                    <x-error-input message="{{ $errors->first('name') }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full lg:w-6/12 px-4">
                                                <div class="relative w-full mb-3">
                                                    <x-label-input-form for="email">
                                                        {{ __('Email') }}
                                                    </x-label-input-form>
                                                    <input type="email"
                                                        class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                                        placeholder="{{ __('Email') }}" x-model="userEmail" name="email"
                                                        value="{{ old('email', $user->email ?? '') }}" />
                                                    @if ($errors->has('email'))
                                                    <x-error-input message="{{ $errors->first('email') }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full lg:w-6/12 px-4">
                                                <div class="relative w-full mb-3">
                                                    <x-label-input-form for="password">
                                                        {{ __('Password') }}
                                                    </x-label-input-form>
                                                    <input type="password"
                                                        class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                                        placeholder="{{ __('Password') }}" name="password" />
                                                    @if ($errors->has('password'))
                                                    <x-error-input message="{{ $errors->first('password') }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full lg:w-6/12 px-4">
                                                <div class="relative w-full mb-3">
                                                    <x-label-input-form for="is_notif_alert">
                                                        {{ __('Notif Alert') }}
                                                    </x-label-input-form>
                                                    <input type="checkbox"
                                                        class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring ease-linear transition-all duration-150"
                                                        placeholder="{{ __('Notif Alert') }}"
                                                        name="setting[is_notif_alert]" value="1" {{
                                                        old('setting.is_notif_alert') &&
                                                        old('setting.is_notif_alert')=='1' ? 'checked' :
                                                        (($user->setting->is_notif_alert ?? 0) == '1' ? 'checked' : '')
                                                    }} />
                                                    @if ($errors->has('setting.is_notif_alert'))
                                                    <x-error-input
                                                        message="{{ $errors->first('setting.is_notif_alert') }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full lg:w-6/12 px-4">
                                                <div class="relative w-full mb-3">
                                                    <x-label-input-form for="avatar">
                                                        {{ __('Avatar') }}
                                                    </x-label-input-form>
                                                    <input type="hidden" name="avatar_id" id="avatar_id"
                                                        value="{{ $user->id ?? '' }}">
                                                    <div class="dropzone" id="dropzone-avatar"></div>
                                                    @if ($errors->has('avatar_id'))
                                                    <x-error-input message="{{ $errors->first('avatar_id') }}" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="w-full lg:w-6/12 px-4">
                                                <div class="relative w-full mb-3">
                                                    <x-label-input-form for="background">
                                                        {{ __('Background') }}
                                                    </x-label-input-form>
                                                    <input type="hidden" name="background_id" id="background_id"
                                                        value="{{ $user->id ?? '' }}">
                                                    <div class="dropzone" id="dropzone-background"></div>
                                                    @if ($errors->has('background_id'))
                                                    <x-error-input message="{{ $errors->first('background_id') }}" />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="mt-6 border-b-1 border-slate-300" />

                                        <div class="flex justify-end">
                                            <div class="py-6 px-3 mt-32 sm:mt-0">
                                                <button
                                                    class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150"
                                                    type="submit">
                                                    <i class="fa fa-save"></i>
                                                    {{ __('Update') }}
                                                </button>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('user.layouts.scripts.profile')
</x-profile-base-layout>