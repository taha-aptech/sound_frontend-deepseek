<?php
include 'dbconnect.php';
session_start();


if (isset($_POST['register'])) {
    $role = $_POST['role']; // admin or user
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($role === 'admin') {
        // Register as Admin
        $sql = "INSERT INTO admin (name, email, password, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['admin_id'] = $conn->insert_id;
            $_SESSION['role'] = 'admin';
            $_SESSION['name'] = $name;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Admin registration failed');</script>";
        }
    } else {
        // Register as User
        $sql = "INSERT INTO users (username, name, email, password, phone, address, created_at) VALUES (?, ?, ?, ?, '', '', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['role'] = 'user';
            $_SESSION['name'] = $name;
            header("Location: users_crud.php");
            exit();
        } else {
            echo "<script>alert('User registration failed');</script>";
        }
    }
}
?>


<?php
include 'header.php';
?>
    
      <div class="bg-light rounded my-5 p-4 col-md-6 offset-md-3">
    <h6 class="mb-4">Register</h6>
    <form method="POST">
      <div class="form-floating mb-3">
        <select name="role" class="form-select" id="role" required>
          <option value="" selected>-- Select Role --</option>
          <option value="user">User</option>
          <option value="admin">Admin</option>
        </select>
        <label for="role">Select Role</label>
      </div>

      <div class="form-floating mb-3">
        <input type="text" name="name" class="form-control" id="name" placeholder="Full Name" required>
        <label for="name">Full Name</label>
      </div>

      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
        <label for="email">Email</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
        <label for="password">Password</label>
      </div>

      <button name="register" class="btn btn-primary w-100">Register</button>
      <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
    </form>
  </div>


  <?php include 'footer.php'; ?>
