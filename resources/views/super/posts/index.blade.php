<x-super-base-layout>
  {{-- Page Title --}}
  <x-page-title :title="__('Post')" :role="'Super Admin'" />
  {{-- End Page Title --}}

  {{-- Nav Header --}}
  <x-slot name="navHeader">
    <x-navbar-breadcrumbs :items="[
      [
        'text' => 'Post',
        'url' => route('super.posts.index')
      ]
    ]" />
  </x-slot>
  {{-- End Nav Header --}}

  {{-- Header Page --}}
  <x-slot name="headerPage">
    <div class="text-right">
      <a class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
        href="{{ route('super.posts.create') }}">
        {{ __('Create Post') }}
      </a>
    </div>
  </x-slot>
  {{-- End Header Page --}}

  <div class="w-full mb-12 xl:mb-0 px-4">
    <x-table-simple :id="'posts-table'" :columns="[
            [
                'name' => 'Title',
                'field' => 'title',
            ],
            [
                'name' => 'Description',
                'field' => 'description',
                'isHtml' => true
            ],
            [
                'name' => 'Status',
                'field' => 'status',
            ],
            [
                'name' => 'Likes',
                'field' => 'summary_likes',
            ],
            [
                'name' => 'Dislikes',
                'field' => 'summary_dislikes',
            ],
            [
                'name' => 'Comments',
                'field' => 'summary_comments',
            ],
            [
                'name' => 'Author',
                'field' => 'author_name',
            ]
        ]" :urlData="'/super/fetch/posts?mode=datatable'">
      <x-slot name="linkColumn">
        <div class="flex flex-wrap space-x-4">
          <x-edit-action url='posts' id='row.id'>
            <span x-text="row.id"></span>
          </x-edit-action>
        </div>
      </x-slot>
      <x-slot name="actionColumn">
        <div class="flex">
          <x-edit-action url='posts' id='row.id' class="m-2">
            <i class="fa fa-edit"></i>
          </x-edit-action>
          <x-delete-action url='posts' id='row.id' class="m-2">
            <i class="fa fa-trash"></i>
          </x-delete-action>
          <x-crud-action url='{{ route("super.posts.index") }}' id="row.id" isEdit="true" isDelete="true" />
        </div>
      </x-slot>

      {{-- Filtering --}}
      <div class="flex-auto p-4">
        <div x-data="{
            // Params
            title: '',
            description: '',
            childQueryParam: {},

            // Functions
            async setQueryParams(modelName, value) {
              this.childQueryParam[modelName] = value;
            },
            async search() {
              params = JSON.parse(JSON.stringify(this.childQueryParam));
              queryParams = new URLSearchParams(params).toString();
              changePage(urlData + '&' + queryParams);
            },
            async reset() {
              this.title = '';
              this.description = '';
              this.childQueryParam = {};
              this.search();
            }
          }" x-cloak>

          {{-- Filters form --}}
          <div class="w-full flex">
            {{-- By Title --}}
            <div class="w-full lg:w-3/12 px-4">
              <div class="relative w-full mb-3">
                <x-label-input-form for="title">
                  {{ __('Title')}}
                </x-label-input-form>
                <input
                  class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                  placeholder="{{ __('Title') }}" x-model="title" id="title"
                  x-on:change="setQueryParams('title', title)" />
              </div>
            </div>

            {{-- By Description --}}
            <div class="w-full lg:w-3/12 px-4">
              <div class="relative w-full mb-3">
                <x-label-input-form for="description">
                  {{ __('Description')}}
                </x-label-input-form>
                <input
                  class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                  placeholder="{{ __('Description') }}" x-model="description" id="description"
                  x-on:change="setQueryParams('description', description)" />
              </div>
            </div>
          </div>

          {{-- Button Actions --}}
          <div class="w-full text-right p-4">
            {{-- Refresh --}}
            <button
              class="bg-sky-500 text-white active:bg-sky-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
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