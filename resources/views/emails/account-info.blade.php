<!DOCTYPE html>
<html>
<head>
    <title>Thông tin tài khoản</title>
</head>
<body>
    <h2>Thông tin tài khoản của bạn</h2>
    <p>Xin chào {{ $user->name }},</p>
    <p>Dưới đây là thông tin đăng nhập của bạn:</p>
    <ul>
        <li>Email: {{ $user->email }}</li>
        <li>Mật khẩu: {{ $password }}</li>
    </ul>
    <p>Vui lòng đăng nhập và đổi mật khẩu ngay sau khi nhận được email này.</p>
    <p>Trân trọng,</p>
    <p>Đội ngũ HR Management</p>
</body>
</html>
