<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Movato — Belajar Matematika SD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-linear-to-b from-indigo-50 via-white to-yellow-50 text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col">
        {{-- Navbar --}}
        <!-- NAV (replace existing nav + mobileMenu + inline script) -->
        <nav class="relative z-30 bg-transparent">
            <div class="max-w-6xl mx-auto px-6">
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-white shadow-md flex items-center justify-center">
                            <span class="font-extrabold text-indigo-600">M</span>
                        </div>
                        <div>
                            <span class="font-bold text-lg">Movato</span>
                            <div class="text-xs text-slate-500">AI-powered Math for SD</div>
                        </div>
                    </div>

                    <!-- Desktop Links -->
                    <div class="hidden md:flex items-center gap-4">
                        <a href="#features" class="text-sm hover:underline">Fitur</a>
                        <a href="#how" class="text-sm hover:underline">Cara Kerja</a>
                        <a href="#testimonials" class="text-sm hover:underline">Testimoni</a>
                        <a href="/admin/login"
                            class="rounded-full px-4 py-2 bg-indigo-600 text-white text-sm shadow hover:bg-indigo-700">Login</a>
                    </div>

                    <!-- Hamburger (mobile) -->
                    <div class="md:hidden">
                        <button id="menuBtn" aria-controls="mobileMenu" aria-expanded="false"
                            class="p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <!-- burger icon -->
                            <svg id="iconBurger" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- close icon (hidden by default) -->
                            <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu: positioned absolute so it doesn't change page layout -->
            <div id="mobileMenu"
                class="md:hidden absolute left-0 right-0 top-full bg-white shadow-lg overflow-hidden z-20"
                style="max-height: 0; transition: max-height 320ms ease;">
                <div class="px-6 py-4 flex flex-col gap-3">
                    <a href="#features" class="text-sm block text-center px-4" data-mobile-link>Fitur</a>
                    <a href="#how" class="text-sm block text-center px-4" data-mobile-link>Cara Kerja</a>
                    <a href="#testimonials" class="text-sm block text-center px-4" data-mobile-link>Testimoni</a>
                    <a href="/admin/login"
                        class="mt-2 block rounded-full px-4 py-2 bg-indigo-600 text-white text-sm shadow w-full text-center"
                        data-mobile-link>
                        Login
                    </a>
                </div>
            </div>
        </nav>

        {{-- Content --}}
        <main class="flex-1">
            <div class="max-w-6xl mx-auto px-6">
                @yield('content')
            </div>
        </main>


        {{-- Footer --}}
        <footer class="py-8 mt-12 border-t bg-transparent">
            <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-slate-600">© {{ date('Y') }} Movato — Belajar matematika interaktif untuk SD
                </div>
                <div class="text-sm text-slate-600">Made with ❤️ by the Movato team</div>
            </div>
        </footer>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('menuBtn');
            const menu = document.getElementById('mobileMenu');
            const iconBurger = document.getElementById('iconBurger');
            const iconClose = document.getElementById('iconClose');

            // helper: open/close with max-height animation
            function openMenu() {
                menu.style.display = 'block';
                const full = menu.scrollHeight + 'px';
                // small delay to allow style to apply so transition works
                requestAnimationFrame(() => {
                    menu.style.maxHeight = full;
                });
                btn.setAttribute('aria-expanded', 'true');
                iconBurger.classList.add('hidden');
                iconClose.classList.remove('hidden');
            }

            function closeMenu() {
                menu.style.maxHeight = '0';
                btn.setAttribute('aria-expanded', 'false');
                iconBurger.classList.remove('hidden');
                iconClose.classList.add('hidden');
                // hide after transition to remove from TAB-order flow
                setTimeout(() => {
                    if (menu.style.maxHeight === '0px' || menu.style.maxHeight === '0') {
                        menu.style.display = 'none';
                    }
                }, 320);
            }

            btn.addEventListener('click', (e) => {
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                if (expanded) closeMenu(); else openMenu();
            });

            // close when clicking a link
            document.querySelectorAll('[data-mobile-link]').forEach(a => {
                a.addEventListener('click', () => closeMenu());
            });

            // close when clicking outside
            document.addEventListener('click', (ev) => {
                const path = ev.composedPath ? ev.composedPath() : (ev.path || []);
                if (!path.includes(menu) && !path.includes(btn)) {
                    if (btn.getAttribute('aria-expanded') === 'true') closeMenu();
                }
            });

            // ensure menu hidden on resize to desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    menu.style.display = 'none';
                    menu.style.maxHeight = '0';
                    btn.setAttribute('aria-expanded', 'false');
                    iconBurger.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>