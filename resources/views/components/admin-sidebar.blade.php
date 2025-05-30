<aside id="sidebar" class="w-64 bg-gray-800 text-white fixed h-full z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
    <div class="flex items-center justify-between p-4 ">
        <div class="flex items-center">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="h-16 w-auto">
        </div>
        <button id="close-sidebar" class="md:hidden text-gray-400 hover:text-white">
            <i class='bx bx-x text-2xl'></i>
        </button>
    </div>

    <nav class="p-4 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center p-3 rounded-lg transition-all duration-200 
                  hover:bg-gray-700 @if(request()->routeIs('admin.dashboard')) bg-gray-700 @endif">
            <i class='bx bx-home text-xl mr-3'></i>
            <span>Dashboard</span>
        </a>
        
        <!-- Albums -->
        <a href="{{ route('admin.albums') }}" 
           class="flex items-center p-3 rounded-lg transition-all duration-200 
                  hover:bg-gray-700 @if(request()->routeIs('admin.albums.*')) bg-gray-700 @endif">
            <i class='bx bx-album text-xl mr-3'></i>
            <span>Albums</span>
        </a>
        
        <!-- Orders -->
        <a href="{{ route('admin.orders') }}" 
           class="flex items-center p-3 rounded-lg transition-all duration-200 
                  hover:bg-gray-700 @if(request()->routeIs('admin.orders.*')) bg-gray-700 @endif">
            <i class='bx bx-receipt text-xl mr-3'></i>
            <span>Orders</span>
        </a>
        
        <!-- Users -->
        <a href="{{ route('admin.users') }}" 
           class="flex items-center p-3 rounded-lg transition-all duration-200 
                  hover:bg-gray-700 @if(request()->routeIs('admin.users.*')) bg-gray-700 @endif">
            <i class='bx bx-user text-xl mr-3'></i>
            <span>Users</span>
        </a>
        
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="w-full" id="logout-form">
            @csrf
            <button type="button" onclick="confirmLogout()" class="flex items-center w-full p-3 rounded-lg transition-all duration-200 hover:bg-gray-700 text-left">
                <i class='bx bx-log-out text-xl mr-3'></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>

<!-- Mobile menu button -->
<button id="mobile-menu-button" class="md:hidden fixed top-4 right-4 z-50 text-white bg-gray-800 p-2 rounded-lg">
    <i class='bx bx-menu text-3xl'></i>
</button>

<script>
    function confirmLogout() {
        if (confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const menuButton = document.getElementById('mobile-menu-button');
        const closeButton = document.getElementById('close-sidebar');
        const menuIcon = menuButton.querySelector('i');

        // Toggle sidebar
        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            menuIcon.classList.toggle('bx-menu');
            menuIcon.classList.toggle('bx-x');
        });

        // Close sidebar
        closeButton.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            menuIcon.classList.add('bx-menu');
            menuIcon.classList.remove('bx-x');
        });

        // Close when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                event.target !== menuButton && 
                !menuButton.contains(event.target)) {
                sidebar.classList.add('-translate-x-full');
                menuIcon.classList.add('bx-menu');
                menuIcon.classList.remove('bx-x');
            }
        });
    });
</script>