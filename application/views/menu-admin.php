     <style>

      .sidebar-menu{
        font-weight: medium;
        color: white;
      }

      .pull-left{
        font-weight: bold;
        color: black;
      }

      .span{
        color: white;
      }

      .span:hover{
        color: black;
      }

      #i{
        color: white;
      }

      #i:hover{
        color: black;
      }

      .user-panel{
        border: 1px solid white;
        border-top: none;
        border-left: none;
        border-right: none;
        margin-top: 35px;
        padding-bottom: 30px;
      }

      #futer #bottom{
        position: absolute;
        bottom: 0;
      }

      @media only screen and (max-width: 576px){
        
      }

     </style>

     <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
            <?php $usr = $this->model_app->view_where('users', array('username'=> $this->session->username))->row_array();
                  if (trim($usr['foto'])==''){ $foto = 'blank.png'; }else{ $foto = $usr['foto']; } ?>
            <img src="<?php echo base_url(); ?>/asset/foto_user/<?php echo $foto; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info" style="color: white;">
              <?php echo "<p>$usr[nama_lengkap]</p>"; ?>
              <a class="span"><i class="fa fa-circle text-success" style="color: green;"></i> Online</a>
            </div>
          </div>

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li><a href="<?php echo base_url().$this->uri->segment(1); ?>/home"><i id="i" class="fa fa-dashboard"></i> <span class="span">Dashboard</span></a></li>
                <li class="treeview">
                  <a href="#"><i id="i" class="fa fa-user"></i> <span class="span">Pengguna</span><i class="fa fa-angle-left pull-right"></i></a>
                  <ul class="treeview-menu">
                  <?php 
                        $cek=$this->model_app->umenu_akses("konsumen",$this->session->id_session);
                        if($cek==1 OR $this->session->level=='admin'){
                          echo "<li><a class='span' href='".base_url().$this->uri->segment(1)."/konsumen'><i id='i' class='fa fa-circle-o'></i>Data Pengguna</a></li>";
                        }
                        ?>
                    </ul>
                </li>
            <li class="treeview">
              <a href="#"><i id="i" class="fa fa-shopping-cart"></i> <span class="span">Toko</span><i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
              <?php 
                    $cek=$this->model_app->umenu_akses("kategori_produk",$this->session->id_session);
                    if($cek==1 OR $this->session->level=='admin'){
                      echo "<li><a class='span' href='".base_url().$this->uri->segment(1)."/kategori_produk'><i id='i' class='fa fa-circle-o'></i>Detail Informasi</a></li>";
                    }

                    $cek=$this->model_app->umenu_akses("produk",$this->session->id_session);
                    if($cek==1 OR $this->session->level=='admin'){
                      echo "<li><a class='span' href='".base_url().$this->uri->segment(1)."/produk'><i id='i' class='fa fa-circle-o'></i> Data Produk</a></li>";
                    }

                    ?>
                </ul>
                </li>
                    
                 </ul>
                 
        </section>
        
        <section class="sidebar" id="futer">
              <ul class="sidebar-menu" id="bottom">
                <li><a href="<?php echo base_url().$this->uri->segment(1); ?>/edit_manajemenuser/<?php echo $this->session->username; ?>"><i id="i" class="fa fa-edit"></i> <span class="span">Edit Profile</span></a></li>
                <li><a href="<?php echo base_url().$this->uri->segment(1); ?>/logout"><i id="i" class="fa fa-power-off"></i> <span class="span">Logout</span></a></li>
              </ul>
        </section>
