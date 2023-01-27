<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <link rel="icon" href="asset/img/LogoBangsa.png" />
    <meta name="author" content="phpmu.com">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/asset/admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="<?= base_url(); ?>/asset/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/asset/admin/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/asset/admin/plugins/iCheck/square/blue.css">
    <!-- Custom styles for this template-->
    <link href="<?= base_url(); ?>asset/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>asset/css/sb-admin-2.css" rel="stylesheet">
  </head>
  <body class="bg-light">
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-login-image">
                        <img class="img-login" src="asset/img/logologin.png" alt="">
                    </div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h2 text-gray-900 mb-4">Silahkan login akun anda</h1>
                            </div>
                            <div class="card-image">
                            <div class="media-sosial">
                                <img src="asset/img/fc.jpg" />
                                <img src="asset/img/go.png" />
                                <img src="asset/img/tw.png" />
                            </div>
                            <p class="text-email">atau daftar menggunakan akun email</p>
                            <?php 
                                echo $this->session->flashdata('message');
                                echo form_open($this->uri->segment(1)); 
                            ?><br><br>
                            </div>  
                            <form method="POST" action="" class="user" autocomplete="off">
                                <div class="form-group has-feedback">
                                    <i class="fa fa-user"></i>
                                    <input autocomplete="off" name="a" type="text" class="form-control form-control-user" id="exampleInputUsername"  placeholder="Username">
                                </div>
                                <div class="form-group has-feedback">
                                <i class="fa fa-lock"></i>
                                    <input autocomplete="off" name="b" type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                </div>
                                <button type="submit" name="submit" class="button" value='Sign In'>Login</button>
                            </form>
                            <div class="text-center1">
                              <a href="<?= base_url('main/reset_password'); ?>" class="small">Lupa Password?</a>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url(); ?>/asset/vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url(); ?>/asset/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url(); ?>/asset/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url(); ?>/asset/js/sb-admin-2.min.js"></script>

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url(); ?>/asset/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url(); ?>/asset/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>/asset/admin/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>

        
  </body>
</html>
