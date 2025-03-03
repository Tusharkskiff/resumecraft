<?php
// Include the database connection
require_once 'connection.php';

// Start the session
session_start();

$emailError = '';
$passwordError = '';
$loginError = '';
$successMessage = '';

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle Registration
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        // Check if the email already exists in the login table
        $sql = "SELECT * FROM login WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Email already exists
            $emailError = "This email already exists";
        } else {
            // Check if password and confirm password match
            if ($password === $confirmPassword) {
                // Insert the user into the login table
                $sql = "INSERT INTO login (username, email, password) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username, $email, $password]);

                // Set success message
                $successMessage = "Registration successful! You can now log in.";
            } else {
                // Passwords do not match
                $passwordError = "Passwords do not match. Please try again.";
            }
        }
    }

    // Handle Login
    if (isset($_POST['login'])) {
        $loginEmail = $_POST['loginEmail'];
        $loginPassword = $_POST['loginPassword'];

        // Check if the email exists in the login table
        $sql = "SELECT * FROM login WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$loginEmail]);
        $user = $stmt->fetch();

        if ($user && $loginPassword === $user['password']) {
            // Successful login: Start session and redirect to the landing page
            $_SESSION['user_id'] = $user['id'];  // Set the session with the user's ID or any other information
            $_SESSION['user_email'] = $user['email'];  // You can store the email or username as well

            header('Location: landing.php'); // Redirect to the landing page
            exit(); // Make sure the script stops here to prevent any further output
        } else {
            // Invalid login credentials
            $loginError = "Invalid email or password. Please try again.";
        }
    }
}

// Real-time email existence check
if (isset($_POST['check_email'])) {
    $email = $_POST['check_email'];
    $sql = "SELECT * FROM login WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo 'exists';
    } else {
        echo 'available';
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResumeCraft - Your Personal Resume Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f4f7f6;
        }
        .container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            display: flex;
            justify-content: space-between;
            width: 70%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 30px;
            border-radius: 8px;
            background-color: white;
        }
        .form-box {
            width: 45%;
        }
        .form-box h2 {
            margin-bottom: 20px;
            color: #007bff;
        }
        .form-box button {
            width: 100%;
        }
        .form-box .alert {
            font-size: 0.9rem;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
        }
        .email-exists-error {
            color: red;
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <!-- Login Section -->
        <div class="form-box">
            <h2>Login to ResumeCraft</h2>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="loginEmail" class="form-label">Email</label>
                    <input type="email" name="loginEmail" id="loginEmail" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Password</label>
                    <input type="password" name="loginPassword" id="loginPassword" class="form-control" required>
                </div>
                <?php if (isset($loginError)): ?>
                    <div class="text-danger message"><?php echo $loginError; ?></div><br>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary" name="login">Login</button>
            </form>
        </div>

        <!-- Register Section -->
        <div class="form-box">
            <h2>Sign Up for ResumeCraft</h2>
            <form id="registerForm" action="login.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="registerEmail" class="form-label">Email</label>
                    <input type="email" name="email" id="registerEmail" class="form-control" required>
                    <div id="emailExistsError" class="email-exists-error">This email already exists</div>
                    <?php if (isset($emailError)): ?>
                        <div class="text-danger message"><?php echo $emailError; ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="registerPassword" class="form-label">Password</label>
                    <input type="password" name="password" id="registerPassword" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" required>
                    <div id="passwordMatchError" class="text-danger message" style="display: none;">Passwords do not match.</div>
                    <div id="passwordMatchSuccess" class="text-success message" style="display: none;">Passwords match!</div>
                </div>
                <?php if (isset($passwordError)): ?>
                    <div class="text-danger message"><?php echo $passwordError; ?></div><br>
                <?php endif; ?>
                <?php if (isset($successMessage)): ?>
                    <div class="text-success message"><?php echo $successMessage; ?></div><br>
                <?php endif; ?>
                <button type="submit" class="btn btn-success" name="register" id="submitBtn" disabled>Register</button>
            </form>
        </div>
    </div>
</div>

<!-- Footer Section -->
<div class="footer">
    <p>ResumeCraft &copy; 2025. Build your career with an amazing resume!</p>
</div>

<script>
    // Real-time email existence check with AJAX
    $('#registerEmail').on('input', function() {
        const email = $(this).val();
        if (email) {
            $.ajax({
                url: 'login.php',
                type: 'POST',
                data: { check_email: email },
                success: function(response) {
                    if (response == 'exists') {
                        $('#emailExistsError').show();
                        $('#submitBtn').prop('disabled', true); // Disable the submit button
                    } else {
                        $('#emailExistsError').hide();
                        $('#submitBtn').prop('disabled', false); // Enable the submit button
                    }
                }
            });
        } else {
            $('#emailExistsError').hide();
            $('#submitBtn').prop('disabled', false); // Enable the submit button if email is empty
        }
    });

    // Real-time password validation
    $('#confirmPassword').on('input', function() {
        const password = $('#registerPassword').val();
        const confirmPassword = $('#confirmPassword').val();
        
        if (password !== confirmPassword) {
            $('#passwordMatchError').show();
            $('#passwordMatchSuccess').hide();
        } else if (confirmPassword.length > 0) { // Check if something is typed in confirmPassword
            $('#passwordMatchError').hide();
            $('#passwordMatchSuccess').show();
        }
    });
</script>

</body>
</html>
