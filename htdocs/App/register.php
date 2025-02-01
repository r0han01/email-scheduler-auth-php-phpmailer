<?php
// Include database connection
include 'db_connect.php'; // Replace with your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form inputs
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Check if the username already exists
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // If username exists, show error message
        echo "<div class='alert error'>Username already taken. Please choose a different username.</div>";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the query to insert the new user
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            // Success message
            echo "<div class='alert success'>New user registered successfully!</div>";
            
            // Redirect to login page after successful registration
            header("Location: login.php"); // Redirect to the login page
            exit; // Ensure no further code is executed
        } else {
            // Error message
            echo "<div class='alert error'>Error: " . $stmt->error . "</div>";
        }

        // Close the statement
        $stmt->close();
    }

    // Close the check statement
    $check_stmt->close();

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('https://images.unsplash.com/photo-1477346611705-65d1883cee1e?dpr=0.800000011920929&auto=format&fit=crop&w=1199&h=800&q=80&cs=tinysrgb&crop=');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Montserrat', sans-serif;
            color: #fff;
            padding: 0 10px; /* Ensure padding is on all sides */
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            backdrop-filter: blur(15px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%; /* Take the full width */
            max-width: 1000px; /* Set a maximum width for large screens */
            margin: 0 auto; /* Center the form container */
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 700;
        }

        .form {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .form__field {
            position: relative;
            margin-bottom: 1.5rem;
            width: 100%;
        }

        label {
            font-size: 14px;
            color: #fff;
            position: absolute;
            top: 10px;
            left: 10px;
            pointer-events: none;
            transition: 0.3s;
        }

        .form__input {
            width: 100%;
            padding: 1rem;
            background-color: rgba(255, 255, 255, 0.3);
            color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: background-color 0.3s;
            backdrop-filter: blur(10px);
            font-weight: bold;
        }

        .form__input:focus {
            background-color: rgba(255, 255, 255, 0.4);
            outline: none;
        }

        .form__input:focus + label,
        .form__input:not(:placeholder-shown) + label {
            top: -10px;
            font-size: 12px;
        }

        .form__submit-button {
            padding: 18px 30px;
            font-weight: 700;
            background: linear-gradient(135deg, rgba(234, 76, 136, 1) 0%, rgba(212, 65, 121, 1) 100%);
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            text-transform: uppercase;
            font-size: 14px;
            width: 100%; /* Full width button */
        }

        .form__submit-button:hover {
            background: linear-gradient(135deg, rgba(212, 65, 121, 1) 0%, rgba(234, 76, 136, 1) 100%);
            transform: scale(1.01);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .form__submit-button:active {
            transform: scale(1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            border-radius: 8px;
            text-align: center;
        }

        .success {
            background-color: rgba(0, 255, 0, 0.1);
            color: #007a00;
            border: 1px solid #007a00;
        }

        .error {
            background-color: rgba(255, 0, 0, 0.1);
            color: #a60000;
            border: 1px solid #a60000;
        }

        .text-center {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #eee;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Register</h2>

        <form action="register.php" method="POST" class="form">
            <div class="form__field">
                <input type="text" id="username" name="username" class="form__input" placeholder=" " required>
                <label for="username">Username</label>
            </div>

            <div class="form__field">
                <input type="email" id="email" name="email" class="form__input" placeholder=" " required>
                <label for="email">Email</label>
            </div>

            <div class="form__field">
                <input type="password" id="password" name="password" class="form__input" placeholder=" " required>
                <label for="password">Password</label>
            </div>

            <div class="form__field">
                <button type="submit" class="form__submit-button">Register</button>
            </div>
        </form>

        <p class="text-center">Already have an account? <a href="login.php">Login now</a></p>
    </div>

</body>
</html>
