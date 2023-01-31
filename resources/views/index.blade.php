<x-base-layout>
    {{-- Page Title --}}
    <x-page-title :title="__('Landing')" />
    {{-- End Page Title --}}

    <section class="mt-48 md:mt-40 pb-40 relative bg-slate-100">
        <div class="-mt-20 top-0 bottom-auto left-0 right-0 w-full absolute h-20" style="transform: translateZ(0)">
            <svg class="absolute bottom-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                version="1.1" viewBox="0 0 2560 100" x="0" y="0">
                <polygon class="text-slate-100 fill-current" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
        <div class="container mx-auto">
            <div class="flex flex-wrap items-center">
                <div class="w-full px-4">
                    <div x-data="{
                        posts: [],
                        url: '/fetch/posts?mode=datatable',

                        // Helpers
                        userId: '{{ auth()->id() }}',
                        currentPage: 1,
                        nextPage: '',
                        paginationInfo: [],

                        // Functions
                        async getPosts(url, isInit = true) {
                            fetch(url)
                            .then((response) => {
                                if (!response.ok) {
                                    return false;
                                }
                                return response.json();
                            })
                            .then((response) => {
                                if (isInit) {
                                    this.posts = response.data;
                                } else {
                                    response.data.map((data) => {
                                        this.posts.push(data);
                                    })   
                                }
                                this.paginationInfo = response.meta;
                                this.currentPage = this.paginationInfo.current_page; 
                                links = response.links ?? null;
                                if (links) {
                                    this.nextPage = links.next;
                                }
                            })
                            .catch(e => {
                                // console.log('error');
                                // console.log(e)
                            });
                        },
                        async loadMore() {
                            if (!this.nextPage) {
                                return;
                            }
                            url = this.url+'&page='+this.nextPage;
                            this.getPosts(url, false);
                        },
                        async initialize() {
                            this.getPosts(this.url);
                        },
                    }" x-init="initialize" x-cloak>

                        <template x-for="(post, index) in posts" :key="index">
                            <div class="m-4">
                                <a class="text-xl mb-1 font-semibold" :href="`posts/${post.slug}`"
                                    x-text="post.title"></a><br>
                                <span class="text-s" x-text="`{{ __('by') }} : ` + post.author_name"></span>
                                <i class="italic" x-show="post.is_edited">({{ __('Edited') }})</i>
                                <p class="text-lg font-light leading-relaxed mt-4 mb-4 text-slate-600"
                                    x-html="post.description">
                                </p>
                                <div class="mb-4">
                                    <div x-data="{
                                        postLike: '',
                                        url: '/fetch/posts?mode=check-like&id=' + post.id,
                                        urlLikeDislike: '/fetch/posts?mode=like-dislike&id=' + post.id,

                                        // Helpers
                                        postLiked: '',
                                        postDisliked: '',
                                        postSummaryLikes: 0,
                                        postSummaryDislikes: 0,
                                        postSummaryComments: 0,

                                        // Functions
                                        async setSummary() {
                                            this.postLiked = this.postLike?.is_like;
                                            this.postDisliked = this.postLike?.is_dislike;
                                            this.postSummaryLikes = post.summary?.likes;
                                            this.postSummaryDislikes = post.summary?.dislikes;
                                            this.postSummaryComments = post.summary?.comments;
                                        },
                                        async getPostLike(url) {
                                            fetch(url)
                                            .then((response) => {
                                                if (!response.ok) {
                                                    return false;
                                                }
                                                return response.json();
                                            })
                                            .then(response => {
                                                this.postLike = response.data;
                                                this.setSummary();
                                            })
                                            .catch(e => {
                                                // console.log('error');
                                                // console.log(e)
                                            });
                                        },
                                        async likeDislike(value) {
                                            fetch(this.urlLikeDislike + '&value=' + value)
                                            .then((response) => {
                                                if (!response.ok) {
                                                    return false;
                                                }
                                                return response.json();
                                            })
                                            .then(response => {
                                                if (value == 1) {
                                                    this.postLiked = 1;
                                                    this.postSummaryLikes += 1;
                                                } else if (value == 0) {
                                                    this.postDisliked = 1;
                                                    this.postSummaryDislikes += 1;
                                                } else {
                                                    if (this.postLiked) {
                                                        this.postLiked = 0;
                                                        this.postSummaryLikes -= 1;
                                                    } else if (this.postDisliked) {
                                                        this.postDisliked = 0;
                                                        this.postSummaryDislikes -= 1;
                                                    }
                                                }
                                            })
                                            .catch(e => {
                                                // console.log('error');
                                                // console.log(e)
                                            });
                                        },
                                        async initialize() {
                                            if (!this.userId) {
                                                return;
                                            }
                                            this.getPostLike(this.url);
                                        }
                                    }" x-init="initialize" x-cloak :key="index">
                                        @auth
                                        <i x-on:click="postDisliked ? '#' : (postLiked ? likeDislike(null) : likeDislike(1))"
                                            :class="'cursor-pointer fa-solid fa-thumbs-up ' + (postLiked ? 'text-indigo-600' : '')"></i>&nbsp;
                                        <span x-text="postSummaryLikes ?? 0"></span>&nbsp;&nbsp;
                                        <i x-on:click="postLiked ? '#' : (postDisliked ? likeDislike(null) : likeDislike(0))"
                                            :class="'cursor-pointer fa-solid fa-thumbs-down ' + (postDisliked ? 'text-red-500' : '')"></i>&nbsp;
                                        <span x-text="postSummaryDislikes ?? 0"></span>&nbsp;&nbsp;
                                        <i
                                            :class="'cursor-pointer fa-solid fa-comments ' + (postSummaryComments ? 'text-indigo-600' : '')"></i>&nbsp;
                                        <span x-text="postSummaryComments ?? 0"></span>
                                        @else
                                        <a href="{{ route('login') }}">
                                            <i class="fa-solid fa-thumbs-up"></i>&nbsp;
                                            <span x-text="post.summary?.likes ?? 0"></span>&nbsp;&nbsp;
                                        </a>
                                        <a href="{{ route('login') }}">
                                            <i class="fa-solid fa-thumbs-down"></i>&nbsp;
                                            <span x-text="post.summary?.dislikes ?? 0"></span>&nbsp;&nbsp;
                                        </a>
                                        <a href="{{ route('login') }}">
                                            <i
                                                :class="'fa-solid fa-comments ' + (post.summary?.comments ? 'text-indigo-600' : '')"></i>&nbsp;
                                            <span x-text="post.summary?.comments ?? 0"></span>
                                        </a>
                                        @endauth
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </template>

                        {{-- Load More --}}
                        <template x-if="nextPage">
                            <button
                                class="bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                type="button" x-on:click="loadMore()" :id="'load-more-btn'">
                                <i class="fa-solid fa-arrows-rotate"></i>&nbsp;&nbsp;{{ __('Load more') }}
                            </button>
                        </template>

                    </div>
                </div>
            </div>
        </div>
    </section>
</x-base-layout>