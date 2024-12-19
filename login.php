<?php
session_start();

// الاتصال بقاعدة البيانات
$host = "localhost"; // استبدل بـ اسم السيرفر الخاص بك
$db_name = "sourcecodester_hoteldb"; // اسم قاعدة البيانات
$username = "root"; // اسم المستخدم الخاص بقاعدة البيانات
$password = ""; // كلمة مرور قاعدة البيانات

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// التحقق من تقديم النموذج
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // التحقق من صحة الإدخال
    if (empty($email) || empty($password)) {
        die("Both email and password are required.");
    }

    // التحقق من المستخدم
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // نجاح تسجيل الدخول
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        header("Location: dashboard.php"); // إعادة التوجيه إلى صفحة المستخدم
        exit();
    } else {
        // فشل تسجيل الدخول
        echo "<script>alert('Invalid email or password.');</script>";
        
    }
}
?>
