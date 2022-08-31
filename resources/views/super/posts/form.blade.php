<x-super-base-layout>
  {{-- Page Title --}}
  <x-page-title :title="(isset($post) ? 'Edit' : 'Create').' POST'" :role="'Super Admin'" />
  {{-- End Page Title --}}

  {{-- Nav Header --}}
  <x-slot name="navHeader">
    <x-navbar-breadcrumbs :items="[
      [
        'text' => 'Post',
        'url' => route('super.posts.index')
      ],
      [
        'text' => ((isset($post) ? 'Edit' : 'Create').' Post'),
        'url' => (isset($post) ? route('super.posts.edit', compact('post')) : route('super.posts.create'))
      ]
    ]" />
  </x-slot>
  {{-- End Nav Header --}}

  <div class="w-full mb-12 xl:mb-0 px-4">
    <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
      <div class="rounded-t bg-white mb-0 px-6 py-6">
        <div class="text-center flex justify-between">
          <h6 class="text-blueGray-700 text-xl font-bold">
            {{ (isset($post) ? 'Edit' : 'Create').' Post' }}
          </h6>
        </div>
      </div>
      <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
          {{ __('Post Information') }}
        </h6>
        @if(isset($post))
        {{ Form::model($post, ['route' => ['super.posts.update', $post['id']]]) }}
        {{ Form::hidden('post_id',$post->id) }}
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @else
        {{ Form::open(['route' => 'super.posts.store', ]) }}
        @endif
        <div class="flex flex-wrap">
          <div class="w-full px-4">
            <div class="relative w-full mb-3">
              <x-label-input-form for="title">
                {{ __('Title') }}
              </x-label-input-form>
              <input type="text"
                class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                placeholder="{{ __('Title') }}" name="title" value="{{ old('title', $post->title ?? '') }}" />
              @if ($errors->has('title'))
              <x-error-input message="{{ $errors->first('title') }}" />
              @endif
            </div>
          </div>
          <div class="w-full px-4">
            <div class="relative w-full mb-3">
              <x-label-input-form for="description">
                {{ __('Description') }}
              </x-label-input-form>
              <textarea type="text"
                class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                placeholder="{{ __('Description') }}"
                name="description">{{ old('description', $post->description ?? '') }}</textarea>
              @if ($errors->has('description'))
              <x-error-input message="{{ $errors->first('description') }}" />
              @endif
            </div>
          </div>
          <div class="w-full px-4">
            <div class="relative w-full mb-3">
              <x-label-input-form for="status">
                {{ __('Status') }}
              </x-label-input-form>
              <div x-data="{
                url: '/super/fetch/posts?mode=post-status',
                oldStatus: '{{ old('status') ?? '' }}',
                datas: [],

                async getPostStatus(url) {
                  fetch(url)
                  .then((response) => response.json())
                  .then(response => {
                    this.datas = response;
                  })
                },
                async initialize() {
                  this.getPostStatus(this.url);
                }
              }" x-init="initialize" x-cloak>
                <select id="status" name="status"
                  class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150">
                  <template x-for="(value, index) in datas">
                    <option :value="value" :selected="(oldStatus == value) ? true : false" x-text="value">
                    </option>
                  </template>
                </select>
              </div>
              @if ($errors->has('status'))
              <x-error-input message="{{ $errors->first('status') }}" />
              @endif
            </div>
          </div>

          <hr class="mt-6 border-b-1 border-blueGray-300" />

          <div class="flex justify-end">
            <div class="py-6 px-3 mt-32 sm:mt-0">
              <a class="bg-indigo-500 active:bg-indigo-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150"
                type="button" href="{{ route('super.posts.index') }}">
                <i class="fa fa-arrow-left"></i>&nbsp;{{ __('Back') }}
              </a>
            </div>
            <div class="py-6 px-3 mt-32 sm:mt-0">
              <button
                class="bg-pink-500 active:bg-pink-600 uppercase text-white font-bold hover:shadow-md shadow text-xs px-4 py-2 rounded outline-none focus:outline-none sm:mr-2 mb-1 ease-linear transition-all duration-150"
                type="submit">
                {{ __((isset($post) ? 'Edit' : 'Create').' Post') }}
              </button>
            </div>
          </div>
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>

  <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
  <script>
    CKEDITOR.replace('description');
  </script>
</x-super-base-layout>