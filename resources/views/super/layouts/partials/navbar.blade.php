<nav
    class="absolute top-0 left-0 w-full z-10 bg-transparent md:flex-row md:flex-nowrap md:justify-start flex items-center p-4">
    <div class="w-full mx-autp items-center flex justify-between md:flex-nowrap flex-wrap md:px-10 px-4">
        @if (!empty($navHeader))
        {{ $navHeader }}
        @else
        <x-navbar-header :url="`{{ route('super.dashboard') }}`" :text="'Dashboard'" />
        @endif
        <form class="md:flex hidden flex-row flex-wrap items-center lg:ml-auto mr-3">
        </form>

        <ul class="flex-col md:flex-row list-none items-center hidden md:flex">
            <li>
                {{-- Notifications --}}
                <div x-data="{
                    datas: [],
                    url: '/fetch/masters?mode=notifications',
                    urlTotalUnread: '/fetch/masters?mode=get-total-unread-notifications',

                    // Helpers
                    show: false,
                    userId: '{{ auth()->id() }}',
                    userNotifAlert: '{{ auth()->id() ? (auth()->user()->setting->is_notif_alert ?? 0) : 0 }}',
                    totalUnread: 0,
                    currentPage: 1,
                    nextPage: '',
                    paginationInfo: [],

                    // Functions
                    async getData(url, isInit = true) {
                        fetch(url)
                        .then((response) => response.json())
                        .then((response) => {
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
                    async changeShow(event, dropdownId) {
                        $store.helper.openDropdown(event, dropdownId)
                        this.show = !this.show;
                        if (this.show) {
                            this.getData(this.url);
                        }
                    },
                    async getTotalUnread() {
                        if (!this.userId) {
                            return;
                        }
                        fetch(this.urlTotalUnread)
                        .then((response) => {
                            if (!response.ok) {
                                return 0;
                            }
                            return response.json();
                        })
                        .then((response) => {
                            this.totalUnread = response.total;
                        })
                    },
                    async listenEvent() {
                        Echo.private(`new-notification.${this.userId}`).listen('NewNotification', (e) => {
                            this.totalUnread += 1;
                            if (parseInt(this.userNotifAlert)) {
                                $store.helper.setAlert('success', e.subject);
                            }
                            this.datas.unshift(e);
                        });
                        Echo.private(`read-notification.${this.userId}`).listen('ReadNotification', (e) => {
                            this.totalUnread -= 1;
                        });
                        Echo.private(`read-all-notification.${this.userId}`).listen('ReadAllNotification', (e) => {
                            this.totalUnread = 0;
                        });
                        Echo.private(`unread-notification.${this.userId}`).listen('UnreadNotification', (e) => {
                            this.totalUnread += 1;
                        });
                        Echo.private(`unread-all-notification.${this.userId}`).listen('UnreadAllNotification', (e) => {
                            this.getTotalUnread();
                        });
                    },
                    async initialize() {
                        this.getTotalUnread();
                        this.listenEvent();
                    }
                }" x-init="initialize" x-cloak>
                    <a class="text-white block py-1 px-3 text-right" href="#"
                        x-on:click="changeShow($event, 'notifications-dropdown')">
                        <i class="fas fa-bell"></i><span class="ml-1" x-text="totalUnread"></span>
                    </a>
                    <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-4 h-350-px overflow-y-auto"
                        id="notifications-dropdown">
                        <div class="flex justify-end mr-3 mb-2 mt-2">
                            <span x-on:click="readAllNotif()" class="cursor-pointer text-indigo-600">
                                {{ __('Read all') }}
                            </span>
                            <span x-on:click="unreadAllNotif()" class="cursor-pointer text-indigo-600 ml-3">
                                {{ __('Unread all') }}
                            </span>
                            <a href="{{ route('super.notifications.index') }}" class="text-indigo-600 ml-3">
                                {{ __('View all') }}
                            </a>
                        </div>
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
                                    <div class="text-blueGray-500 ml-3 mb-2 mt-2 cursor-pointer"
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
                {{-- End Notifications --}}
            </li>
            <li>
                <div x-data x-cloak>
                    <a class="text-blueGray-500 block" href="#"
                        x-on:click="$store.helper.openDropdown($event,'user-dropdown')">
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
                        }" x-init="initialize" x-cloak class="items-center flex">
                            <span
                                class="w-12 h-12 text-sm text-white bg-blueGray-200 inline-flex items-center justify-center rounded-full">
                                <img alt="..." class="w-full rounded-full align-middle border-none shadow-lg"
                                    :src="image" />
                            </span>
                        </div>
                    </a>
                    <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48"
                        id="user-dropdown">
                        <a href="{{ route('home') }}"
                            class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700">
                            {{ __('Home') }}
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700">
                            {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('super.profile.index') }}"
                            class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700">
                            {{ __('Profile') }}
                        </a>
                        <div class="h-0 my-2 border border-solid border-blueGray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                {{ __('Logout') }}
                            </x-dropdown-link>
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>