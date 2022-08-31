<x-super-base-layout>
  {{-- Page Title --}}
  <x-page-title :title="(isset($user) ? 'Edit' : 'Create').' User'" :role="'Super Admin'" />
  {{-- End Page Title --}}

  {{-- Nav Header --}}
  <x-slot name="navHeader">
    <x-navbar-breadcrumbs :items="[
      [
        'text' => 'User',
        'url' => route('super.users.index')
      ],
      [
        'text' => ((isset($user) ? 'Edit' : 'Create').' User'),
        'url' => (isset($user) ? route('super.users.edit', compact('user')) : route('super.users.create'))
      ]
    ]" />
  </x-slot>
  {{-- End Nav Header --}}

  <div class="w-full mb-12 xl:mb-0 px-4">
    <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
      <div class="rounded-t bg-white mb-0 px-6 py-6">
        <div class="text-center flex justify-between">
          <h6 class="text-blueGray-700 text-xl font-bold">
            {{ (isset($user) ? 'Edit' : 'Create').' User' }}
          </h6>
        </div>
      </div>
      <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
          {{ __('User Information') }}
        </h6>
        @if(isset($user))
        {{ Form::model($user, ['route' => ['super.users.update', $user['id']]]) }}
        {{ Form::hidden('user_id',$user->id) }}
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @else
        {{ Form::open(['route' => 'super.users.store', ]) }}
        @endif
        <div class="flex flex-wrap">
          <div class="w-full lg:w-6/12 px-4">
            <div class="relative w-full mb-3">
              <x-label-input-form for="username">
                {{ __('Username') }}
              </x-label-input-form>
              <input type="text"
                class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
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
                class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                placeholder="{{ __('Name') }}" name="name" value="{{ old('name', $user->name ?? '') }}" />
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
                class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                placeholder="{{ __('Email') }}" name="email" value="{{ old('email', $user->email ?? '') }}" />
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
                class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                placeholder="{{ __('Password') }}" name="password" />
              @if ($errors->has('password'))
              <x-error-input message="{{ $errors->first('password') }}" />
              @endif
            </div>
          </div>
          <div class="w-full lg:w-6/12 px-4">
            <div class="relative w-full mb-3">
              <x-label-input-form for="role_name">
                {{ __('Role') }}
              </x-label-input-form>
              <div x-data="{
                datas: [],
                oldRoleName: '{{ collect(old('role_name') ?? '[]') }}',
                userRoleName: '{{ collect($user->role_array ?? []) }}',
                async getRoleData(url) {
                fetch('/fetch/master?mode=roles')
                    .then((response) => response.json())
                    .then((response) => {
                        console.log(response);
                        this.datas = response.data;
                    });
                },
              }" x-init="getRoleData" x-cloak>
                <select id="role_name" name="role_name[]"
                  class="borduer-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                  multiple>
                  <template x-for="data in datas">
                    <option :value="data.name"
                      :selected="(oldRoleName.includes(data.name) || userRoleName.includes(data.name)) ? true : false"
                      x-text="data.display_name">
                    </option>
                  </template>
                </select>
              </div>
              @if ($errors->has('role_name'))
              <x-error-input message="{{ $errors->first('role_name') }}" />
              @endif
            </div>
          </div>
        </div>

        <hr class="mt-6 border-b-1 border-blueGray-300" />

        <div class="flex justify-end">
          <div class="py-6 px-3 mt-32 sm:mt-0">
            <a class="bg-indigo-500 active:bg-indigo-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150"
              type="button" href="{{ route('super.users.index') }}">
              <i class="fa fa-arrow-left"></i>&nbsp;{{ __('Back') }}
            </a>
          </div>
          <div class="py-6 px-3 mt-32 sm:mt-0">
            <button
              class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150"
              type="submit">
              {{ __((isset($user) ? 'Edit' : 'Create').' User') }}
            </button>
          </div>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
</x-super-base-layout>