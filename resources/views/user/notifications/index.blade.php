<x-base-layout>
    {{-- Page Title --}}
    <x-page-title :title="__('Notification')" :role="'User'" />
    {{-- End Page Title --}}

    <section class="mt-48 md:mt-40 pb-40 relative bg-slate-100">
        <div class="-mt-20 top-0 bottom-auto left-0 right-0 w-full absolute h-20" style="transform: translateZ(0)">
            <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                <polygon class="text-slate-100 fill-current" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
        <div class="container mx-auto">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded" x-data="{
                datas: [],
                url: '/fetch/masters?mode=notifications',
        
                // Helpers
                userId: '{{ auth()->id() }}',
                currentPage: 1,
                nextPage: '',
                paginationInfo: [],
        
                // Functions
                async getData(url, isInit = true) {
                    fetch(url)
                    .then((response) => response.json())
                    .then((response) => {
                        console.log(response);
                        if (isInit) {
                            this.datas = response.data;
                        } else {
                            response.data.map((data) => {
                                this.datas.push(data);
                            });
                        }
                        this.paginationInfo = response.meta;
                        this.currentPage = this.paginationInfo.current_page; 
                        links = response.links ?? null;
                        if (links) {
                            this.nextPage = links.next;
                        }
                    });
                },
                async readNotif(id, index) {
                    fetch('/fetch/master?mode=read-notification', {
                        method: 'PATCH',
                        headers: $store.config.headers,
                        body: JSON.stringify({
                            id: id,
                        })
                    })
                    .then((response) => {
                        if (!response.ok) {
                            return false;
                        }
                        return response.json();
                    })
                    .then((response) => {
                        this.datas[index].read_at = true;
                        $store.helper.setAlert(response.status ? 'success' : 'error', response.message);
                    })
                },
                async readAllNotif() {
                    fetch('/fetch/master?mode=read-all-notification', {
                        method: 'PATCH',
                        headers: $store.config.headers,
                    })
                    .then((response) => {
                        if (!response.ok) {
                            return false;
                        }
                        return response.json();
                    })
                    .then((response) => {
                        this.datas.map((data, index) => {
                            if (!data.read_at) {
                                data.read_at = true;
                            }
                        });
                        $store.helper.setAlert(response.status ? 'success' : 'error', response.message);
                    })
                },
                async unreadNotif(id, index) {
                    fetch('/fetch/master?mode=unread-notification', {
                        method: 'PATCH',
                        headers: $store.config.headers,
                        body: JSON.stringify({
                            id: id,
                        })
                    })
                    .then((response) => {
                        if (!response.ok) {
                            return false;
                        }
                        return response.json();
                    })
                    .then((response) => {
                        this.datas[index].read_at = false;
                        $store.helper.setAlert(response.status ? 'success' : 'error', response.message);
                    })
                },
                async unreadAllNotif() {
                    fetch('/fetch/master?mode=unread-all-notification', {
                        method: 'PATCH',
                        headers: $store.config.headers,
                    })
                    .then((response) => {
                        if (!response.ok) {
                            return false;
                        }
                        return response.json();
                    })
                    .then((response) => {
                        this.datas.map((data, index) => {
                            if (data.read_at) {
                                data.read_at = false;
                            }
                        });
                        $store.helper.setAlert(response.status ? 'success' : 'error', response.message);
                    })
                },
                async loadMore() {
                    if (!this.nextPage) {
                        return;
                    }
                    url = this.url+'&page='+this.nextPage;
                    this.getData(url, false);
                },
                async listenEvent() {
                    Echo.private(`new-notification.${this.userId}`).listen('NewNotification', (e) => {
                        this.datas.unshift(e);
                    });
                },
                async initialize() {
                    this.getData(this.url);
                    this.listenEvent();
                }
            }" x-init="initialize" x-cloak>
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex justify-end">
                            <span x-on:click="readAllNotif()" class="cursor-pointer text-indigo-600">
                                {{ __('Read all') }}
                            </span>
                            <span x-on:click="unreadAllNotif()" class="cursor-pointer text-indigo-600 ml-3">
                                {{ __('Unread all') }}
                            </span>
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto">
                        <template x-for="(data, index) in datas">
                            <div>
                                <template x-if="data.read_at">
                                    <div class="ml-3 mb-2 mt-2 cursor-pointer" x-on:click="unreadNotif(data.id, index)">
                                        <h4 class="font-bold" x-text="data.subject"></h4>
                                        <span x-text="data.message"></span>
                                        <hr>
                                    </div>
                                </template>
                                <template x-if="!data.read_at">
                                    <div class="text-slate-500 ml-3 mb-2 mt-2 cursor-pointer"
                                        x-on:click="readNotif(data.id, index)">
                                        <div class="flex">
                                            <h4 class="font-bold" x-text="data.subject"></h4>
                                            <span class="text-red-500">&#x2022;</span>
                                        </div>
                                        <span x-text="data.message"></span>
                                        <hr>
                                    </div>
                                </template>
                            </div>
                        </template>
                        {{-- Load More --}}
                        <template x-if="nextPage">
                            <div class="text-center">
                                <button
                                    class="bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                    type="button" x-on:click="loadMore()" :id="'load-more-btn'">
                                    <i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;{{ __('Load more') }}
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-base-layout>