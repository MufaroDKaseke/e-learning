<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<aside class="w-64 bg-navy min-h-[calc(100vh-64px)] text-white">
  <div class="p-4">
    <div class="space-y-6">
      <!-- Learning Section -->
      <div class="space-y-2">
        <h3 class="text-sm uppercase text-gray-400 font-semibold">Learning</h3>
        <a href="./index.php" class="flex items-center space-x-2 px-4 py-2 rounded <?= ($current_page === 'index.php') ? 'bg-white/10' : 'hover:bg-white/10' ?>">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard Overview</span>
        </a>
        <a href="./my-courses.php" class="flex items-center space-x-2 px-4 py-2 rounded <?= ($current_page === 'my-courses.php') ? 'bg-white/10' : 'hover:bg-white/10' ?>">
          <i class="fas fa-graduation-cap"></i>
          <span>My Courses</span>
        </a>
      </div>

      <!-- Assignments Section -->
      <div class="space-y-2">
        <h3 class="text-sm uppercase text-gray-400 font-semibold">Assignments</h3>
        <a href="./pending-tasks.php" class="flex items-center justify-between px-4 py-2 rounded <?= ($current_page === 'pending-tasks.php') ? 'bg-white/10' : 'hover:bg-white/10' ?>">
          <div class="flex items-center space-x-2">
            <i class="fas fa-clock"></i>
            <span>Due</span>
          </div>
          <span class="bg-red-500 text-xs rounded-full px-2 py-1">4</span>
        </a>
        <a href="./submitted.php" class="flex items-center space-x-2 px-4 py-2 rounded <?= ($current_page === 'submitted.php') ? 'bg-white/10' : 'hover:bg-white/10' ?>">
          <i class="fas fa-check-circle"></i>
          <span>Submitted Work</span>
        </a>
        <a href="./grades.php" class="flex items-center space-x-2 px-4 py-2 rounded <?= ($current_page === 'grades.php') ? 'bg-white/10' : 'hover:bg-white/10' ?>">
          <i class="fas fa-star"></i>
          <span>Grades</span>
        </a>
      </div>

      <!-- Schedule Section -->
      <div class="space-y-2">
        <h3 class="text-sm uppercase text-gray-400 font-semibold">Schedule</h3>
        <a href="./calendar.php" class="flex items-center space-x-2 px-4 py-2 rounded <?= ($current_page === 'calendar.php') ? 'bg-white/10' : 'hover:bg-white/10' ?>">
          <i class="fas fa-calendar-alt"></i>
          <span>Calendar</span>
        </a>
      </div>

      <!-- Resources Section -->
      <div class="space-y-2">
        <h3 class="text-sm uppercase text-gray-400 font-semibold">Resources</h3>
        <a href="./library.php" class="flex items-center space-x-2 px-4 py-2 rounded <?= ($current_page === 'library.php') ? 'bg-white/10' : 'hover:bg-white/10' ?>">
          <i class="fas fa-book"></i>
          <span>Digital Library</span>
        </a>
      </div>
    </div>
  </div>
</aside>