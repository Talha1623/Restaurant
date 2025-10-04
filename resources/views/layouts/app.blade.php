<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title> @yield('title', 'Dashboard')</title>

<!-- Preload critical resources -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">

<!-- Critical CSS inline for faster loading -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
body { 
    font-family: 'Poppins', sans-serif; 
    margin: 0;
    padding: 0;
    overflow-x: hidden;
}


</style>

<!-- Load Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body { font-family: 'Poppins', sans-serif; }

/* Sidebar - Fixed positioning */
.sidebar {
    background: #083344;
    height: 100vh;
    width: 14rem; /* Fixed width */
    transition: all 0.3s ease;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    overflow-y: auto;
    overflow-x: hidden;
}
.sidebar .logo {
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 600;
    padding: 1rem; color: white;
}
.sidebar .nav-item {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.65rem 1rem; margin: 0.3rem 0.75rem;
    border-radius: 8px; color: rgba(255, 255, 255, 0.8); 
    transition: all 0.3s ease;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    font-size: 0.85rem;
}
.sidebar .nav-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s ease;
}
.sidebar .nav-item:hover::before {
    left: 100%;
}
.sidebar .nav-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white; 
    transform: translateX(6px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}
.sidebar .nav-item i {
    font-size: 1rem;
    width: 1.2rem;
    text-align: center;
    transition: all 0.3s ease;
}
.sidebar .nav-item:hover i {
    transform: scale(1.1);
    color: #00d03c;
}
/* Active button using #00d03c */
.sidebar .nav-item.active {
    background: #00d03c;
    color: white; 
    position: relative;
    box-shadow: 0 4px 20px rgba(0, 208, 60, 0.3);
    transform: translateX(6px);
}
.sidebar .nav-item.active::after {
    content: '';
    position: absolute;
    right: -1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 0.5rem;
    height: 0.5rem;
    background: #00d03c;
    border-radius: 50%;
    box-shadow: 0 0 10px rgba(0, 208, 60, 0.5);
}
.sidebar .nav-item.active i {
    color: white;
    transform: scale(1.1);
}

/* Restaurant Dropdown */
.restaurant-dropdown {
    position: relative;
}
.dropdown-trigger {
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.dropdown-trigger:hover {
    transform: translateX(2px);
    background: rgba(255, 255, 255, 0.05);
}
.dropdown-trigger.active {
    background: rgba(0, 208, 60, 0.1);
    animation: pulse 2s infinite;
}
.dropdown-menu-restaurant {
    display: none;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 0 0 8px 8px;
    margin: 0 0.75rem;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    max-height: 0;
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}
.dropdown-menu-restaurant.show {
    display: block;
    max-height: 200px;
    opacity: 1;
    transform: translateY(0) scale(1);
    animation: slideDownBounce 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    margin: 0.125rem 0.5rem;
    border-radius: 6px;
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 500;
    font-size: 0.8rem;
    text-decoration: none;
    background: transparent;
    transform: translateX(-10px);
    opacity: 0;
    animation: slideInFromLeft 0.3s ease forwards;
}
.dropdown-item:nth-child(1) { animation-delay: 0.1s; }
.dropdown-item:nth-child(2) { animation-delay: 0.2s; }
.dropdown-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    transform: translateX(4px) scale(1.02);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}
.dropdown-item i {
    font-size: 0.8rem;
    width: 1rem;
    text-align: center;
}
.dropdown-trigger .fa-chevron-down {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}
.dropdown-trigger.active .fa-chevron-down {
    transform: rotate(180deg) scale(1.1);
    color: #00d03c;
}
.dropdown-trigger:hover .fa-chevron-down {
    transform: scale(1.1);
    color: #00d03c;
}

