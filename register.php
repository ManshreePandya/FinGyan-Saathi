<?php
session_start();
require_once("config.php");

$register_error = "";
$register_success = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($username == "" || $email == "" || $password == "") {
        $register_error = "All fields are required!";
    } else {

        // 🔥 CHECK BOTH USERNAME & EMAIL
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing['email'] === $email) {
                $register_error = "Email already registered!";
            } elseif ($existing['username'] === $username) {
                $register_error = "Username already taken!";
            }
        } else {
            // Insert new user
            $hashPass = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:u, :e, :p)");
            $stmt->bindParam(':u', $username);
            $stmt->bindParam(':e', $email);
            $stmt->bindParam(':p', $hashPass);

            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                header("Location: home.html");
                exit;
            } else {
                $register_error = "Registration failed!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
:root {
            --primary-color: #ff8c42;
            --text-color: #333;
            --light-text-color: #666;
            --background-color: #f7f7f7;
            --white: #fff;
            --border-color: #ddd;
            --font-family: 'Roboto', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            color: var(--text-color);
            line-height: 1.6;
            background-color: var(--background-color);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: var(--primary-color);
        }

        a:hover {
            text-decoration: underline;
        }

        header {
            background-color: var(--white);
            padding: 15px 5%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            height: 70px;
        }

        nav a.nav-link {
            color: var(--text-color);
            margin-right: 20px;
            font-weight: 500;
        }

        .nav-button {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
        }

        .primary-nav-button {
            background-color: var(--primary-color);
            color: var(--white);
            margin-left: 10px;
        }

        main {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 20px;
        }

        .card-container {
            width: 100%;
            max-width: 450px;
        }

        .card {
            background-color: var(--white);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .card h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subtitle {
            color: var(--light-text-color);
            margin-bottom: 25px;
            font-size: 14px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            color: var(--text-color);
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .primary-button {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .primary-button:hover {
            background-color: #e57a31;
        }

        .account-link {
            margin-top: 20px;
            font-size: 14px;
            color: var(--light-text-color);
        }

        footer {
            background-color: var(--white);
            border-top: 1px solid var(--border-color);
            padding: 40px 5% 10px;
            color: var(--light-text-color);
            font-size: 14px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto 30px;
        }

        .footer-section {
            flex: 1 1 180px;
        }

        .footer-section h3 {
            color: var(--text-color);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 8px;
        }

        .footer-section ul a {
            color: var(--light-text-color);
        }

        .footer-section.motto h3 {
            font-weight: 500;
            line-height: 1.4;
            max-width: 180px;
        }

        .social-icons {
            margin-top: 15px;
        }

        .social-icons a {
            color: var(--light-text-color);
            font-size: 18px;
            margin-right: 15px;
        }

        .newsletter-form {
            display: flex;
            margin-top: 10px;
            max-width: 300px;
        }

        .newsletter-form input {
            flex-grow: 1;
            padding: 8px 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px 0 0 4px;
            font-size: 14px;
        }

        .secondary-button {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 8px 15px;
            border: none;
            border-radius: 0 4px 4px 0;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .copyright {
            padding: 10px 5%;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--light-text-color);
        }

        .vislly-link::before {
            content: "V";
            display: inline-block;
            color: var(--white);
            background-color: #5527a0;
            font-weight: bold;
            font-size: 10px;
            line-height: 15px;
            width: 15px;
            height: 15px;
            text-align: center;
            border-radius: 3px;
            margin-right: 4px;
            vertical-align: middle;
        }

        .vislly-link {
            color: #5527a0;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
        }

        .copyright p:first-child {
            font-size: 12px;
        }


        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
            }

            nav a.nav-link {
                margin-right: 10px;
            }

            .footer-content {
                flex-direction: column;
                padding: 0 20px;
            }

            .footer-section {
                flex: 1 1 100%;
                margin-bottom: 20px;
            }

            .footer-section.motto h3 {
                max-width: none;
            }

            .newsletter-form {
                max-width: 100%;
            }

            .copyright {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px 20px;
            }

            .copyright p:last-child {
                margin-top: 5px;
            }
        }
    
</style>
</head>

<body>
<header>
    <nav>
        <a href="index.php" class="nav-link">Home</a>
        <a href="#" class="nav-link">Community</a>
        <a href="login.php" class="nav-link">Log In</a>
        <a href="register.php" class="nav-button primary-nav-button">Register</a>
    </nav>
</header>

<main>
    <div class="card-container">
        <div class="card">
            <h2>Create an Account</h2>
            <p class="subtitle">Join the Women in Digital community.</p>

            <?php 
                if (!empty($register_error)) echo "<p style='color: red;'>$register_error</p>"; 
            ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" required placeholder="Choose a username">
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" name="email" required placeholder="Email">
                </div>

                <div class="form-group">
                    <label for="password">Create password</label>
                    <input type="password" name="password" required placeholder="********">
                </div>

                <button type="submit" class="primary-button" name="register">Register</button>

                <p class="account-link">Already have an account? <a href="login.php">Log in</a></p>
            </form>
        </div>
    </div>
</main>

</body>
</html>
