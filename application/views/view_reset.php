<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $title; ?></title>
    <meta name="author" content="phpmu.com">
    <link rel="icon" href="../asset/img/LogoBangsa.png" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/asset/admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/asset/admin/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/asset/admin/plugins/iCheck/square/blue.css">
    <!-- Custom styles for this template-->
    <link href="<?= base_url(); ?>asset/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>asset/css/sb-admin-password-2.css" rel="stylesheet">
    <script type="text/javascript">
      function nospaces(t){
          if(t.value.match(/\s/g)){
              alert('Maaf, Password Tidak Boleh Menggunakan Spasi,..');
              t.value=t.value.replace(/\s/g,'');
          }
      }
    </script>
  </head>

  <body>
                <div class="row">
                    <div class="col-lg-6 d-none d-lg-block bg-password-image">
                        <img class="img-password" src="../asset/img/logologin.png" alt="">
                    </div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h2 text-gray-900 mb-4">Reset Password</h1>
                            </div>
                            <div class="card-image">
                            <?php 
                                if ($this->input->post('id_session')!=''){
                                echo "<div class='alert alert-warning'><center>$title</center></div>";
                              }
                                echo form_open($this->uri->segment(1).'/reset_password'); 
                            ?><br><br>
                            </div>  
                            <form method="POST" action="" class="user" autocomplete="off">
                            <div class="form-group has-feedback">
                                    <i class="fa fa-user"></i>
                                    <input autocomplate="off" type="password" class="form-control form-control-user" name="a" id="exampleInputUsername" placeholder="Email" onkeyup="nospaces(this)" required>
                            </div>
                            <!-- <div class="form-group has-feedback">
                                    <i class="fa fa-lock"></i>
                                    <input autocomplate="off" type="password" class="form-control form-control-user" name="b" id="exampleInputUsername" placeholder="Ulangi Sekali Lagi" onkeyup="nospaces(this)" required>
                                    <input type="hidden" name='id_session' value='<?php echo $this->session->id_session; ?>'>
                            </div> -->
                            <div class="tombol">
                              <div class="col-xs-12">
                                <button name='submit' type="submit" class="btn">Kirim</button>
                                <div class="text-center1">
                                <a href='<?php echo base_url(); ?>' class="small">Kembali Login?</a>
                                </div>
                              </div><!-- /.col -->
                            </div>
                            </form>
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
