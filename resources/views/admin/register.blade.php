<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Đăng ký người dùng</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('adminlte_3_0_4/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('adminlte_3_0_4/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('adminlte_3_0_4/dist/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Đăng ký người dùng</p>

      <form action="" method="post">
          @csrf
        <div class="row p-0">
            <div class="col-md-7">
                <div class="input-group">
                    <input name="last_name" type="text" class="form-control" placeholder="Họ">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                @if ($errors->first('last_name'))
                    <p class="text-danger">{{ $errors->first('last_name') }}</p>
                @endif
            </div>
            <div class="col-md-5 p-0">
                <div class="input-group">
                    <input name="first_name" type="text" class="form-control" placeholder="Tên">
                    <div class="input-group-append">
                        <div class="input-group-text">
                        <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                @if ($errors->first('first_name'))
                    <p class="text-danger">{{ $errors->first('first_name') }}</p>
                @endif
            </div>
        </div>
            
        <!-- </div> -->
        <div class="input-group mt-3">
          <input name="email" type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        @if ($errors->first('email'))
            <p class="text-danger">{{ $errors->first('email') }}</p>
        @endif
        <div class="input-group mt-3">
          <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @if ($errors->first('password'))
            <p class="text-danger">{{ $errors->first('password') }}</p>
        @endif
        <div class="input-group mt-3">
          <input name="retype_password" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @if ($errors->first('retype_password'))
            <p class="text-danger">{{ $errors->first('retype_password') }}</p>
        @endif
        <div class="row mt-3">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               Tôi chấp nhận <a href="#">điều khoản</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center">
        <p>- HOẶC -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Đăng nhập bằng Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Đăng nhập bằng Google+
        </a>
      </div>

      <a href="login.html" class="text-center">Tôi đã có tài khoản!</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="{{asset('adminlte_3_0_4/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('adminlte_3_0_4/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('adminlte_3_0_4/dist/js/adminlte.min.js')}}"></script>
</body>
</html>
