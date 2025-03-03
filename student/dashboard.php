<?php
require_once '../app/models/Auth.php';
require_once '../app/models/Course.php';
require_once '../app/models/Assignment.php';
require_once '../app/models/Submission.php';

$auth = new Auth();
$auth->requireRole('student');

$course = new Course();
$assignment = new Assignment();
$submission = new Submission();

// Get current user
$user = $auth->getCurrentUser();
$student_id = $user['user_id'];

// Fetch stats
$enrolled_query = "SELECT COUNT(*) as count FROM enrollments WHERE student_id = $student_id";
$enrolled_result = $course->db->query($enrolled_query);
$enrolled_count = mysqli_fetch_assoc($enrolled_result)['count'];

$pending_assignments = $assignment->getPendingForStudent($student_id);
$pending_count = mysqli_num_rows($pending_assignments);

// Calculate average grade
$grades_query = "SELECT AVG(g.grade_value) as avg_grade 
                FROM grades g 
                JOIN submissions s ON g.submission_id = s.submission_id 
                WHERE s.student_id = $student_id";
$grades_result = $submission->db->query($grades_query);
$avg_grade = round(mysqli_fetch_assoc($grades_result)['avg_grade'] ?? 0);

// Get recent submissions
$recent_submissions = $submission->getSubmissionsByStudent($student_id);
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
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Semester Info -->
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
                    <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg">
                        <span class="font-medium">First Semester 2025</span>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="text-gray-500 text-sm">Enrolled Courses</div>
                        <div class="text-2xl font-bold mt-1"><?php echo $enrolled_count; ?></div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="text-gray-500 text-sm">Assignments Due</div>
                        <div class="text-2xl font-bold mt-1 text-red-600"><?php echo $pending_count; ?></div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="text-gray-500 text-sm">Average Grade</div>
                        <div class="text-2xl font-bold mt-1"><?php echo $avg_grade; ?>%</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <div class="text-gray-500 text-sm">Hours This Week</div>
                        <div class="text-2xl font-bold mt-1">12.5</div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Current Courses -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4">Current Courses</h3>
                        <div class="space-y-4">
                            <?php
                            $courses_query = "SELECT c.*, u.first_name, u.last_name, e.progress_percentage 
                                            FROM courses c 
                                            JOIN enrollments e ON c.course_id = e.course_id 
                                            JOIN users u ON c.instructor_id = u.user_id 
                                            WHERE e.student_id = $student_id 
                                            AND e.status = 'in_progress'";
                            $courses_result = $course->db->query($courses_query);
                            while ($row = mysqli_fetch_assoc($courses_result)):
                            ?>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium"><?php echo htmlspecialchars($row['title']); ?></h4>
                                    <p class="text-sm text-gray-500">
                                        <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" 
                                             style="width: <?php echo $row['progress_percentage']; ?>%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600"><?php echo $row['progress_percentage']; ?>%</span>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Upcoming Deadlines -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4">Upcoming Deadlines</h3>
                        <div class="space-y-4">
                            <?php while ($assignment = mysqli_fetch_assoc($pending_assignments)): 
                                $due_date = new DateTime($assignment['due_date']);
                                $now = new DateTime();
                                $interval = $now->diff($due_date);
                                $is_due_today = $interval->days == 0;
                            ?>
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-medium <?php echo $is_due_today ? 'text-red-600' : 'text-orange-600'; ?>">
                                        <?php echo $is_due_today ? 'Due Today' : 'Due ' . $interval->days . ' days'; ?>
                                    </h4>
                                    <p class="font-medium"><?php echo htmlspecialchars($assignment['title']); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($assignment['course_title']); ?></p>
                                </div>
                                <span class="text-sm <?php echo $is_due_today ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600'; ?> px-3 py-1 rounded-full">
                                    <?php echo $due_date->format('H:i'); ?>
                                </span>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <?php while ($submission = mysqli_fetch_assoc($recent_submissions)): 
                            $submission_date = new DateTime($submission['submission_date']);
                        ?>
                        <div class="flex items-center text-sm">
                            <span class="w-2 h-2 <?php echo $submission['status'] === 'graded' ? 'bg-green-500' : 'bg-blue-500'; ?> rounded-full mr-2"></span>
                            <span class="text-gray-600">
                                <?php echo $submission['status'] === 'graded' ? 'Grade received:' : 'Assignment submitted:'; ?>
                            </span>
                            <span class="ml-1 font-medium"><?php echo htmlspecialchars($submission['assignment_title']); ?></span>
                            <span class="ml-auto text-gray-500">
                                <?php echo $submission_date->format('M j, Y H:i'); ?>
                            </span>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
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
