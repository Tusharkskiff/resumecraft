<?php
session_start();
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require 'connection.php';  


$user_id = $_SESSION['user_id'];

// Fetch the username from the login table using the user ID
$query = "SELECT username FROM login WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user_info = $stmt->fetch();

// Check if a user was found, else handle the error
if ($user_info) {
    $username = $user_info['username'];  
} else {
    header('Location: index.php');
    exit();
}

// Fetch all data at once (no need to repeat multiple SELECT queries)
$query = "SELECT * FROM basic WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();

$query = "SELECT * FROM experience WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$experience_data = $stmt->fetchAll();

// Check if there is at least one row for experience
if (count($experience_data) > 0) {
    $experience_data = $experience_data[0]; 
} else {
    
    $experience_data = []; 
}

$query = "SELECT * FROM education WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$education_data = $stmt->fetch();

$query = "SELECT * FROM skills WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$skills_data = $stmt->fetch();

$query = "SELECT * FROM achievements WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$achievements_data = $stmt->fetch();

// Update Basic Information
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_basic'])) {
    $name = $_POST['name'];
    $resume_email = $_POST['resume_email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $executive_summary = $_POST['executive_summary'];

    $query = "INSERT INTO basic (id, name, phone_number, address, executive_summary, resume_email)
              VALUES (?, ?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE 
                name = ?, phone_number = ?, address = ?, executive_summary = ?, resume_email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        $user_id, $name, $phone_number, $address, $executive_summary, $resume_email,
        $name, $phone_number, $address, $executive_summary, $resume_email
    ]);

    $_SESSION['message'] = 'Basic information updated successfully!';
    header('Location: landing.php');
    exit();
}

// Delete Basic Information
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_basic'])) {
    $query = "DELETE FROM basic WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);

    $_SESSION['message'] = 'Basic information deleted successfully!';
    header('Location: landing.php');
    exit();
}

// Update Experience
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_experience'])) {
    $type = $_POST['type'];
    $title = $_POST['title'];
    $company_name = $_POST['company_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $responsibilities = $_POST['responsibilities'];

    $query = "INSERT INTO experience (id, type, title, company_name, start_date, end_date, responsibilities)
              VALUES (?, ?, ?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE
                type = ?, title = ?, company_name = ?, start_date = ?, end_date = ?, responsibilities = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        $user_id, $type, $title, $company_name, $start_date, $end_date, $responsibilities,
        $type, $title, $company_name, $start_date, $end_date, $responsibilities
    ]);

    $_SESSION['message'] = 'Experience updated successfully!';
    header('Location: landing.php');
    exit();
}

// Delete Experience
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_experience'])) {
    $query = "DELETE FROM experience WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);

    $_SESSION['message'] = 'Experience deleted successfully!';
    header('Location: landing.php');
    exit();
}

// Update Education
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_education'])) {
    $school = $_POST['school'];
    $tenth_date = $_POST['tenth_date'];
    $tenth_marks = $_POST['tenth_marks'];
    $twelfth_school = $_POST['twelfth_school'];
    $twelfth_date = $_POST['twelfth_date'];
    $twelfth_percentage = $_POST['twelfth_percentage'];
    $college_name = $_POST['college_name'];
    $completion_date = $_POST['completion_date'];
    $gpa = $_POST['gpa'];

    $query = "INSERT INTO education (id, school, tenth_date, tenth_marks, twelfth_school, twelfth_date, twelfth_percentage, college_name, completion_date, gpa)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE
                school = ?, tenth_date = ?, tenth_marks = ?, twelfth_school = ?, twelfth_date = ?, twelfth_percentage = ?, college_name = ?, completion_date = ?, gpa = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        $user_id, $school, $tenth_date, $tenth_marks, $twelfth_school, $twelfth_date, $twelfth_percentage, $college_name, $completion_date, $gpa,
        $school, $tenth_date, $tenth_marks, $twelfth_school, $twelfth_date, $twelfth_percentage, $college_name, $completion_date, $gpa
    ]);

    $_SESSION['message'] = 'Education updated successfully!';
    header('Location: landing.php');
    exit();
}

