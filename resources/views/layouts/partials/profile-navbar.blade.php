<nav class="top-0 absolute z-50 w-full flex flex-wrap items-center justify-between px-2 py-3 navbar-expand-lg">
    <div class="container px-4 mx-auto flex flex-wrap items-center justify-between">
        <div class="w-full relative flex justify-between lg:w-auto lg:static lg:block lg:justify-start">
            <a class="text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-nowrap uppercase text-white"
                href="{{ route('home') }}">{{ __('Home') }}</a><button
                class="cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none"
                type="button" onclick="toggleNavbar('example-collapse-navbar')">
                <i class="text-white fas fa-bars"></i>
            </button>
        </div>
        <div class="lg:flex flex-grow items-center bg-white lg:bg-opacity-0 lg:shadow-none hidden"
            id="example-collapse-navbar">
            <ul class="flex flex-col lg:flex-row list-none lg:ml-auto items-center">
                <li class="inline-block relative">
                    <div x-data x-cloak>
                        <a class="lg:text-white lg:hover:text-slate-200 text-slate-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
                            href="#" x-on:click="$store.helper.openDropdown($event,'user-landing-dropdown')">
                            {{ auth()->user()->name }}
                        </a>
                        <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48"
                            id="user-landing-dropdown">
                            <span
                                class="text-sm pt-2 pb-0 px-4 font-bold block w-full whitespace-nowrap bg-transparent text-slate-400">
                                {{ __('User') }}
                            </span>
                            <a href="{{ route('dashboard') }}"
                                class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                                {{ __('Dashboard') }}
                            </a>
                            @php
                            $user = auth()->user();
                            $role = 'user';
                            if ($user->hasRole('super')) {
                            $role = 'super';
                            } elseif ($user->hasRole('admin')) {
                            $role = 'admin';
                            }
                            $route = $role.'.profile.index';
                            @endphp
                            <a href="{{ route($route) }}"
                                class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                                {{ __('Profile') }}
                            </a>
                            <div class="h-0 mx-4 my-2 border border-solid border-slate-100"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                this.closest('form').submit();"
                                    class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-slate-700">
                                    {{ __('Logout') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </li>
                <li class="">
                    {{-- Notifications --}}
                    <div x-data="{
                    datas: [],
                    url: '/fetch/masters?mode=notifications',
                    urlTotalUnread: '/fetch/masters?mode=get-total-unread-notifications',

                    // Helpers
                    show: false,
                    urlViewAll: '/notifications',
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
                    async getUrlViewAll() {
                        if (`{{ auth()->user()->hasRole('super') }}`) {
                            this.urlViewAll = '/super/notifications';
                        } else if (`{{ auth()->user()->hasRole('admin') }}`) {
                            this.urlViewAll = '/admin/notifications';
                        }
                    },
                    async initialize() {
                        this.getTotalUnread();
                        this.listenEvent();
                        this.getUrlViewAll();
                    }
                }" x-init="initialize" x-cloak>
                        <a class="hover:text-slate-500 text-white block py-1 px-3 text-right" href="#"
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
                                <a :href="urlViewAll" class="text-indigo-600 ml-3">
                                    {{ __('View all') }}
                                </a>
                            </div>
                            <template x-for="(data, index) in datas">
                                <div>
                                    <template x-if="data.read_at">
                                        <div class="ml-3 mb-2 mt-2 cursor-pointer"
                                            x-on:click="unreadNotif(data.id, index)">
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
                    {{-- End Notifications --}}
                </li>
                <li class="flex items-center">
                    <a class="lg:text-white lg:hover:text-slate-200 text-slate-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
                        href="https://www.linkedin.com/in/ramdani-ramdani-4380ba180/" target="_blank">
                        <i class="lg:text-slate-200 text-slate-400 fab fa-linkedin text-lg leading-lg"></i>
                        <span class="lg:hidden inline-block ml-2">LinkedIn</span>
                    </a>
                </li>
                <li class="flex items-center">
                    <a class="lg:text-white lg:hover:text-slate-200 text-slate-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
                        href="https://twitter.com/ramdaniB15" target="_blank">
                        <i class="lg:text-slate-200 text-slate-400 fab fa-twitter text-lg leading-lg"></i>
                        <span class="lg:hidden inline-block ml-2">Twitter</span>
                    </a>
                </li>
                <li class="flex items-center">
                    <a class="lg:text-white lg:hover:text-slate-200 text-slate-700 px-3 py-4 lg:py-2 flex items-center text-xs uppercase font-bold"
                        href="https://github.com/ramdani15" target="_blank">
                        <i class="lg:text-slate-200 text-slate-400 fab fa-github text-lg leading-lg"></i>
                        <span class="lg:hidden inline-block ml-2">GitHub</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>