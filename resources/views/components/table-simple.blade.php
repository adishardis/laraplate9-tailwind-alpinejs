{{-- src : https://devdojo.com/mithicher/building-a-laravel-blade-table-component-with-alpinejs --}}

@props([
'id' => '',
'columns' => [],
'urlData' => null,
'actionLabel' => 'Action',
'linkLabel' => '#',
'isStriped' => false,
'isPagination' => true,
'emptyRecordText' => 'No records found',
])

<div id="{{ $id }}" x-data="{
        columns: {{ collect($columns) }},
        urlData: '{{ $urlData }}',

        isStriped: Boolean({{ $isStriped }}),
        isPagination: Boolean({{ $isPagination }}),

        datas: [],
        paginationInfo: [],
        firstPage: null,
        prevPage: null,
        nextPage: null,
        lastPage: null,

        // Functions
        async getTableData(url) {
            fetch(url)
            .then((response) => response.json())
            .then((response) => {
                console.log(response);
                this.datas = response.data;
                if (this.isPagination) {
                    links = response.links ?? null;
                    if (links) {
                        this.firstPage = links.first;
                        this.prevPage = links.prev;
                        this.nextPage = links.next;
                        this.lastPage = links.last;
                    }
                    this.paginationInfo = response.meta ?? [];
                }
            });
        },
        async deleteAction(dataId, url) {
            var result = await $store.action.deleteConfirm(dataId, url);
            if (!result) {
                return;
            }
            this.datas = this.datas.filter((obj) => {
                return obj.id !== dataId;
            });
        },
        async initTable() {
            this.getTableData(this.urlData);
        },
        async changePage(url) {
            this.getTableData(url);
        },
    }" x-init="initTable" x-cloak>

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow overflow-y-auto relative mb-4">
        <div class="flex flex-wrap">
            <div class="w-full px-4">
                {{ $slot }}
            </div>
        </div>
    </div>
    {{-- End Header --}}

    <div class="mb-5 overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative">
        <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
            <thead>
                <tr class="text-left">
                    {{-- Link Label--}}
                    @isset($linkColumn)
                    <th
                        class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs truncate">
                        {{ $linkLabel }}
                    </th>
                    @endisset
                    {{-- End Link Label--}}

                    {{-- Data Labels --}}
                    <template x-for="column in columns">
                        <th :class="`${column.columnClasses}`"
                            class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs truncate"
                            x-text="column.name"></th>
                    </template>
                    {{-- End Data Labels --}}

                    {{-- Action Label --}}
                    @isset($actionColumn)
                    <th
                        class="bg-gray-50 sticky top-0 border-b border-gray-100 px-6 py-3 text-gray-500 font-bold tracking-wider uppercase text-xs truncate">
                        {{ $actionLabel }}
                    </th>
                    @endisset
                    {{-- End Action Label --}}

                </tr>
            </thead>
            <tbody>

                {{-- Empty Records --}}
                <template x-if="datas.length === 0">
                    <tr>
                        <td colspan="100%" class="text-center py-10 px-4 text-sm">
                            {{ __($emptyRecordText) }}
                        </td>
                    </tr>
                </template>
                {{-- End Empty Records --}}

                <template x-for="(row, rowIndex) in datas" :key="'row-' +rowIndex">
                    <tr :class="{'bg-gray-200': isStriped === true && ((rowIndex+1) % 2 === 0) }">

                        {{-- Link Column --}}
                        @isset($linkColumn)
                        <td class="text-gray-600 px-6 py-3 border-t border-gray-100 whitespace-nowrap">
                            {{ $linkColumn }}
                        </td>
                        @endisset
                        {{-- End Link Column --}}

                        {{-- Data Columns --}}
                        <template x-for="(column, columnIndex) in columns" :key="'column-' + columnIndex">
                            <td :class="`${column.rowClasses}`"
                                class="text-gray-600 px-6 py-3 border-t border-gray-100 whitespace-nowrap">
                                <template x-if="!column.isHtml">
                                    <div x-text="`${row[column.field]}`" class="truncate"></div>
                                </template>
                                <template x-if="column.isHtml">
                                    <div x-html="`${row[column.field]}`" class="truncate"></div>
                                </template>
                            </td>
                        </template>
                        {{-- End Data Columns --}}

                        {{-- Action Column --}}
                        @isset($actionColumn)
                        <td class="text-gray-600 px-6 py-3 border-t border-gray-100 whitespace-nowrap">
                            <span id="row.id"></span>
                            {{ $actionColumn }}
                        </td>
                        @endisset
                        {{-- End Action Column --}}
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    {{-- Pagination --}}
    <div x-show="isPagination">
        <nav aria-label="Page navigation" class="flex-1 flex justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    {{ __('Showing') }}
                    <span class="font-medium" x-text="paginationInfo['from'] ?? ''"></span>
                    {{ __('to') }}
                    <span class="font-medium" x-text="paginationInfo['to'] ?? ''"></span>
                    {{ __('of') }}
                    <span class="font-medium" x-text="paginationInfo['total'] ?? ''"></span>
                    {{ __('results') }}
                </p>
            </div>
            <ul class="inline-flex -space-x-px">
                <template x-for="(link, index) in paginationInfo.links">
                    <li>
                        <button x-on:click="changePage(link.url)" :disabled="link.active || !link.url"
                            :class="(link.url && link.active) ? 'text-pink-600' : ''"
                            class="py-2 px-3 ml-0 leading-tight bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                            x-html="link.label">
                        </button>
                    </li>
                </template>
            </ul>
        </nav>
    </div>
    {{-- End Pagination Records --}}
</div>