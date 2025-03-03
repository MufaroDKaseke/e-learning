<?php
require_once '../app/models/Auth.php';
require_once '../app/models/Course.php';

$auth = new Auth();
$auth->requireRole('student');

$user = $auth->getCurrentUser();
$student_id = $user['user_id'];

$course = new Course();
$db = $course->db;

// Get student's courses with grades
$query = "SELECT c.*, 
            GROUP_CONCAT(DISTINCT a.assignment_id) as assignment_ids,
            GROUP_CONCAT(DISTINCT g.grade_value) as grades,
            GROUP_CONCAT(DISTINCT a.weight_percentage) as weights,
            GROUP_CONCAT(DISTINCT a.title) as assignment_titles,
            COALESCE(AVG(g.grade_value), 0) as final_grade
         FROM courses c
         JOIN enrollments e ON c.course_id = e.course_id
         LEFT JOIN assignments a ON c.course_id = a.course_id
         LEFT JOIN submissions s ON a.assignment_id = s.assignment_id AND s.student_id = $student_id
         LEFT JOIN grades g ON s.submission_id = g.submission_id
         WHERE e.student_id = $student_id
         GROUP BY c.course_id
         ORDER BY c.title";

$courses = $db->query($query);
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
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Header with term selector -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Academic Performance</h2>
                    <!-- Term selector removed for now as we're not filtering by term -->
                </div>

                <!-- Grades List -->
                <div class="space-y-4">
                    <?php 
                    while ($course = mysqli_fetch_assoc($courses)):
                        $assignment_ids = explode(',', $course['assignment_ids'] ?? '');
                        $grades = explode(',', $course['grades'] ?? '');
                        $weights = explode(',', $course['weights'] ?? '');
                        $titles = explode(',', $course['assignment_titles'] ?? '');
                        $final_grade = number_format($course['final_grade'], 1);
                    ?>
                    <div class="border rounded-lg">
                        <button class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-blue-600 text-xl"></i>
                                </div>
                                <div class="text-left">
                                    <h3 class="font-medium"><?php echo htmlspecialchars($course['title']); ?></h3>
                                    <p class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($course['course_code']); ?> • Fall 2023
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-2xl font-bold text-blue-600">
                                    <?php echo $final_grade ?: '--'; ?>
                                </span>
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </button>
                        <!-- Expandable Content -->
                        <div class="hidden border-t px-4 py-3 bg-gray-50">
                            <table class="w-full text-sm">
                                <thead class="text-gray-500">
                                    <tr>
                                        <th class="text-left py-2">Assessment</th>
                                        <th class="text-right py-2">Weight</th>
                                        <th class="text-right py-2">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php 
                                    for ($i = 0; $i < count($assignment_ids); $i++):
                                        if (empty($assignment_ids[$i])) continue;
                                    ?>
                                    <tr>
                                        <td class="py-2"><?php echo htmlspecialchars($titles[$i]); ?></td>
                                        <td class="text-right"><?php echo $weights[$i]; ?>%</td>
                                        <td class="text-right"><?php echo $grades[$i] ?? '--'; ?></td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endwhile; ?>
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

                // Toggle grade details
                $('.border.rounded-lg button').click(function() {
                    $(this).next('.hidden').toggleClass('hidden');
                    $(this).find('.fa-chevron-down').toggleClass('rotate-180');
                });
            });
        </script>
</body>

</html>