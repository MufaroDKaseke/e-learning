<?php
require_once '../app/models/Auth.php';
require_once '../app/models/Course.php';

$auth = new Auth();
$auth->requireRole('student');

$course = new Course();
$user = $auth->getCurrentUser();
$student_id = $user['user_id'];

// Get enrolled courses
$enrolled_query = "SELECT c.*, u.first_name, u.last_name, e.status, e.progress_percentage 
                  FROM courses c 
                  JOIN enrollments e ON c.course_id = e.course_id 
                  JOIN users u ON c.instructor_id = u.user_id 
                  WHERE e.student_id = $student_id";
$enrolled_courses = $course->db->query($enrolled_query);

// Get available courses (not enrolled)
$available_query = "SELECT c.*, u.first_name, u.last_name 
                   FROM courses c 
                   JOIN users u ON c.instructor_id = u.user_id 
                   WHERE c.course_id NOT IN (
                       SELECT course_id FROM enrollments WHERE student_id = $student_id
                   )";
$available_courses = $course->db->query($available_query);
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
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Enrolled Courses Section -->
            <section class="lg:w-3/5">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-4">My Enrolled Courses</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Course</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php while ($course = mysqli_fetch_assoc($enrolled_courses)): ?>
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="font-medium"><?php echo htmlspecialchars($course['title']); ?></div>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            <?php echo $course['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                                            <?php echo ucfirst($course['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <a href="course.php?id=<?php echo $course['course_id']; ?>" 
                                           class="text-blue-600 hover:text-blue-800">Open →</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Available Courses Section -->
            <section class="lg:w-2/5">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">Available Courses</h2>
                        <a href="browse-courses.php" class="text-blue-600 text-sm hover:text-blue-800">View All</a>
                    </div>
                    <ul class="divide-y divide-gray-200">
                        <?php while ($course = mysqli_fetch_assoc($available_courses)): ?>
                        <li class="py-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium"><?php echo htmlspecialchars($course['title']); ?></h3>
                                    <p class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($course['first_name'] . ' ' . $course['last_name']); ?>
                                    </p>
                                </div>
                                <form action="enroll.php" method="post" class="inline">
                                    <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                    <button type="submit" 
                                            class="px-3 py-1 text-sm border-2 border-blue-600 text-blue-600 rounded hover:bg-blue-50">
                                        Enroll
                                    </button>
                                </form>
                            </div>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </section>
        </div>
    </main>
    </div>

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
        });
    </script>
</body>

</html>