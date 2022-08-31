<x-super-base-layout>
  {{-- Page Title --}}
  <x-page-title :title="'Dashboard'" :role="'Super Admin'" />
  {{-- End Page Title --}}

  {{-- Nav Header --}}
  <x-slot name="navHeader">
    <x-navbar-header :url="`{{ route('super.dashboard') }}`" :text="'Dashboard'" />
  </x-slot>
  {{-- End Nav Header --}}

  <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
      <div class="rounded-t mb-0 px-4 py-3 border-0">
        <div class="flex flex-wrap items-center">
          <div class="relative w-full px-4 max-w-full flex-grow flex-1">
            <h3 class="font-semibold text-base text-blueGray-700">
              {{ __("Post Summaries") }}
            </h3>
          </div>
          <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
          </div>
        </div>
      </div>
      <div class="block w-full overflow-x-auto">
        <!-- Posts summary table -->
        <div x-data="{
          url: '/super/fetch/dashboard?mode=summary',
          posts: [],
          summaries: [],

          // Functions
          async getSummary(url) {
            fetch(url)
            .then((response) => {
              if (!response.ok) {
                return false;
              }
              return response.json();
            })
            .then((response) => {
              data = response.data;
              this.posts = data.posts;
              summaries = data.summaries;
              summaries.map((summary) => {
                this.summaries[summary.post_id] = summary;
              });
            });
          },
          async initialize() {
            this.getSummary(this.url);
          }
        }" x-init="initialize" x-cloak>
          <table class="items-center w-full bg-transparent border-collapse">
            <thead>
              <tr>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  {{ __("Title") }}
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  {{ __("Likes") }}
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  {{ __("Dislikes") }}
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  {{ __("Comments") }}
                </th>
              </tr>
            </thead>
            <tbody>
              <template x-for="post in posts" :key="post.id">
                <tr>
                  <th
                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                    <span x-text="post.title"></span>
                  </th>
                  <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                    <span x-text="summaries[post.id].likes_sum ?? 0"></span>
                  </td>
                  <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                    <span x-text="summaries[post.id].dislikes_sum ?? 0"></span>
                  </td>
                  <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                    <span x-text="summaries[post.id].comments_sum ?? 0"></span>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="w-full xl:w-4/12 px-4">
    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
      <div class="rounded-t mb-0 px-4 py-3 border-0">
        <div class="flex flex-wrap items-center">
          <div class="relative w-full px-4 max-w-full flex-grow flex-1">
            <h3 class="font-semibold text-base text-blueGray-700">
              {{ __('Profile Summaries') }}
            </h3>
          </div>
          <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
          </div>
        </div>
      </div>
      <div class="block w-full overflow-x-auto">
        <!-- Summary profile -->
        <div x-data="{
          url: '/super/fetch/profile?mode=summary',
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
          <table class="items-center w-full bg-transparent border-collapse">
            <thead class="thead-light">
              <tr>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  {{ __('Type') }}
                </th>
                <th
                  class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                  {{ __('Total') }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                  {{ __('Likes') }}
                </th>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <span x-text="totalLikes"></span>
                </td>
              </tr>
              <tr>
                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                  {{ __('Dislikes') }}
                </th>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <span x-text="totalDislikes"></span>
                </td>
              </tr>
              <tr>
                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                  {{ __('Comments') }}
                </th>
                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                  <span x-text="totalComments"></span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-super-base-layout>