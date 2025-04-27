<!DOCTYPE html>
<html>
<head>
    <title>Xác thực tài khoản</title>
</head>
<body>
    <h2>Xác thực tài khoản</h2>
    <p>Xin chào,</p>
    <p>Cảm ơn bạn đã đăng ký tài khoản. Vui lòng click vào link bên dưới để xác thực tài khoản của bạn:</p>
    <p>
        <a href="{{ url('/verify-email/' . $token) }}">Xác thực tài khoản</a>
    </p>
    <p>Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.</p>
    <p>Trân trọng,</p>
    <p>HR Management Team</p>
</body>
</html>
