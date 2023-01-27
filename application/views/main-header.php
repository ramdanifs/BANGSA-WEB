<style type="text/css">
  .sekolah{
    float: left;
    background-color: transparent;
    background-image: none;
    padding: 15px 15px;
    font-family: fontAwesome;
    color:#fff;
  }
  .sekolah:hover{
    color:#fff;
  }

  .img-logo{
    width: 70%;
    margin-top: 15px;
  }

  @media only screen and (max-width: 576px){
    .img-logo{
      width: 30%;
      margin-top: 3px;
    }
  }
</style>
        <!-- Logo -->
        <a href="<?php echo base_url().$this->uri->segment(1); ?>/home" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="<?php echo base_url(); ?>/asset/img/logo-sidebar.png" alt="logo bangsa" class="img-logo"></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" style="background-color: #E8F9FF; padding: 1vw;" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" style="color: #6e6e68;">
            <span class="sr-only">Toggle navigation</span>
          </a>  
          <section class="content-header">
          <h1 style="margin-top: -4px;"> Dashboard </h1>
        </section>

        </nav>