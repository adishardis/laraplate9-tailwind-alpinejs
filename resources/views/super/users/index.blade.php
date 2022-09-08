<x-super-base-layout>
  {{-- Page Title --}}
  <x-page-title :title="__('User')" :role="'Super Admin'" />
  {{-- End Page Title --}}

  {{-- Nav Header --}}
  <x-slot name="navHeader">
    <x-navbar-breadcrumbs :items="[
      [
        'text' => 'User',
        'url' => route('super.users.index')
      ]
    ]" />
  </x-slot>
  {{-- End Nav Header --}}

  {{-- Header Page --}}
  <x-slot name="headerPage">
    <div class="text-right">
      <a class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
        href="{{ route('super.users.create') }}">
        {{ __('Create User') }}
      </a>
    </div>
  </x-slot>
  {{-- End Header Page --}}

  <div class="w-full mb-12 xl:mb-0 px-4">
    <x-table-simple :id="'users-table'" :columns="[
            [
                'name' => 'Name',
                'field' => 'name',
            ],
            [
                'name' => 'Email',
                'field' => 'email',
            ],
            [
                'name' => 'Roles',
                'field' => 'role_array',
            ]
        ]" :urlData="'/super/fetch/users?mode=datatable'">
      <x-slot name="linkColumn">
        <div class="flex flex-wrap space-x-4">
          <x-edit-action url='users' id='row.id' isTextId="true" />
        </div>
      </x-slot>
      <x-slot name="actionColumn">
        <div class="flex flex-wrap space-x-4">
          <x-crud-action url='{{ route("super.users.index") }}' id="row.id" isEdit="true" isDelete="true" />
        </div>
      </x-slot>

      {{-- Filtering --}}
      <div class="flex-auto p-4">
        <div x-data="{
            url: '/fetch/masters?mode=roles',
            roleData: [],

            // Params
            roleName: '',
            name: '',
            email: '',
            childQueryParam: {},

            // Functions
            async getRoleData() {
                fetch(this.url)
                .then((response) => response.json())
                .then((response) => {
                    console.log(response);
                    this.roleData = response.data;
                });
            },
            async setQueryParams(modelName, value) {
              this.childQueryParam[modelName] = value;
            },
            async search() {
              params = JSON.parse(JSON.stringify(this.childQueryParam));
              queryParams = new URLSearchParams(params).toString();
              changePage(urlData + '&' + queryParams);
            },
            async reset() {
              this.roleName = '';
              this.name = '';
              this.email = '';
              this.childQueryParam = {};
              this.search();
            },
            async init() {
              this.getRoleData();
            }
          }" x-init="init" x-cloak>

          {{-- Filters form --}}
          <div class="w-full flex">
            {{-- By Role --}}
            <div class="w-full lg:w-3/12 px-4">
              <div class="relative w-full mb-3">
                <x-label-input-form for="roleName">
                  {{ __('Role')}}
                </x-label-input-form>
                <select
                  class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                  x-model="roleName" id="roleName" x-on:change="setQueryParams('role', roleName)">
                  <option value="">{{ __('Select Role') }}</option>
                  <template x-for="data in roleData">
                    <option :value="data.name" x-text="data.display_name">
                    </option>
                  </template>
                </select>
              </div>
            </div>

            {{-- By Name --}}
            <div class="w-full lg:w-3/12 px-4">
              <div class="relative w-full mb-3">
                <x-label-input-form for="name">
                  {{ __('Name')}}
                </x-label-input-form>
                <input
                  class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                  placeholder="{{ __('Name') }}" x-model="name" id="name" x-on:change="setQueryParams('name', name)" />
              </div>
            </div>

            {{-- By Email --}}
            <div class="w-full lg:w-3/12 px-4">
              <div class="relative w-full mb-3">
                <x-label-input-form for="email">
                  {{ __('Email')}}
                </x-label-input-form>
                <input
                  class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                  placeholder="{{ __('Email') }}" x-model="email" id="email"
                  x-on:change="setQueryParams('email', email)" />
              </div>
            </div>
          </div>

          {{-- Button Actions --}}
          <div class="w-full text-right p-4">
            {{-- Refresh --}}
            <button
              class="bg-lightBlue-500 text-white active:bg-lightBlue-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
              type="button" x-on:click="reset()">
              <i class="fa fa-eraser"></i>&nbsp;&nbsp;{{ __('Reset') }}
            </button>

            {{-- Search --}}
            <button
              class="bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
              type="button" x-on:click="search()">
              <i class="fa fa-search"></i>&nbsp;&nbsp;{{ __('Search') }}
            </button>
          </div>
        </div>
      </div>
      {{-- End Filtering --}}

    </x-table-simple>
  </div>
</x-super-base-layout>