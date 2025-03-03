<!-- Navbar -->
<nav class="bg-blue-600 p-4 text-white">
  <div class="container mx-auto flex items-center">
    <!-- Left section: Logo and main links -->
    <div class="flex items-center space-x-6 flex-1">
      <a href="index.html" class="text-lg font-bold">NUST</a>
      <div class="flex items-center space-x-2">
        <a href="courses.html" class="px-4 py-1.5 rounded-full hover:bg-white/20 transition-colors">Lecturer</a>
        <a href="dashboard.html" class="px-4 py-1.5 rounded-full bg-white/20">Student</a>
      </div>
    </div>

    <!-- Right section: Dropdowns -->
    <div class="flex items-center space-x-4">
      <!-- Language Dropdown -->
      <div class="relative">
        <button class="flex items-center px-3 py-1.5 rounded-full hover:bg-white/20 transition-colors">
          <i class="fas fa-globe"></i>
          <span class="ml-2">EN</span>
          <i class="fas fa-chevron-down ml-2 text-xs"></i>
        </button>
        <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
          <a href="#" class="block px-4 py-2 text-blue-600 hover:bg-gray-100">English</a>
          <a href="#" class="block px-4 py-2 text-blue-600 hover:bg-gray-100">Spanish</a>
          <a href="#" class="block px-4 py-2 text-blue-600 hover:bg-gray-100">French</a>
        </div>
      </div>

      <!-- Calendar Dropdown -->
      <div class="relative">
        <button class="flex items-center px-3 py-1.5 rounded-full hover:bg-white/20 transition-colors">
          <i class="fas fa-calendar"></i>
          <i class="fas fa-chevron-down ml-2 text-xs"></i>
        </button>
        <div class="hidden absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg">
          <div class="p-4 text-blue-600">
            <!-- Calendar content -->
            <p class="font-semibold">Upcoming Sessions</p>
            <div class="mt-2 text-sm">
              <p class="py-1">No scheduled sessions</p>
            </div>
          </div>
        </div>
      </div>

      <!-- User Dropdown -->
      <div class="relative">
        <button class="flex items-center px-3 py-1.5 rounded-full hover:bg-white/20 transition-colors">
          <img src="https://ui-avatars.com/api/?name=Mufaro+Kaseke" alt="User" class="w-6 h-6 rounded-full">
          <i class="fas fa-chevron-down ml-2 text-xs"></i>
        </button>
        <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
          <div class="px-4 py-3 text-sm text-blue-600">
            <p class="font-semibold">Mufaro Kaseke</p>
            <p class="text-gray-600">john@example.com</p>
          </div>
          <hr>
          <a href="#" class="block px-4 py-2 text-blue-600 hover:bg-gray-100">Profile</a>
          <a href="#" class="block px-4 py-2 text-blue-600 hover:bg-gray-100">Settings</a>
          <hr>
          <a href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Sign out</a>
        </div>
      </div>
    </div>
  </div>
</nav>