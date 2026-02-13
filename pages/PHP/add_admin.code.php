<?php
include_once '../../includes/db_connection.php'; // Make sure $pdo is available

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addAdmin_Btn'])) {
    try {
        // Get form values
        $full_Name = trim($_POST['full_Name']);
        $email     = trim($_POST['email']);
        $contact   = trim($_POST['contact']);
        $password  = $_POST['password']; // Hash it later

        // Validate required fields
        if (empty($full_Name) || empty($email) || empty($contact) || empty($password)) {
            die("All fields are required.");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Handle profile picture upload
        $profile_picture = null;

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
            }

            $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
            $originalName = basename($_FILES['profile_picture']['name']);
            $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newFileName = uniqid('admin_', true) . '.' . strtolower($fileExtension);
            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                $profile_picture = $newFileName;
            } else {
                die("Error uploading profile picture.");
            }
        }

        // Insert into the database
        $sql = "INSERT INTO admin (full_Name, email, contact, password, profile_picture)
                VALUES (:full_Name, :email, :contact, :password, :profile_picture)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':full_Name'       => $full_Name,
            ':email'           => $email,
            ':contact'         => $contact,
            ':password'        => $hashedPassword,
            ':profile_picture' => $profile_picture
        ]);

        // Redirect after successful insert
        header("Location: ../../pages/add_admin.php?status=success");
        exit;

    } catch (PDOException $e) {
        // Handle errors
        echo "Database error: " . $e->getMessage();
        exit;
    }
} else {
    // Redirect if accessed directly without form submission
    header("Location: ../../pages/add_admin.php");
    exit;
}
?>