// Delete Education
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_education'])) {
    $query = "DELETE FROM education WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);

    $_SESSION['message'] = 'Education deleted successfully!';
    header('Location: landing.php');
    exit();
}

// Update Skills
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_skills'])) {
    $skills = $_POST['skills']; 

    $query = "INSERT INTO skills (id, s1, s2, s3, s4, s5, s6, s7, s8, s9, s10)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
              ON DUPLICATE KEY UPDATE 
                s1 = ?, s2 = ?, s3 = ?, s4 = ?, s5 = ?, s6 = ?, s7 = ?, s8 = ?, s9 = ?, s10 = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array_merge([$user_id], $skills, $skills));

    $_SESSION['message'] = 'Skills updated successfully!';
    header('Location: landing.php');
    exit();
}

// Delete Skills
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_skills'])) {
    $query = "DELETE FROM skills WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);

    $_SESSION['message'] = 'Skills deleted successfully!';
    header('Location: landing.php');
    exit();
}

// Update Achievements
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_achievements'])) {
    $achieve = $_POST['achieve'];

    $query = "INSERT INTO achievements (id, achieve)
              VALUES (?, ?)
              ON DUPLICATE KEY UPDATE 
                achieve = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id, $achieve, $achieve]);

    $_SESSION['message'] = 'Achievements updated successfully!';
    header('Location: landing.php');
    exit();
}

// Delete Achievements
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_achievements'])) {
    $query = "DELETE FROM achievements WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);

    $_SESSION['message'] = 'Achievements deleted successfully!';
    header('Location: landing.php');
    exit();
}


// Fetch all data at once (no need to repeat multiple SELECT queries)
$query = "SELECT * FROM basic WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();

$query = "SELECT * FROM experience WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$experience_data = $stmt->fetch(); 

// Check if experience data is available
if (!$experience_data) {
    $experience_data = []; 
}

$query = "SELECT * FROM education WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$education_data = $stmt->fetch();

$query = "SELECT * FROM skills WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$skills_data = $stmt->fetch();

$query = "SELECT * FROM achievements WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$achievements_data = $stmt->fetch();

