<?php
require_once __DIR__ . '/app/models/Auth.php';

$auth = new Auth();

// Redirect if already logged in
if ($auth->session->isLoggedIn()) {
    $role = $auth->session->getUserRole();
    $redirect = $role === 'instructor' ? './lecturer/dashboard.php' : './student/dashboard.php';
    header("Location: $redirect");
    exit();
}

$error = '';
$activeTab = $_GET['tab'] ?? 'student'; // Default to student tab

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'student';

    if ($auth->login($email, $password)) {
        $redirect = $role === 'instructor' ? './lecturer/dashboard.php' : './student/dashboard.php';
        header("Location: $redirect");
        exit();
    } else {
        $error = 'Invalid email or password';
        $activeTab = $role === 'instructor' ? 'lecturer' : 'student';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-navy min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo/Home Link -->
        <div class="text-center">
            <a href="index.html" class="text-white text-3xl font-bold">E-Learning</a>
        </div>

        <!-- Login Container -->
        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg shadow-lg overflow-hidden">
            <!-- Tabs -->
            <div class="flex border-b border-gray-200 border-opacity-20">
                <button onclick="switchTab('student')" 
                        class="flex-1 py-4 px-6 text-sm font-medium text-center <?php echo $activeTab === 'student' ? 'text-white border-b-2 border-white' : 'text-gray-300 hover:text-white'; ?>">
                    Student Login
                </button>
                <button onclick="switchTab('lecturer')"
                        class="flex-1 py-4 px-6 text-sm font-medium text-center <?php echo $activeTab === 'lecturer' ? 'text-white border-b-2 border-white' : 'text-gray-300 hover:text-white'; ?>">
                    Lecturer Login
                </button>
            </div>

            <!-- Student Login Form -->
            <form action="" method="post" id="studentForm" class="p-8 space-y-6 <?php echo $activeTab === 'student' ? '' : 'hidden'; ?>">
                <input type="hidden" name="role" value="student">
                <?php if ($error && $activeTab === 'student'): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Student Email</label>
                        <input type="email" name="email" 
                               class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
                               placeholder="student@students.nust.ac.zw">
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Password</label>
                        <input type="password" name="password"
                               class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
                               placeholder="Enter your password">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 bg-opacity-20">
                            <label class="ml-2 text-sm text-gray-300">Remember me</label>
                        </div>
                        <a href="#" class="text-sm text-gray-300 hover:text-white">Forgot Password?</a>
                    </div>

                    <button type="submit"
                            class="w-full bg-white text-navy font-semibold py-2 px-4 rounded hover:bg-opacity-90 transition duration-300">
                        Sign In as Student
                    </button>
                </div>
            </form>

            <!-- Lecturer Login Form -->
            <form action="" method="post" id="lecturerForm" class="p-8 space-y-6 <?php echo $activeTab === 'lecturer' ? '' : 'hidden'; ?>">
                <input type="hidden" name="role" value="instructor">
                <?php if ($error && $activeTab === 'lecturer'): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Staff Email</label>
                        <input type="email" name="email"
                               class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
                               placeholder="lecturer@nust.ac.zw">
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Password</label>
                        <input type="password" name="password"
                               class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
                               placeholder="Enter your password">
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 rounded border-gray-300 bg-opacity-20">
                            <label class="ml-2 text-sm text-gray-300">Remember me</label>
                        </div>
                        <a href="#" class="text-sm text-gray-300 hover:text-white">Forgot Password?</a>
                    </div>

                    <button type="submit"
                            class="w-full bg-white text-navy font-semibold py-2 px-4 rounded hover:bg-opacity-90 transition duration-300">
                        Sign In as Lecturer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Update URL without reloading
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);

            // Show/hide forms
            document.getElementById('studentForm').classList.toggle('hidden', tab !== 'student');
            document.getElementById('lecturerForm').classList.toggle('hidden', tab !== 'lecturer');

            // Update tab styling
            const buttons = document.querySelectorAll('button[onclick^="switchTab"]');
            buttons.forEach(button => {
                if (button.getAttribute('onclick').includes(tab)) {
                    button.classList.add('text-white', 'border-b-2', 'border-white');
                    button.classList.remove('text-gray-300');
                } else {
                    button.classList.remove('text-white', 'border-b-2', 'border-white');
                    button.classList.add('text-gray-300');
                }
            });
        }
    </script>
</body>
</html>