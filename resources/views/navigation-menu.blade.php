<div class="sidebar fixed  inset-y-0 left-0 z-20 w-48 -translate-x-full transform bg-white transition duration-200 ease-in-out md:relative md:w-48 md:translate-x-0">
    <div class="grid justify-items-center py-2">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <img class="my-1 h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }} {{ Auth::user()->last_name }}" />
        @endif
        <div class="text-sm text-gray-600">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
        @if (!Auth::user()->isUserProfile())
            <div class="text-xs text-gray-400" title="{{ __('Profile') }}">{{ Auth::user()->profile->name }}</div>
        @endif
    </div>
    <livewire:users.available-balance />
    <!-- nav -->
    <nav class="sidebar-item font-sans text-xs">
        <!-- Navigation Links -->
        <a href="{{ route('dashboard') }}" class="sidebar-item @if (request()->routeIs('dashboard')) sidebar-item-active @endif mb-1 mt-6 block px-4 py-2.5 transition duration-200">
            {{ __('Dashboard') }}
        </a>
        @if (auth()->user()->isSuperAdminProfile() ||
                auth()->user()->isOwnerProfile())
        @endif
        <a href="{{ route('inspections.index') }}" class="sidebar-item @if (request()->routeIs('inspections.index') || request()->routeIs('inspections.form') || request()->routeIs('map')) sidebar-item-active bg-blue-100 @endif mb-1 block px-4 py-2.5 transition duration-200">
            {{ __('Inspections') }}
        </a>
        @if (auth()->user()->checkBillingAccess())
            <a href="{{ route('billing.index') }}" class="sidebar-item @if (request()->routeIs('billing.index')) sidebar-item-active bg-blue-100 @endif mb-1 block px-4 py-2.5 transition duration-200">
                {{ __('Billing') }}
            </a>
        @endif
        @if (auth()->user()->isSuperAdminProfile() ||
                auth()->user()->isOwnerProfile())
            <a href="{{ route('users.index') }}" class="sidebar-item @if (request()->routeIs('users.index') || request()->routeIs('users.form')) sidebar-item-active bg-blue-100 @endif mb-1 block px-4 py-2.5 transition duration-200">
                {{ __('Access Management') }}
            </a>
        @endif
        @if (auth()->user()->isSuperAdminProfile() ||
                auth()->user()->isOwnerProfile())
            <a href="{{ route('finding-types.index') }}" class="sidebar-item @if (request()->routeIs('finding-types.index')) sidebar-item-active bg-blue-100 @endif mb-1 block px-4 py-2.5 transition duration-200">
                {{ __('Layer Data Types') }}
            </a>
        @endif
        <a href="{{ route('profile.show') }}" class="sidebar-item @if (request()->routeIs('profile.show')) sidebar-item-active bg-blue-100 @endif mb-1 block px-4 py-2.5 transition duration-200">
            {{ __('Profile') }}
        </a>
        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="sidebar-item mb-1 block px-4 py-2.5 transition duration-200" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
            </a>
        </form>
    </nav>
</div>
