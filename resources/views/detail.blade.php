<x-base-layout>
    {{-- Page Title --}}
    <x-page-title :title="$post->title" />
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
                <div class="w-full px-4" x-data="{
                    post: {{ $post }},
                    urlPostComments: '/fetch/comments?mode=post-comments&post_id=' + '{{ $post->id }}',
                    urlPostSummary: '/fetch/posts?mode=get-summary&id=' + '{{ $post->id }}',
                    postSummary: [],

                    baseUrlCommentLikeDislike: '/fetch/comments?mode=like-dislike',
                    baseUrlCommentSummary: '/fetch/comments?mode=get-summary',
                    baseUrlCommentCheckLike: '/fetch/comments?mode=check-like',
                    comments: [],
                    commentSummary: [],
                    commentLike: [],
                    commentLiked: [],
                    commentDisliked: [],
                
                    // Helpers
                    currentPage: 1,
                    nextPage: '',
                    paginationInfo: [],
                    userId: `{{ auth()->id() ?? '' }}`,
                    userName: `{{ auth()->user()->name ?? '' }}`,
                    commentInput: '',
                    commentReplyInput: {},
                    commentReplyStatus: {},
                
                    // Functions
                    async getPostSummary(url) {
                        fetch(url)
                        .then((response) => {
                            if (!response.ok) {
                                return false;
                            }
                            return response.json();
                        })
                        .then((response) => {
                            data = response.data
                            this.postSummary = data;
                        })
                        .catch(e => {
                            // console.log('error');
                            // console.log(e)
                        });
                    },

                    // Comments Section
                    async updateCommentReplyStatus(commentId) {
                        this.commentReplyStatus[commentId] = !this.commentReplyStatus[commentId];

                        // Hide others reply comment
                        if (this.commentReplyStatus[commentId]) {
                            Object.entries(this.commentReplyStatus).map((
                                [key, value]) =>
                                {
                                    if (key != commentId) {
                                        this.commentReplyStatus[key] = 0;
                                    }
                                }
                            );
                        }
                    },
                    async setCommentReplyHelper() {
                        this.comments.map((comment) => {
                            this.resetReplyStatusInput(comment.id);
                        });
                    },
                    async resetReplyStatusInput(commentId) {
                        this.commentReplyStatus[commentId] = 0;
                        this.commentReplyInput[commentId] = '';
                    },
                    async pushNewReplyComment(parentId, commentId) {
                        data = {
                            id: commentId,
                            user_name: this.userName,
                            parent_id: parentId,
                            comment: this.commentReplyInput[parentId],
                            is_edited: 0,
                            user_id: this.userId,
                        };
                        commentIndex = this.comments.findIndex(obj => {
                            return obj.id === parentId;
                        });

                        // push new comment
                        this.comments[commentIndex].childrens.unshift(data);

                        // update count comment
                        this.postSummary.comments += 1;
                        
                        // reset reply input and status
                        this.resetReplyStatusInput(parentId);
                    },
                    async pushNewComment(commentId) {
                        data = {
                            id: commentId,
                            user_name: this.userName,
                            comment: this.commentInput,
                            is_edited: 0,
                            user_id: this.userId,
                            childrens: []
                        };

                        // push new comment
                        this.comments.unshift(data);
                        // update count comment
                        this.postSummary.comments += 1;
                        // reset input
                        this.commentInput = '';
                    },
                    async addReplyComment(parentId) {
                        if (!this.commentReplyInput[parentId].length) {
                            return;
                        }
                        fetch('/fetch/comments?mode=add-comment', {
                            method: 'POST',
                            headers: $store.config.headers,
                            body: JSON.stringify({
                                post_id: this.post.id,
                                parent_id: parentId,
                                comment: this.commentReplyInput[parentId],
                            })
                        })
                        .then((response) => {
                            if (!response.ok) {
                                return false;
                            }
                            return response.json();
                        })
                        .then((response) => {
                            console.log(response);
                            commentId = response.data.id;
                            this.pushNewReplyComment(parentId, commentId);
                        })
                        .catch(e => {
                            // console.log('error');
                            // console.log(e)
                        });
                    },
                    async addComment() {
                        if (!this.commentInput.length) {
                            return;
                        }
                        fetch('/fetch/comments?mode=add-comment', {
                            method: 'POST',
                            headers: $store.config.headers,
                            body: JSON.stringify({
                                post_id: this.post.id,
                                comment: this.commentInput,
                            })
                        })
                        .then((response) => {
                            if (!response.ok) {
                                return false;
                            }
                            return response.json();
                        })
                        .then((response) => {
                            commentId = response.data.id;
                            this.pushNewComment(commentId);
                        })
                        .catch(e => {
                            // console.log('error');
                            // console.log(e)
                        });
                    },
                    async commentLikeDislike(commentId, value) {
                        url = this.baseUrlCommentLikeDislike + '&id=' + commentId + '&value=' + value;
                        fetch(url)
                        .then((response) => {
                            if (!response.ok) {
                                return false;
                            }
                            return response.json();
                        })
                        .then(response => {
                            if (value == 1) {
                                this.commentLiked[commentId] = 1;
                                this.commentSummary[commentId].likes += 1;
                            } else if (value == 0) {
                                this.commentDisliked[commentId] = 1;
                                this.commentSummary[commentId].dislikes += 1;
                            } else {
                                if (this.commentLiked[commentId]) {
                                    this.commentLiked[commentId] = 0;
                                    this.commentSummary[commentId].likes -= 1;
                                } else if (this.commentDisliked[commentId]) {
                                    this.commentDisliked[commentId] = 0;
                                    this.commentSummary[commentId].dislikes -= 1;
                                } 
                            }
                        })
                        .catch(e => {
                            // console.log('error');
                            // console.log(e)
                        });
                    },
                    async getCommentSummary(commentId) {
                        url = this.baseUrlCommentSummary + '&id=' + commentId;
                        fetch(url)
                        .then((response) => {
                            if (!response.ok) {
                                return false;
                            }
                            return response.json();
                        })
                        .then(response => {
                            this.commentSummary[commentId] = response.data;
                        })
                        .catch(e => {
                            // console.log('error');
                            // console.log(e)
                        });
                    },
                    async getCommentLike(commentId) {
                        url = this.baseUrlCommentCheckLike + '&id=' + commentId;
                        fetch(url)
                        .then((response) => {
                            if (!response.ok) {
                                return false;
                            }
                            return response.json();
                        })
                        .then(response => {
                            this.commentLike[commentId] = response.data;
                            this.commentLiked[commentId] = this.commentLike[commentId]?.is_like ?? 0;
                            this.commentDisliked[commentId] = this.commentLike[commentId]?.is_dislike ?? 0;
                        })
                        .catch(e => {
                            // console.log('error');
                            // console.log(e)
                        });
                    },
                    async getComments(url, isInit = true) {
                        fetch(url)
                        .then((response) => {
                            if (!response.ok) {
                                return false;
                            }
                            return response.json();
                        })
                        .then((response) => {
                            if (isInit) {
                                this.comments = response.data;
                            } else {
                                response.data.map((data) => {
                                    this.comments.push(data);
                                });
                            }
                            this.paginationInfo = response.meta;
                            this.currentPage = this.paginationInfo.current_page; 
                            links = response.links ?? null;
                            if (links) {
                                this.nextPage = links.next;
                            }
                            this.setCommentReplyHelper();
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
                        url = this.urlPostComments+'&page='+this.nextPage;
                        this.getComments(url, false);
                    },
                    // End Comments Section

                    async initialize() {
                        this.getPostSummary(this.urlPostSummary);
                        this.getComments(this.urlPostComments);
                    },
                }" x-init="initialize" x-cloak>
                    <template x-if="post">
                        <div class="m-4">
                            {{-- Main Content --}}
                            <div class="mb-4">
                                <h6 class="text-xl mb-1 font-semibold" x-text="post.title"></h6>
                                <span class="text-s" x-text="`{{ __('by') }} : ` + post.author?.name"></span>
                                <i class="italic" x-show="post.is_edited">({{ __('Edited') }})</i>
                                <p class="text-lg font-light leading-relaxed mt-4 mb-4 text-slate-600"
                                    x-html="post.description">
                                </p>

                                {{-- Likes, Dislikes and Comments Post --}}
                                <div class="mb-4">
                                    <div x-data="{
                                        postLike: '',
                                        url: '/fetch/posts?mode=check-like&id=' + post.id,
                                        urlLikeDislike: '/fetch/posts?mode=like-dislike&id=' + post.id,
                
                                        // Helpers
                                        postId: post.id,
                                        postLiked: '',
                                        postDisliked: '',
                
                                        // Functions
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
                                                this.postLiked = this.postLike?.is_like;
                                                this.postDisliked = this.postLike?.is_dislike;
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
                                                    this.postSummary.likes += 1;
                                                } else if (value == 0) {
                                                    this.postDisliked = 1;
                                                    this.postSummary.dislikes += 1;
                                                } else {
                                                    if (this.postLiked) {
                                                        this.postLiked = 0;
                                                        this.postSummary.likes -= 1;
                                                    } else if (this.postDisliked) {
                                                        this.postDisliked = 0;
                                                        this.postSummary.dislikes -= 1;
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
                                        },
                                    }" x-init="initialize" x-cloak>
                                        @auth
                                        <i x-on:click="postDisliked ? '#' : (postLiked ? likeDislike(null) : likeDislike(1))"
                                            :class="'cursor-pointer fa-solid fa-thumbs-up ' + (postLiked ? 'text-indigo-600' : '')"></i>&nbsp;
                                        <span x-text="postSummary?.likes ?? 0"></span>&nbsp;&nbsp;
                                        <i x-on:click="postLiked ? '#' : (postDisliked ? likeDislike(null) : likeDislike(0))"
                                            :class="'cursor-pointer fa-solid fa-thumbs-down ' + (postDisliked ? 'text-red-500' : '')"></i>&nbsp;
                                        <span x-text="postSummary?.dislikes ?? 0"></span>&nbsp;&nbsp;
                                        <i
                                            :class="'fa-solid fa-comments ' + (postSummary?.comments ? 'text-indigo-600' : '')"></i>&nbsp;
                                        <span x-text="postSummary?.comments ?? 0"></span>
                                        @else
                                        <a href="{{ route('login') }}">
                                            <i class="fa-solid fa-thumbs-up"></i>&nbsp;
                                            <span x-text="postSummary?.likes ?? 0"></span>&nbsp;&nbsp;
                                        </a>
                                        <a href="{{ route('login') }}">
                                            <i class="fa-solid fa-thumbs-down"></i>&nbsp;
                                            <span x-text="postSummary?.dislikes ?? 0"></span>&nbsp;&nbsp;
                                        </a>
                                        <a href="{{ route('login') }}">
                                            <i
                                                :class="'fa-solid fa-comments ' + (postSummary?.comments ? 'text-indigo-600' : '')"></i>&nbsp;
                                            <span x-text="postSummary?.comments ?? 0"></span>
                                        </a>
                                        @endauth
                                    </div>
                                </div>
                                {{-- End Likes, Dislikes and Comments Post --}}
                                <hr>
                            </div>
                            {{-- End Main Content --}}

                            {{-- Comments --}}
                            <div id="comments">
                                <h6 class="text-xl mb-3 font-semibold text-pink-600" x-text="'Comments'"></h6>
                                <template x-for="comment in comments" :key="comment.id">
                                    <div class="border-dotted border-2 border-indigo-600 mb-2 pl-8">
                                        <div class="flex mt-4 mb-4">
                                            <h6 class="text-s mb-1 font-semibold" x-text="comment.user_name"></h6>
                                            <span class="font-bold text-indigo-600"
                                                x-show="post.user_id == comment.user_id">
                                                &nbsp;({{ __('Author') }})
                                            </span>
                                            <i x-show="comment.is_edited">
                                                &nbsp;({{ __('Edited') }})
                                            </i>
                                        </div>
                                        <p class="text-lg font-light leading-relaxed text-slate-600"
                                            x-text="comment.comment"></p>

                                        {{-- Likes and Dislikes Comments --}}
                                        <div x-data="{
                                            // Helpers
                                            commentId: comment.id,
                    
                                            // Functions
                                            async initialize() {
                                                this.getCommentSummary(this.commentId);
                                                if (!this.userId) {
                                                    return;
                                                }
                                                this.getCommentLike(this.commentId);
                                            },
                                        }" x-init="initialize" x-cloak>
                                            @auth
                                            <i x-on:click="commentDisliked[commentId] ? '#' : (commentLiked[commentId] ? commentLikeDislike(commentId, null) : commentLikeDislike(commentId, 1))"
                                                :class="'cursor-pointer fa-solid fa-thumbs-up ' + (commentLiked[commentId] ? 'text-indigo-600' : '')"></i>&nbsp;
                                            <span x-text="commentSummary[commentId]?.likes ?? 0"></span>&nbsp;&nbsp;
                                            <i x-on:click="commentLiked[commentId] ? '#' : (commentDisliked[commentId] ? commentLikeDislike(commentId, null) : commentLikeDislike(commentId, 0))"
                                                :class="'cursor-pointer fa-solid fa-thumbs-down ' + (commentDisliked[commentId] ? 'text-red-500' : '')"></i>&nbsp;
                                            <span x-text="commentSummary[commentId]?.dislikes ?? 0"></span>&nbsp;&nbsp;
                                            @else
                                            <a href="{{ route('login') }}">
                                                <i class="fa-solid fa-thumbs-up"></i>&nbsp;
                                                <span x-text="commentSummary[commentId]?.likes ?? 0"></span>&nbsp;&nbsp;
                                            </a>
                                            <a href="{{ route('login') }}">
                                                <i class="fa-solid fa-thumbs-down"></i>&nbsp;
                                                <span
                                                    x-text="commentSummary[commentId]?.dislikes ?? 0"></span>&nbsp;&nbsp;
                                            </a>
                                            @endauth
                                        </div>
                                        {{-- End Likes and Dislikes Comments --}}

                                        {{-- Reply Comments --}}
                                        <span x-on:click="updateCommentReplyStatus(comment.id)"
                                            class="cursor-pointer text-sm text-indigo-600 mb-4">
                                            {{ __('Reply') }}
                                        </span>
                                        {{-- End Reply Comments --}}

                                        {{-- Reply Comments --}}
                                        <template x-for="child in comment.childrens">
                                            <div class="mx-4">
                                                <span class="text-indigo-600">|</span>
                                                <div>
                                                    <h6 class="text-s mb-1 font-semibold" x-text="child.user_name">
                                                    </h6>
                                                    <span class="font-bold text-indigo-600"
                                                        x-show="post.user_id == child.user_id">
                                                        &nbsp;({{ __('Author') }})
                                                    </span>
                                                    <i x-show="child.is_edited">
                                                        &nbsp;({{ __('Edited') }})
                                                    </i>
                                                </div>
                                                <p class="text-lg font-light leading-relaxed mt-4 mb-4 text-slate-600"
                                                    x-text="child.comment"></p>

                                                {{-- Likes and Dislikes Reply Comments --}}
                                                <div x-data="{                                        
                                                    // Helpers
                                                    commentId: child.id,
                                        
                                                    // Functions
                                                    async initialize() {
                                                        this.getCommentSummary(this.commentId);
                                                        if (!this.userId) {
                                                            return;
                                                        }
                                                        this.getCommentLike(this.commentId);
                                                    },
                                                }" x-init="initialize" x-cloak>
                                                    @auth
                                                    <i x-on:click="commentDisliked[commentId] ? '#' : (commentLiked[commentId] ? commentLikeDislike(commentId, null) : commentLikeDislike(commentId, 1))"
                                                        :class="'cursor-pointer fa-solid fa-thumbs-up ' + (commentLiked[commentId] ? 'text-indigo-600' : '')"></i>&nbsp;
                                                    <span
                                                        x-text="commentSummary[commentId]?.likes ?? 0"></span>&nbsp;&nbsp;
                                                    <i x-on:click="commentLiked[commentId] ? '#' : (commentDisliked[commentId] ? commentLikeDislike(commentId, null) : commentLikeDislike(commentId, 0))"
                                                        :class="'cursor-pointer fa-solid fa-thumbs-down ' + (commentDisliked[commentId] ? 'text-red-500' : '')"></i>&nbsp;
                                                    <span
                                                        x-text="commentSummary[commentId]?.dislikes ?? 0"></span>&nbsp;&nbsp;
                                                    @else
                                                    <a href="{{ route('login') }}">
                                                        <i class="fa-solid fa-thumbs-up"></i>&nbsp;
                                                        <span
                                                            x-text="commentSummary[commentId]?.likes ?? 0"></span>&nbsp;&nbsp;
                                                    </a>
                                                    <a href="{{ route('login') }}">
                                                        <i class="fa-solid fa-thumbs-down"></i>&nbsp;
                                                        <span
                                                            x-text="commentSummary[commentId]?.dislikes ?? 0"></span>&nbsp;&nbsp;
                                                    </a>
                                                    @endauth
                                                </div>
                                                {{-- End Likes and Dislikes Reply Comments --}}
                                            </div>
                                        </template>
                                        {{-- End Reply Comments --}}

                                        {{-- Add Reply Comment --}}
                                        <template x-if="commentReplyStatus[comment.id]">
                                            <div :key="'comment-reply-' + comment.id" class="mt-4">
                                                <hr>
                                                <label>{{ __('Reply comment') }}</label>
                                                <textarea type="text"
                                                    class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                                    placeholder="{{ __('Your comments') }}"
                                                    x-model="commentReplyInput[comment.id]"></textarea>
                                                <button
                                                    class="flex bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                                    type="button"
                                                    x-on:click="userId ? addReplyComment(comment.id) : (window.location.href = '/login')">
                                                    <i class="fa fa-save"></i>&nbsp;&nbsp;{{ __('Reply') }}
                                                </button>
                                            </div>
                                        </template>
                                        {{-- End Add Reply Comment --}}
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
                                {{-- End Load More --}}

                                {{-- Add Post Comment --}}
                                <div :key="'comment-' + post.id" class="mt-4">
                                    <hr>
                                    <label>{{ __('Add comment') }}</label>
                                    <textarea type="text"
                                        class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        placeholder="{{ __('Your comments') }}" x-model="commentInput"></textarea>
                                    <button
                                        class="flex bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                        type="button"
                                        x-on:click="'{{ auth()->user() }}' ? addComment() : (window.location.href = '/login')">
                                        <i class="fa fa-save"></i>&nbsp;&nbsp;{{ __('Add') }}
                                    </button>
                                </div>
                                {{-- End Add Post Comment --}}

                            </div>
                            {{-- End Comments --}}
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>
</x-base-layout>