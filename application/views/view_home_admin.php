
  <a style='color:#000' href='<?php echo base_url().$this->uri->segment(1); ?>/produk'>
  <div class="col-md-4 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fa fa-file"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Produk  </span>
        <?php $jmlb = $this->model_app->view('rb_produk')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmlb; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <a style='color:#000' href='<?php echo base_url().$this->uri->segment(1); ?>/konsumen'>
  <div class="col-md-4 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Users</span>
        <?php $jmld = $this->model_app->view('rb_konsumen')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmld; ?></span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  </a>

  <a style='color:#000' href='<?php echo base_url().$this->uri->segment(1); ?>/kategori_produk'>
  <div class="col-md-4 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-aqua"><i class="fa fa-book"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Kategori</span>
        <?php $jmla = $this->model_app->view('rb_kategori_produk')->num_rows(); ?>
        <span class="info-box-number"><?php echo $jmla; ?></span>
      </div>
    </div>
  </div>
  </a>


<section class="col-lg-6 connectedSortable">
    <?php include "grafik.php"; ?>
</section><!-- right col -->

        <div class="col-xs-12" style="margin-top: 50px;">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Jumlah Unggahan</h3>&ensp;:&ensp;
                    <?php $jmlb = $this->model_app->view('rb_produk')->num_rows(); ?>
                    <span class="info-box-number" style="margin-left: 155px; margin-top: -23px;"><?php echo $jmlb; ?></span>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Nama Produk</th>
                        <th>Modal</th>
                        <th>Reseller</th>
                        <th>Konsumen</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Berat</th>
                        <th style='width:50px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <td>1</td>
                      <td>Produk</td>
                      <td>modal</td>
                      <td>reseller</td>
                      <td>konsumen</td>
                      <td>stok</td>
                      <td>satuan</td>
                      <td>berat</td>
                      <td><span class='glyphicon glyphicon-edit' style='display: flex; justify-content: center;'></span></td>
                  </tbody>
                </table>
              </div>
              

<script>
  $(document).ready(function () {
 
 $('#example').DataTable(
       { language: {
   searchPlaceholder: "Search records",
   search: "",
 }
});
   var table = $('#doors').DataTable();
$('#example tbody').off('click');
$('#example tbody').on('click', 'img.icon-delete', function () {
   var data = table.row( this ).data();
   // alert( 'You clicked on '+data[0]+'\'s row' );
   alert("table click"+data[0]);

   $('#example').DataTable( {
       fixedHeader: true
   });

   $('#example').DataTable({
   // Enable the vertical scrolling
   // of data in DataTable to 200px
   scrollY: '200',
           scroller: true
 });
   $('#example').dataTable( {
   "pageLength": 50
} );
$('#example').dataTable( {
   lengthMenu: [ [10, 25, 50, -1], [25, 50, 75, "All"] ]
} );

});
});
</script>