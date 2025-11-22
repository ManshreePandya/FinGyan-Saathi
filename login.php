<?php

require_once("config.php");

// Initialize message
$registration_message = "";

if(isset($_POST['register'])){

    // filter data yang diinputkan
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    // Check if passwords match
    if ($_POST["password"] !== $_POST["confirm_password"]) {
        $registration_message = "<p style='color: red;'>Error: Passwords do not match.</p>";
    } else {
        // enkripsi password
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // menyiapkan query (Using 'name' for both 'name' and 'username')
        $sql = "INSERT INTO users (name, username, email, password) 
                VALUES (:name, :username, :email, :password)";
        $stmt = $db->prepare($sql);

        // bind parameter ke query
        $params = array(
            ":name" => $name,
            ":username" => $name, // Using 'name' for 'username'
            ":password" => $password,
            ":email" => $email
        );

        // eksekusi query untuk menyimpan ke database
        $saved = $stmt->execute($params);

        // jika query simpan berhasil, maka user sudah terdaftar
        if($saved) {
             header("Location: login.php");
             exit; // Always exit after a header redirect
        } else {
            // Handle save failure
            $registration_message = "<p style='color: red;'>Error: Registration failed. Please try again.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* CSS STYLES (Provided in original response) */
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
                <p class="subtitle">Enter your details to join Women in Digital.</p>
                
                <?php echo $registration_message; ?>

                <form action="" method="POST">
                    
                    <div class="form-group">
                        <label for="reg-name">Name</label>
                        <input type="text" id="reg-name" name="name" placeholder="Your Full Name" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-email">Email</label>
                        <input type="email" id="reg-email" name="email" placeholder="email@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-password">Password</label>
                        <input type="password" id="reg-password" name="password" placeholder="**********" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-confirm-password">Confirm Password</label>
                        <input type="password" id="reg-confirm-password" name="confirm_password" placeholder="**********" required>
                    </div>
                    
                    <button type="submit" class="primary-button" name="register">Register</button>
                    <p class="account-link">Already have an account? <a href="login.php">Log in</a></p>
                </form>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section motto">
                <h3>Empowering women through digital literacy.</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3>Company</h3>
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Resources</h3>
                <ul>
                    <li><a href="#">Digital Literacy</a></li>
                    <li><a href="#">Workshops</a></li>
                    <li><a href="#">Articles</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section newsletter">
                <h3>Join Our Newsletter</h3>
                <p>Stay updated with our latest resources and events.</p>
                <div class="newsletter-form">
                    <input type="email" placeholder="Your email address">
                    <button class="secondary-button">Subscribe &gt;</button>
                </div>
            </div>
        </div>

        <div class="copyright">
            <p>&copy; 2025 Women in Digital. All rights reserved.</p>
            <p>Made with <a href="#" class="vislly-link">Vislly</a></p>
        </div>
    </footer>
</body>
</html>