@keyframes slideDownBounce {
    0% {
        max-height: 0;
        opacity: 0;
        transform: translateY(-20px) scale(0.8);
    }
    50% {
        max-height: 200px;
        opacity: 0.8;
        transform: translateY(5px) scale(1.05);
    }
    100% {
        max-height: 200px;
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes slideInFromLeft {
    0% {
        transform: translateX(-20px);
        opacity: 0;
    }
    100% {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(0, 208, 60, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(0, 208, 60, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(0, 208, 60, 0);
    }
}

.sidebar-footer { 
    margin-top: auto; 
    padding: 1.5rem; 
    text-align: center; 
    font-size: 0.75rem; 
    color: rgba(255, 255, 255, 0.6);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin: 1rem;
    padding-top: 1.5rem;
}

/* Top Navbar */
.top-nav {
    display: flex; justify-content: flex-end; align-items: center; gap: 1rem;
    background: white; padding: 0.5rem 1.5rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    position: sticky; top: 0; z-index: 50;
}
.dropdown { position: relative; }
.dropdown-btn {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.35rem 0.75rem; border-radius: 0.375rem;
    background: none; font-size: 0.85rem; font-weight: 500;
    cursor: pointer; transition: all 0.2s ease;
}
.dropdown-btn:hover { background: #f3f4f6; }
.dropdown-btn i.fas.fa-user-circle { background: none !important; color: #00d03c; }
.dropdown-menu {
    display: none; position: absolute; right: 0; top: 110%;
    background: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-radius: 0.5rem; overflow: hidden; min-width: 150px; z-index: 50;
}
.dropdown-menu a {
    display: block; padding: 0.5rem 1rem; font-size: 0.85rem; color: #374151; transition: all 0.2s ease;
}
.dropdown-menu a:hover { background: #f3f4f6; color: #111827; }
.dropdown:hover .dropdown-menu { display: block; }

/* Main content area - Account for fixed sidebar */
.main-content {
    margin-left: 14rem; /* Match sidebar width */
    min-height: 100vh;
    transition: margin-left 0.3s ease;
}

/* Mobile Toggle */
.menu-toggle { cursor: pointer; display: none; }
@media (max-width: 768px) {
    .sidebar { 
        position: fixed; 
        left: -100%; 
        top: 0; 
        width: 16rem; 
        z-index: 1000; 
    }
    .sidebar.show { 
        left: 0; 
        transition: left 0.3s ease; 
    }
    .menu-toggle { display: block; }
    .main-content { 
        margin-left: 0 !important; 
    }
    .dropdown-menu-restaurant {
        position: static;
        box-shadow: none;
        border: none;
        margin-top: 0;
        background: rgba(0, 0, 0, 0.1);
    }
}
</style>
</head>
<body class="bg-gray-100">


<!-- Sidebar -->
<aside class="flex flex-col sidebar" id="sidebar">
    <!-- Logo -->
    <div class="logo">
        {{ Auth::user() ? strtoupper(substr(Auth::user()->name ?? 'User', 0, 10)) : 'GUEST' }}
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col flex-1 mt-4">
        <a href="/dashboard" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-bar w-5 text-center"></i> Dashboard
        </a>
        <!-- Restaurants Dropdown -->
        <div class="restaurant-dropdown">
            <div class="nav-item {{ request()->routeIs('restaurants.*') ? 'active' : '' }} dropdown-trigger" onclick="toggleRestaurantDropdown()">
                <i class="fas fa-utensils w-5 text-center"></i> Restaurants
                <i class="fas fa-chevron-down ml-auto text-xs transition-transform" id="restaurantChevron"></i>
            </div>
            <div class="dropdown-menu-restaurant" id="restaurantDropdown">
                <a href="{{ route('restaurants.index') }}" class="dropdown-item">
                    <i class="fas fa-list w-4 text-center"></i> All Restaurants
                </a>
                <a href="{{ route('restaurants.create') }}" class="dropdown-item">
                    <i class="fas fa-plus w-4 text-center"></i> Add New Restaurant
                </a>
            </div>
        </div>
        <a href="{{ route('riders.index') }}" class="nav-item {{ request()->routeIs('riders.*') ? 'active' : '' }}">
            <i class="fas fa-motorcycle w-5 text-center"></i> Riders
        </a>
        <a href="{{ route('customers.index') }}" 
   class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
    <i class="fas fa-users w-5 text-center"></i> Customers 
</a>

        <a href="#" class="nav-item">
            <i class="fas fa-chart-line w-5 text-center"></i> Performance
        </a>
        <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <i class="fas fa-cog w-5 text-center"></i> Settings
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-money-bill-wave w-5 text-center"></i> Financials
        </a>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        © 2023 v2.0
    </div>
</aside>

<!-- Main Content -->
<div class="main-content">
    <!-- Top Navbar -->
    <div class="top-nav">
        <div class="menu-toggle md:hidden" onclick="toggleSidebar()">
            <i class="fas fa-bars text-gray-700 text-xl"></i>
        </div>
        <div class="dropdown ml-auto">
            <div class="dropdown-btn">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <div class="dropdown-menu">
                <a href="#">Profile</a>
                <a href="#" onclick="logout()" class="cursor-pointer">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <main class="p-6 overflow-y-auto">
        @yield('content')
    </main>
</div>

<script>
// Page initialization
document.addEventListener('DOMContentLoaded', function() {
    // Page is ready - no loading overlay needed
});

// Sidebar functions
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

function toggleRestaurantDropdown() {
    const dropdown = document.getElementById('restaurantDropdown');
    const trigger = document.querySelector('.dropdown-trigger');
    const chevron = document.getElementById('restaurantChevron');
    
    dropdown.classList.toggle('show');
    trigger.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('restaurantDropdown');
    const trigger = event.target.closest('.dropdown-trigger');
    
    if (!trigger && dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
    }
});

// Logout function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        document.getElementById('logout-form').submit();
    }
}

// Optimize page performance
window.addEventListener('load', function() {
    // Preload critical resources
    const criticalLinks = document.querySelectorAll('a[href^="/"]');
    criticalLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            const href = this.getAttribute('href');
            if (href && !href.startsWith('#')) {
                const linkElement = document.createElement('link');
                linkElement.rel = 'prefetch';
                linkElement.href = href;
                document.head.appendChild(linkElement);
            }
        });
    });
});
</script>

@stack('scripts')
</body>
</html>
