<x-super-base-layout>
  {{-- Page Title --}}
  <x-page-title :title="__('Permission')" :role="'Super Admin'" />
  {{-- End Page Title --}}

  {{-- Nav Header --}}
  <x-slot name="navHeader">
    <x-navbar-breadcrumbs :items="[
      [
        'text' => 'Permission',
        'url' => route('super.permissions.index')
      ]
    ]" />
  </x-slot>
  {{-- End Nav Header --}}

  {{-- Header Page --}}
  <x-slot name="headerPage">
    <div class="text-right">
      <a class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
        href="{{ route('super.permissions.create') }}">
        {{ __('Create Permission') }}
      </a>
    </div>
  </x-slot>
  {{-- End Header Page --}}

  <div class="w-full mb-12 xl:mb-0 px-4">
    <x-table-simple :id="'permissions-table'" :columns="[
            [
                'name' => 'Name',
                'field' => 'name',
            ],
            [
                'name' => 'Display Name',
                'field' => 'display_name',
            ],
            [
                'name' => 'Roles',
                'field' => 'role_array',
            ]
        ]" :urlData="'/super/fetch/permissions?mode=datatable'">
      <x-slot name="linkColumn">
        <div class="flex flex-wrap space-x-4">
          <x-edit-action url='permissions' id='row.id'>
            <span x-text="row.id"></span>
          </x-edit-action>
        </div>
      </x-slot>
      <x-slot name="actionColumn">
        <div class="flex">
          <x-edit-action url='permissions' id='row.id' class="m-2">
            <i class="fa fa-edit"></i>
          </x-edit-action>
          <x-delete-action url='permissions' id='row.id' class="m-2">
            <i class="fa fa-trash"></i>
          </x-delete-action>
          <x-crud-action url='{{ route("super.permissions.index") }}' id="row.id" isEdit="true" isDelete="true" />
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
            display_name: '',
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
              this.display_name = '';
              this.childQueryParam = {};
              this.search();
            },
            async initialize() {
              this.getRoleData();
            }
          }" x-init="initialize" x-cloak>

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

            {{-- By Display Name --}}
            <div class="w-full lg:w-3/12 px-4">
              <div class="relative w-full mb-3">
                <x-label-input-form for="display_name">
                  {{ __('Display Name')}}
                </x-label-input-form>
                <input
                  class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                  placeholder="{{ __('Display Name') }}" x-model="display_name" id="display_name"
                  x-on:change="setQueryParams('display_name', display_name)" />
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