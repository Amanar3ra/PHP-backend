<?php

// Start the session
session_start();
require_once 'config.php';

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Check if id parameter exists
if(empty($_GET["id"])) {
    header("location: dashboard.php");
    exit;
}

$id = $_GET["id"];
$username = $name = $email = $role = "";
$username_err = $name_err = $email_err = "";

// Get user data
$sql = "SELECT username, name, email, role FROM users WHERE id = :id";
if($stmt = $pdo->prepare($sql)) {
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    
    if($stmt->execute()) {
        if($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            $username = $row["username"];
            $name = $row["name"];
            $email = $row["email"];
            $role = $row["role"];
        } else {
            header("location: dashboard.php");
            exit;
        }
    } else {
        echo "Something went wrong. Please try again later.";
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate name
    if(empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name.";     
    } else {
        $name = trim($_POST["name"]);
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";     
    } else {
        $email = trim($_POST["email"]);
    }
    
    // Set role
    $role = !empty($_POST["role"]) ? trim($_POST["role"]) : "user";
    
    // Check input errors before updating in database
    if(empty($name_err) && empty($email_err)) {
        
        // Prepare an update statement
        $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
         
        if($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":role", $param_role, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_role = $role;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()) {
                header("location: dashboard.php?updated=1");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }

            unset($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        h2 {
            color: #333;
            margin: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }
        .invalid-feedback {
            color: #f44336;
            font-size: 14px;
            margin-top: 5px;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .action-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .readonly {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h2>Edit User</h2>
        </header>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>" readonly class="readonly">
            </div>    
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="<?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role">
                    <option value="user" <?php if($role == "user") echo "selected"; ?>>User</option>
                    <option value="admin" <?php if($role == "admin") echo "selected"; ?>>Admin</option>
                </select>
            </div>
            <div class="action-buttons">
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn">Update User</button>
            </div>
        </form>
    </div>    
</body>
</html>