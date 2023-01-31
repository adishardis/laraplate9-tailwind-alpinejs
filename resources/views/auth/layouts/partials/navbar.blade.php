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