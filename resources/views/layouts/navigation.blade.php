<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    @php
        use App\Models\Cart;
        use Illuminate\Support\Facades\Auth;

        $cartCount = 0;

        try {
            $cartQuery = Cart::where('status', 'draft');

            if (Auth::check()) {
                $cartQuery->where('user_id', Auth::id());
            } else {
                $cartQuery->where('token', request()->cookie('cart_token'));
            }

            $cartCount = optional($cartQuery->first())->items()->sum('quantity') ?? 0;
        } catch (\Throwable $e) {
            $cartCount = 0;
        }
    @endphp

    <!-- Barre principale -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- GAUCHE : Logo + Dashboard + Browse puzzles -->
            <div class="flex items-stretch gap-8">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>

                <!-- Liens principaux -->
                <div class="hidden sm:flex items-stretch gap-2">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-2 text-sm font-medium
                              border-b-2 transition
                              {{ request()->routeIs('dashboard') ? 'border-amber-800 text-gray-900' : 'border-transparent text-gray-700 hover:text-gray-900 hover:border-amber-800' }}">
                        {{ __('Dashboard') }}
                    </a>

                    {{-- Browse puzzles --}}
                    <a href="{{ route('puzzles.index') }}"
                       class="inline-flex items-center px-2 text-sm font-medium
                              border-b-2 transition
                              {{ request()->routeIs('puzzles.index') ? 'border-amber-800 text-gray-900' : 'border-transparent text-gray-700 hover:text-gray-900 hover:border-amber-800' }}">
                        {{ __('Browse puzzles') }}
                    </a>

                    {{-- Admin-only: quick actions --}}
                        @if(auth()->user()?->is_admin)
                        <a href="{{ route('puzzles.create') }}"
                            class="inline-flex items-center px-2 text-sm font-medium
                                    border-b-2 transition
                                    {{ request()->routeIs('puzzles.create') ? 'border-amber-800 text-gray-900' : 'border-transparent text-gray-700 hover:text-gray-900 hover:border-amber-800' }}">
                            {{ __('Create puzzle') }}
                        </a>

                        <a href="{{ route('categories.create') }}"
                            class="inline-flex items-center px-2 text-sm font-medium
                                    border-b-2 transition
                                    {{ request()->routeIs('categories.create') ? 'border-amber-800 text-gray-900' : 'border-transparent text-gray-700 hover:text-gray-900 hover:border-amber-800' }}">
                            {{ __('Create categories') }}
                        </a>
                        @endif

                </div>
            </div>

            @php $currentLocale = app()->getLocale(); @endphp

            <!-- DROITE : Panier + Langue + Compte -->
            <div class="flex items-center gap-4">
                <!-- Panier (desktop) -->
                <a href="{{ route('cart.show') }}"
                   class="relative hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-xl transition
                          border-b-2
                          {{ request()->routeIs('cart.show') ? 'border-amber-800 text-gray-900' : 'border-transparent text-gray-700 hover:text-gray-900 hover:border-amber-800' }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 3h1.6a2 2 0 0 1 1.94 1.52l.22.98M7 13h10l2-6H6.76M7 13l-1.2 4.8A2 2 0 0 0 7.74 20H18M7 13l-.74-3.5M10 21.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm8 0a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"/>
                    </svg>
                    <span>{{ __('Cart') }}</span>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 grid place-content-center
                                     text-xs font-semibold rounded-full bg-gray-900 text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- Language switcher --}}
                <div class="hidden sm:flex sm:items-center">
                    <div x-data="{open:false}" class="relative">
                        <button @click="open = !open"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl ring-1 ring-gray-300 transition
                                       border-b-2 border-transparent hover:border-amber-800 hover:text-gray-900">
                            <span class="text-lg">
                                {{ $currentLocale === 'en' ? '🇬🇧' : '🇫🇷' }}
                            </span>
                            <span class="text-sm font-medium">
                                {{ strtoupper($currentLocale) }}
                            </span>
                            <svg class="w-4 h-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.outside="open=false" x-cloak
                             class="absolute right-0 mt-2 w-40 rounded-xl border border-gray-200 bg-white shadow-lg overflow-hidden">
                            <a href="{{ route('locale.switch', 'fr') }}"
                               class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50">
                                <span class="text-lg">🇫🇷</span>
                                <span>Français</span>
                            </a>
                            <a href="{{ route('locale.switch', 'en') }}"
                               class="flex items-center gap-2 px-3 py-2 hover:bg-gray-50">
                                <span class="text-lg">🇬🇧</span>
                                <span>English</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Compte -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 transition">
                                <div>
                                    @if(Auth::check())
                                        {{ Auth::user()->name }}
                                    @else
                                        {{ __('Guest') }}
                                    @endif
                                </div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(Auth::check())
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            @else
                                <x-dropdown-link :href="route('login')">
                                    {{ __('Log in') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('register')">
                                    {{ __('Register') }}
                                </x-dropdown-link>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Burger -->
                <div class="sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('puzzles.index')" :active="request()->routeIs('puzzles.index')">
                {{ __('Browse puzzles') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('cart.show')" :active="request()->routeIs('cart.show')">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 3h1.6a2 2 0 0 1 1.94 1.52l.22.98M7 13h10l2-6H6.76M7 13l-1.2 4.8A2 2 0 0 0 7.74 20H18M7 13l-.74-3.5M10 21.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm8 0a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"/>
                    </svg>
                    {{ __('Cart') }}
                    @if($cartCount > 0)
                        <span class="ms-2 inline-flex items-center justify-center min-w-5 h-5 px-1
                                     text-xs font-semibold rounded-full bg-gray-900 text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </span>
            </x-responsive-nav-link>

            {{-- Langues (mobile) --}}
            <div class="px-4 flex items-center gap-3">
                <a href="{{ route('locale.switch','fr') }}" class="px-3 py-2 rounded-lg ring-1 ring-gray-200">🇫🇷 Français</a>
                <a href="{{ route('locale.switch','en') }}" class="px-3 py-2 rounded-lg ring-1 ring-gray-200">🇬🇧 English</a>
            </div>
        </div>

        <!-- Profil (mobile) -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                @if(Auth::check())
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                @else
                    <div class="font-medium text-base text-gray-800">{{ __('Guest') }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ __('Not signed in') }}</div>
                @endif
            </div>

            <div class="mt-3 space-y-1">
                @if(Auth::check())
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                @else
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                @endif
            </div>
        </div>
    </div>
</nav>
