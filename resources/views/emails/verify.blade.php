<!DOCTYPE html>
<html>
<head>
    <title>Xác thực tài khoản</title>
</head>
<body>
    <h2>Xác thực tài khoản</h2>
    <p>Xin chào,</p>
    <p>Vui lòng nhấp vào liên kết bên dưới để xác thực tài khoản của bạn:</p>
    <a href="{{ url('/verify-email/' . $token) }}">Xác thực tài khoản</a>
    <p>Nếu bạn không yêu cầu xác thực này, vui lòng bỏ qua email này.</p>
    <p>Trân trọng,</p>
    <p>Đội ngũ HR Management</p>
</body>
</html>
