<p>Xin chào {{ $user->username }},</p>
<p>Tài khoản nhân viên của bạn đã được tạo:</p>
<ul>
    <li>Email đăng nhập: <strong>{{ $user->email }}</strong></li>
    <li>Mật khẩu: <strong>{{ $password }}</strong></li>
</ul>
<p>Vui lòng đăng nhập và đổi mật khẩu sau lần đăng nhập đầu tiên.</p>
<p>Link đăng nhập: <a href="{{ url('/login') }}">{{ url('/login') }}</a></p>
<p>Trân trọng,</p>
<p>HR Management Team</p>