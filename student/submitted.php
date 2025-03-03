<?php
require_once '../app/models/Auth.php';
require_once '../app/models/Course.php';
require_once '../app/models/Submission.php';

$auth = new Auth();
$auth->requireRole('student');

$course = new Course();
$submission = new Submission();
$user = $auth->getCurrentUser();
$student_id = $user['user_id'];

// Get enrolled courses for filter
$courses_query = "SELECT DISTINCT c.* FROM courses c 
                 JOIN assignments a ON c.course_id = a.course_id
                 JOIN submissions s ON a.assignment_id = s.assignment_id
                 WHERE s.student_id = $student_id";
$courses = $course->db->query($courses_query);

// Apply filters
$course_filter = isset($_GET['course_id']) ? (int)$_GET['course_id'] : null;
$status_filter = isset($_GET['status']) ? $_GET['status'] : null;

// Get submissions with grades
$query = "SELECT s.*, a.title as assignment_title, a.description, 
                 c.title as course_title, g.grade_value, g.feedback,
                 s.submission_date, s.status
          FROM submissions s
          JOIN assignments a ON s.assignment_id = a.assignment_id
          JOIN courses c ON a.course_id = c.course_id
          LEFT JOIN grades g ON s.submission_id = g.submission_id
          WHERE s.student_id = $student_id";

if ($course_filter) {
    $query .= " AND c.course_id = $course_filter";
}
if ($status_filter) {
    $query .= " AND s.status = '" . $submission->db->escape($status_filter) . "'";
}

$query .= " ORDER BY s.submission_date DESC";
$submissions = $submission->db->query($query);
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
                        <h2 class="text-2xl font-bold">Submitted Work</h2>
                        <div class="flex items-center space-x-4">
                            <form action="" method="get" class="flex space-x-4">
                                <select name="course_id" class="text-sm border rounded-md px-3 py-2 text-gray-600" onchange="this.form.submit()">
                                    <option value="">All Courses</option>
                                    <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                                        <option value="<?php echo $course['course_id']; ?>"
                                                <?php echo isset($_GET['course_id']) && $_GET['course_id'] == $course['course_id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <select name="status" class="text-sm border rounded-md px-3 py-2 text-gray-600" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="graded" <?php echo isset($_GET['status']) && $_GET['status'] == 'graded' ? 'selected' : ''; ?>>Graded</option>
                                    <option value="submitted" <?php echo isset($_GET['status']) && $_GET['status'] == 'submitted' ? 'selected' : ''; ?>>Pending Review</option>
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Submitted Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php while ($submission = mysqli_fetch_assoc($submissions)): 
                                $submitted_date = new DateTime($submission['submission_date']);
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900"><?php echo htmlspecialchars($submission['assignment_title']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($submission['description']); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($submission['course_title']); ?></td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600"><?php echo $submitted_date->format('M j, Y'); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo $submitted_date->format('g:i A'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        <?php echo $submission['status'] === 'graded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo ucfirst($submission['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium">
                                        <?php echo $submission['grade_value'] ?? '--'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($submission['status'] === 'graded'): ?>
                                        <button onclick="showFeedback('<?php echo htmlspecialchars($submission['feedback']); ?>')"
                                                class="text-blue-600 hover:text-blue-800">
                                            View Feedback
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo htmlspecialchars($submission['file_path']); ?>" 
                                           class="text-blue-600 hover:text-blue-800" target="_blank">
                                            View Submission
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Feedback Modal -->
    <div id="feedbackModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-2">Feedback</h3>
                <p id="feedbackText" class="text-sm text-gray-500"></p>
            </div>
            <div class="mt-4">
                <button onclick="hideFeedback()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
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

        function showFeedback(feedback) {
            document.getElementById('feedbackText').textContent = feedback;
            document.getElementById('feedbackModal').classList.remove('hidden');
        }

        function hideFeedback() {
            document.getElementById('feedbackModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('feedbackModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideFeedback();
            }
        });
    </script>
</body>

</html>