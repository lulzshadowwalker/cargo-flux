<div class="navbar backdrop-blur-sm fixed top-0 z-20">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul
                tabindex="0"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <li><a>{{ __('website/header.item1') }}</a></li>
                <li>
                    <a>{{ __('website/header.parent') }}</a>
                    <ul class="p-2">
                        <li><a>{{ __('website/header.submenu1') }}</a></li>
                        <li><a>{{ __('website/header.submenu2') }}</a></li>
                    </ul>
                </li>
                <li><a>{{ __('website/header.item3') }}</a></li>
            </ul>
        </div>
        <a class="btn btn-ghost text-xl">{{ config('app.name') }}</a>
    </div>

    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li>
                <details>
                    <summary>{{ __('website/header.download') }}</summary>
                    <ul class="p-2">
                        <li><a class="text-nowrap"><i class="fa-brands fa-app-store"></i> {{ __('website/header.play-store') }}</a></li>
                        <li><a><i class="fa-brands fa-google-play"></i> {{ __('website/header.app-store') }}</a></li>
                    </ul>
                </details>
            </li>
            <li><livewire:website.order-tracking-button /></li>
            <li><a>{{ __('website/header.contact-us') }}</a></li>
        </ul>
    </div>

    <div class="navbar-end">
        <a class="btn btn-primary">{{ __('website/header.become-partner') }}</a>
    </div>
</div>