// Check if the user clicked the download PDF link
if (isset($_GET['download_pdf']) && $_GET['download_pdf'] == '1') {
    $pdf = new TCPDF();
    $pdf->SetTitle('Resume - ' . $user_data['name']);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 18);

    // Header with name and title (with colored background)
    $pdf->SetTextColor(255, 255, 255); 
    $pdf->SetFillColor(0, 0, 0); 
    $pdf->Cell(0, 15, $user_data['name'], 0, 1, 'C', 1);
    $pdf->Ln(5); 

    // User Details Section (with icons)
    $pdf->SetTextColor(0, 0, 0); 

    // Email
    $pdf->SetFont('helvetica', 'B', 12); 
    $pdf->Cell(40, 10, 'Email:', 0, 0, 'L');  
    $pdf->SetFont('helvetica', '', 12);  
    $pdf->Cell(0, 10, $user_data['resume_email'], 0, 1, 'L'); 
    
    // Phone
    $pdf->SetFont('helvetica', 'B', 12); 
    $pdf->Cell(40, 10, 'Phone:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);  
    $pdf->Cell(0, 10, $user_data['phone_number'], 0, 1, 'L'); 

    // Address
    $pdf->SetFont('helvetica', 'B', 12); 
    $pdf->Cell(40, 10, 'Address:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);  
    $pdf->Cell(0, 10, $user_data['address'], 0, 1, 'L'); 
    $pdf->Ln(10); 

    // Executive Summary Section (boxed with light color)
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetFillColor(240, 240, 240); 
    $pdf->Cell(0, 10, 'Executive Summary', 0, 1, 'L', 1);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, $user_data['executive_summary'], 0, 'L');  
    $pdf->Ln(5); 

    // Professional Experience Section (boxed with light color)
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetFillColor(240, 240, 240); 
    $pdf->Cell(0, 10, 'Professional Experience', 0, 1, 'L', 1);
    $pdf->SetFont('helvetica', '', 12);

    // Check if experience data is available and print it
    if (!empty($experience_data)) {
        // Title
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(40, 10, 'Title:', 0, 0, 'L'); 
        $pdf->SetFont('helvetica', '', 12);  
        $pdf->Cell(0, 10, $experience_data['title'], 0, 1, 'L'); 

        // Company
        $pdf->SetFont('helvetica', 'B', 12); 
        $pdf->Cell(40, 10, 'Company:', 0, 0, 'L'); 
        $pdf->SetFont('helvetica', '', 12); 
        $pdf->Cell(0, 10, $experience_data['company_name'], 0, 1, 'L');

        // Type
        $pdf->SetFont('helvetica', 'B', 12); 
        $pdf->Cell(40, 10, 'Type:', 0, 0, 'L'); 
        $pdf->SetFont('helvetica', '', 12);  
        $pdf->Cell(0, 10, $experience_data['type'], 0, 1, 'L'); 

        // From / To Dates
        $pdf->SetFont('helvetica', 'B', 12); 
        $pdf->Cell(40, 10, 'From:', 0, 0, 'L'); 
        $pdf->SetFont('helvetica', '', 12);  
        $pdf->Cell(0, 10, $experience_data['start_date'] . ' To: ' . $experience_data['end_date'], 0, 1, 'L'); 

        // Responsibilities (start from the beginning of the line)
        $responsibilities = trim($experience_data['responsibilities']);  
        $pdf->SetFont('helvetica', 'B', 12); 
        $pdf->Cell(40, 10, 'Responsibilities:', 0, 0, 'L'); 
        $pdf->SetFont('helvetica', '', 12);   
        $pdf->MultiCell(0, 10, $responsibilities, 0, 'L');  
        $pdf->Ln(5); 
    } else {
        // If no experience data, show a message
        $pdf->Cell(0, 10, 'No experience data available.', 0, 1);
    }

    // Education Section (with a modern design)
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(0, 10, 'Education', 0, 1, 'L', 1);
    $pdf->SetFont('helvetica', '', 12);

    // Print Education Data
    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, 'School:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['school'], 0, 1, 'L'); 

    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, '10th Date:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['tenth_date'], 0, 1, 'L'); 

    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, '10th Marks:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['tenth_marks'], 0, 1, 'L'); 

    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, '12th School:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['twelfth_school'], 0, 1, 'L'); 

    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, '12th Date:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['twelfth_date'], 0, 1, 'L'); 

    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, '12th Percentage:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['twelfth_percentage'], 0, 1, 'L'); 

    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, 'College:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['college_name'], 0, 1, 'L'); 

    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, 'Completion Date:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['completion_date'], 0, 1, 'L'); 

    $pdf->SetFont('helvetica', 'B', 12);  
    $pdf->Cell(40, 10, 'GPA:', 0, 0, 'L'); 
    $pdf->SetFont('helvetica', '', 12);   
    $pdf->Cell(0, 10, $education_data['gpa'], 0, 1, 'L'); 

    $pdf->Ln(5); 
    $pdf->Ln();  


    // Skills Section (with icons and neat presentation)
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetFillColor(240, 240, 240); 
    $pdf->Cell(0, 10, 'Skills', 0, 1, 'L', 1);
    $pdf->SetFont('helvetica', '', 12);

    for ($i = 1; $i <= 10; $i++) {
        $skill = $skills_data['s' . $i];
        if ($skill) {
            $pdf->Cell(0, 10, $skill, 0, 1);
        }
    }
    $pdf->Ln(5); 

    // Achievements Section (boxed)
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->SetFillColor(240, 240, 240); 
    $pdf->Cell(0, 10, 'Achievements', 0, 1, 'L', 1);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->MultiCell(0, 10, $achievements_data['achieve'], 0, 'L');  

    // Add a horizontal line to separate footer
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, $pdf->GetY() + 5, 200, $pdf->GetY() + 5);

    // Output the PDF (with download prompt)
    $pdf->Output('resume_' . $user_data['name'] . '.pdf', 'D');  
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResumeCraft - Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="logo.png" type="image/png">
    <style>
        body {
            background-color: #f4f8fc;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #0056b3;
        }
        .navbar a {
            color: white !important;
        }
        .form-container {
            max-width: 800px;
            margin-top: 50px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #0056b3;
            border-color: #00408b;
        }
        .btn-primary:hover {
            background-color: #003366;
            border-color: #00264d;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
        }
        h1 {
            font-size: 2.5rem;
            text-align: center;
            color: #0056b3;
            font-weight: 700;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .alert {
            margin-top: 20px;
            text-align: center;
        }
        .form-row {
            margin-bottom: 15px;
        }
        .center-btn-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .btn-full-width {
            width: 100%;
            max-width: 400px; /* Adjust this width as needed */
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">ResumeCraft</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <br>
    <div class="container">
        <h2 class="text-center">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    </div>

    <!-- Message Display -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Basic Information Form -->
    <div class="container form-container">
        <h1>Basic Information</h1>
        <form method="POST" action="landing.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="resume_email">Resume Email</label>
                <input type="email" class="form-control" id="resume_email" name="resume_email" value="<?php echo htmlspecialchars($user_data['resume_email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user_data['phone_number'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="executive_summary">Executive Summary</label>
                <textarea class="form-control" id="executive_summary" name="executive_summary" rows="3"><?php echo htmlspecialchars($user_data['executive_summary'] ?? ''); ?></textarea>
            </div>
            <div class="button-group">
                <button type="submit" name="update_basic" class="btn btn-primary btn-sm">Update</button>
                <button type="submit" name="delete_basic" class="btn btn-danger btn-sm">Delete</button>
            </div>
        </form>
    </div>


<!-- Experience Form -->
<div class="container form-container">
    <h1>Experience</h1>
    <form method="POST" action="landing.php">
        <div class="form-group">
            <label for="type">Type of Experience</label>
            <input type="text" class="form-control" id="type" name="type" 
                   value="<?php echo htmlspecialchars($experience_data['type'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="title">Job Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                   value="<?php echo htmlspecialchars($experience_data['title'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="company_name">Company Name</label>
            <input type="text" class="form-control" id="company_name" name="company_name" 
                   value="<?php echo htmlspecialchars($experience_data['company_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" 
                   value="<?php echo htmlspecialchars($experience_data['start_date'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" 
                   value="<?php echo htmlspecialchars($experience_data['end_date'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="responsibilities">Responsibilities</label>
            <textarea class="form-control" id="responsibilities" name="responsibilities" rows="3">
            <?php 
            // Trim leading and trailing whitespace and tabs
            $responsibilities = isset($experience_data['responsibilities']) ? $experience_data['responsibilities'] : '';
            $responsibilities = preg_replace('/^\s+/', '', $responsibilities);  // Remove leading spaces/tabs
            echo htmlspecialchars($responsibilities);
            ?>
        </textarea>


        </div>
        <div class="button-group">
            <button type="submit" name="update_experience" class="btn btn-primary btn-sm">Update</button>
            <button type="submit" name="delete_experience" class="btn btn-danger btn-sm">Delete</button>
        </div>
    </form>
</div>



<!-- Education Form -->
<div class="container form-container">
    <h1>Education</h1>
    <form method="POST" action="landing.php">
        <!-- School Information -->
        <div class="form-group">
            <label for="school">School Name</label>
            <input type="text" class="form-control" id="school" name="school" value="<?php echo htmlspecialchars($education_data['school'] ?? ''); ?>">
        </div>
        
        <!-- 10th Information -->
        <div class="form-group">
            <label for="tenth_date">10th Date</label>
            <input type="date" class="form-control" id="tenth_date" name="tenth_date" value="<?php echo htmlspecialchars($education_data['tenth_date'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="tenth_marks">10th Marks</label>
            <input type="text" class="form-control" id="tenth_marks" name="tenth_marks" value="<?php echo htmlspecialchars($education_data['tenth_marks'] ?? ''); ?>">
        </div>
        
        <!-- 12th Information -->
        <div class="form-group">
            <label for="twelfth_school">12th School Name</label>
            <input type="text" class="form-control" id="twelfth_school" name="twelfth_school" value="<?php echo htmlspecialchars($education_data['twelfth_school'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="twelfth_date">12th Date</label>
            <input type="date" class="form-control" id="twelfth_date" name="twelfth_date" value="<?php echo htmlspecialchars($education_data['twelfth_date'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="twelfth_percentage">12th Percentage</label>
            <input type="text" class="form-control" id="twelfth_percentage" name="twelfth_percentage" value="<?php echo htmlspecialchars($education_data['twelfth_percentage'] ?? ''); ?>">
        </div>
        
        <!-- College Information -->
        <div class="form-group">
            <label for="college_name">College Name</label>
            <input type="text" class="form-control" id="college_name" name="college_name" value="<?php echo htmlspecialchars($education_data['college_name'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="completion_date">Completion Date</label>
            <input type="date" class="form-control" id="completion_date" name="completion_date" value="<?php echo htmlspecialchars($education_data['completion_date'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="gpa">GPA</label>
            <input type="text" class="form-control" id="gpa" name="gpa" value="<?php echo htmlspecialchars($education_data['gpa'] ?? ''); ?>">
        </div>
        
        <!-- Submit and Delete Buttons -->
        <div class="button-group">
            <button type="submit" name="update_education" class="btn btn-primary btn-sm">Update</button>
            <button type="submit" name="delete_education" class="btn btn-danger btn-sm">Delete</button>
        </div>
    </form>
</div>


    <!-- Skills Form (Updated to 5x2 grid) -->
    <div class="container form-container">
        <h1>Skills</h1>
        <form method="POST" action="landing.php">
            <div class="form-row">
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s1'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s2'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s3'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s4'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s5'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s6'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s7'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s8'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s9'] ?? ''); ?>">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="skills[]" value="<?php echo htmlspecialchars($skills_data['s10'] ?? ''); ?>">
                </div>
            </div>
            <div class="button-group">
                <button type="submit" name="update_skills" class="btn btn-primary btn-sm">Update</button>
                <button type="submit" name="delete_skills" class="btn btn-danger btn-sm">Delete</button>
            </div>
        </form>
    </div>

    <!-- Achievements Form -->
    <div class="container form-container">
        <h1>Achievements</h1>
        <form method="POST" action="landing.php">
            <div class="form-group">
                <label for="achieve">Achievement</label>
                <textarea class="form-control" id="achieve" name="achieve" rows="3"><?php echo htmlspecialchars($achievements_data['achieve'] ?? ''); ?></textarea>
            </div>
            <div class="button-group">
                <button type="submit" name="update_achievements" class="btn btn-primary btn-sm">Update</button>
                <button type="submit" name="delete_achievements" class="btn btn-danger btn-sm">Delete</button>
            </div>
        </form>
    </div>

    <div class="center-btn-container">
    <form method="POST" action="landing.php">
        <a href="landing.php?download_pdf=1" class="btn btn-success btn-block mt-3">Download Resume as PDF</a>
    </form>
</div>
<!-- Footer Section -->
<div class="footer">
    <p>ResumeCraft &copy; 2025. Build your career with an amazing resume!</p>
</div>

</body>
</html>
