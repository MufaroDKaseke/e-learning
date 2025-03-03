<?php
require_once __DIR__ . '/app/models/Auth.php';

$auth = new Auth();

// Redirect if already logged in
if ($auth->session->isLoggedIn()) {
  header('Location: dashboard.php');
  exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $required_fields = ['first_name', 'last_name', 'email', 'password', 'confirm_password'];
  $missing_fields = array_filter($required_fields, function ($field) {
    return empty($_POST[$field]);
  });

  if (!empty($missing_fields)) {
    $error = 'All fields are required';
  } elseif ($_POST['password'] !== $_POST['confirm_password']) {
    $error = 'Passwords do not match';
  } else {
    $userData = [
      'email' => $_POST['email'],
      'password' => $_POST['password'],
      'first_name' => $_POST['first_name'],
      'last_name' => $_POST['last_name'],
      'role' => 'student' // Default role
    ];

    try {
      if ($auth->user->create($userData)) {
        $success = 'Account created successfully. You can now login.';
      } else {
        $error = 'Failed to create account';
      }
    } catch (Exception $e) {
      $error = 'An error occurred. Please try again.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - E-Learning Platform</title>
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
  <div class="max-w-md w-full">
    <!-- Logo/Home Link -->
    <div class="text-center mb-8">
      <a href="index.html" class="text-white text-3xl font-bold">E-Learning</a>
    </div>

    <form class="bg-white bg-opacity-10 backdrop-blur-sm p-8 rounded-lg shadow-lg" method="post" action="">
      <h2 class="text-2xl font-bold text-white mb-6 text-center">Create Your Account</h2>

      <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">First Name</label>
            <input type="text" name="first_name"
              class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
              placeholder="First name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
          </div>
          <div>
            <label class="block text-gray-300 text-sm font-medium mb-2">Last Name</label>
            <input type="text" name="last_name"
              class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
              placeholder="Last name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
          </div>
        </div>

        <div>
          <label class="block text-gray-300 text-sm font-medium mb-2">Email Address</label>
          <input type="email" name="email"
            class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
            placeholder="Enter your email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>

        <div>
          <label class="block text-gray-300 text-sm font-medium mb-2">Password</label>
          <input type="password" name="password"
            class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
            placeholder="Create a password">
        </div>

        <div>
          <label class="block text-gray-300 text-sm font-medium mb-2">Confirm Password</label>
          <input type="password" name="confirm_password"
            class="w-full px-3 py-2 bg-white bg-opacity-20 border border-gray-300 border-opacity-20 rounded text-white placeholder-gray-400 focus:outline-none focus:border-white focus:ring-1 focus:ring-white"
            placeholder="Confirm your password">
        </div>

        <div class="flex items-center">
          <input type="checkbox" class="h-4 w-4 rounded border-gray-300 bg-opacity-20">
          <label class="ml-2 text-sm text-gray-300">I agree to the Terms of Service and Privacy Policy</label>
        </div>

        <button type="submit"
          class="w-full bg-white text-navy font-semibold py-2 px-4 rounded hover:bg-opacity-90 transition duration-300">
          Create Account
        </button>

        <p class="text-center text-gray-300 text-sm">
          Already have an account?
          <a href="login.php" class="text-white hover:underline">Sign in</a>
        </p>
      </div>
    </form>
  </div>
</body>

</html>