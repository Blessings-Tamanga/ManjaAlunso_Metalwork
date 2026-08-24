<nav class="navbar" id="navbar">
    <div class="container">
        <a href="{{ route('home') }}" class="nav-logo">Manja<span>Alunso</span></a>
        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
            <li><a href="{{ route('projects') }}" class="{{ request()->routeIs('projects') ? 'active' : '' }}">Projects</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
            <li><a href="{{ route('contact') }}" class="nav-cta">Get Started</a></li>
            <li>
                <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch color theme">
                    <i class="ri-moon-line"></i>
                    <i class="ri-sun-line"></i>
                </button>
            </li>
        </ul>
        <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>