<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Login tracking
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

//Get action (signup or login)
$action = $_POST['action'] ?? '';

if ($action === 'signup') {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    //Check if user exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "Email already registered.";
        exit();
    }
    $checkStmt->close();

    //Hash password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //Insert new user
    $insertStmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $insertStmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

    if ($insertStmt->execute()) {
        // Get the inserted user's ID
        $user_id = $insertStmt->insert_id;

        // Set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        if ($role === "seller") {
            // Get the new user's ID 
            $user_id = $conn->insert_id;

            // Use the user's name as the default store name
            $default_store_name = $name . "'s Store";
            $stmt = $conn->prepare("INSERT INTO sellers (user_id, store_name, status) VALUES (?, ?, 'active')");
            $stmt->bind_param("is", $user_id, $default_store_name);
            $stmt->execute();
            $stmt->close();

            header("Location: /rainbow_market/seller/dashboard.php"); // redirect seller
        } else {
            header("Location: /rainbow_market/index.php"); // redirect buyer
        }
        exit();
    } else {
        echo "Error: " . $insertStmt->error;
        exit();
    }
    $insertStmt->close();

} elseif ($action === 'login') {
    //handle user login
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    //Check email and role
    $loginStmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $loginStmt->bind_param("ss", $email, $role);
    $loginStmt->execute();
    $result = $loginStmt->get_result();

    //Retrieve password 
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            if ($role === "seller") {
                header("Location: /rainbow_market/seller/dashboard.php"); // redirect seller
            } else {
                header("Location: /rainbow_market/index.php"); // redirect buyer 
            }
            exit();
        } else {
            echo "Incorrect password";
            exit();
        }
    } else {
        echo "No user found with this email and role";
        exit();
    }
    $loginStmt->close();
} else {
    echo "Invalid form submission";
    exit();
}

// After user registration or login
$stmt = $conn->prepare("SELECT user_id FROM wallet WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO wallet (user_id, balance) VALUES (?, 0)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}
$stmt->close();
?>
