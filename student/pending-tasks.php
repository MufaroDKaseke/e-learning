<?php
require_once '../app/models/Auth.php';
require_once '../app/models/Assignment.php';
require_once '../app/models/Course.php';

$auth = new Auth();
$auth->requireRole('student');

$assignment = new Assignment();
$course = new Course();
$user = $auth->getCurrentUser();
$student_id = $user['user_id'];

// Get enrolled courses for dropdown
$courses_query = "SELECT c.* FROM courses c 
                 JOIN enrollments e ON c.course_id = e.course_id 
                 WHERE e.student_id = $student_id";
$courses = $course->db->query($courses_query);

// Get pending assignments
$course_filter = isset($_GET['course_id']) ? (int)$_GET['course_id'] : null;
$pending_assignments = $assignment->getPendingForStudent($student_id, $course_filter);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - E-Learning</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'navy': '#0A1A2F',
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gray-100">
  <!-- Navbar -->
  <?php
  require_once '../components/navbar_student.php';
  ?>
  <!-- Main Layout -->
  <div class="flex">
    <?php
    require_once '../components/sidebar.php';
    ?>

    <!-- Main Content -->
    <main class="flex-1 p-8">
      <div class="bg-white rounded-lg shadow-md">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
          <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">Pending Assignments</h2>
            <div class="flex items-center space-x-4">
              <form action="" method="get" class="flex items-center">
                <select name="course_id" onchange="this.form.submit()" class="text-sm border rounded-md px-3 py-2 text-gray-600">
                  <option value="">All Courses</option>
                  <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                    <option value="<?php echo $course['course_id']; ?>"
                      <?php echo isset($_GET['course_id']) && $_GET['course_id'] == $course['course_id'] ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($course['title']); ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </form>
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Assignment</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Course</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Due Date</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <?php while ($assignment = mysqli_fetch_assoc($pending_assignments)): 
                $due_date = new DateTime($assignment['due_date']);
                $now = new DateTime();
                $interval = $now->diff($due_date);
                $days_remaining = $interval->days;
                $is_urgent = $days_remaining <= 2;
              ?>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <div>
                    <div class="font-medium text-gray-900">
                      <?php echo htmlspecialchars($assignment['title']); ?>
                    </div>
                    <div class="text-sm text-gray-500">
                      <?php echo htmlspecialchars($assignment['description']); ?>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                  <?php echo htmlspecialchars($assignment['course_title']); ?>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm <?php echo $is_urgent ? 'text-red-600' : 'text-gray-600'; ?>">
                    Due in <?php echo $days_remaining; ?> days
                  </div>
                  <div class="text-xs text-gray-500">
                    <?php echo $due_date->format('M j, Y'); ?>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <form action="submit-assignment.php" method="post" enctype="multipart/form-data" 
                    class="flex items-center space-x-2">
                    <input type="hidden" name="assignment_id" 
                      value="<?php echo $assignment['assignment_id']; ?>">
                    <input type="file" class="hidden" 
                      id="file_<?php echo $assignment['assignment_id']; ?>" 
                      name="submission" accept=".zip,.pdf,.doc,.docx">
                    <label for="file_<?php echo $assignment['assignment_id']; ?>" 
                      class="cursor-pointer px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                      <i class="fas fa-paperclip mr-1"></i> Attach
                    </label>
                    <button type="submit" disabled
                      class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                      Submit
                    </button>
                  </form>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <script>
      $(document).ready(function() {
        // Toggle dropdowns
        $('.relative button').click(function(e) {
          e.stopPropagation();
          $(this).next('.hidden').toggleClass('hidden');
          // Hide other dropdowns
          $('.relative button').not(this).next().addClass('hidden');
        });

        // Close dropdowns when clicking outside
        $(document).click(function() {
          $('.relative button').next().addClass('hidden');
        });

        // File input handling
        $('input[type="file"]').change(function() {
          const submitBtn = $(this).closest('form').find('button[type="submit"]');
          if (this.files.length > 0) {
            submitBtn.prop('disabled', false);
          } else {
            submitBtn.prop('disabled', true);
          }
        });
      });
    </script>
</body>

</html>