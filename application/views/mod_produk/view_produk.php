            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Jumlah Unggahan</h3>&ensp;:&ensp;
                    <?php $jmlb = $this->model_app->view('rb_produk')->num_rows(); ?>
                    <span class="info-box-number" style="margin-left: 155px; margin-top: -23px;"><?php echo $jmlb; ?></span>
                  <!-- <a class='pull-right btn btn-primary btn-sm' href='<?php echo base_url(); ?>main/tambah_produk'>Tambahkan Data</a> -->
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
                  <?php 
                    $no = 1;
                    foreach ($record as $row){
                      $res = $this->db->query("SELECT * FROM rb_reseller a JOIN rb_kota b ON a.kota_id=b.kota_id where a.id_reseller='$row[id_reseller]'")->row_array();
                      if ($row['id_reseller']=='0'){
                        $jual = $this->model_reseller->jual($row['id_produk'])->row_array();
                        $beli = $this->model_reseller->beli($row['id_produk'])->row_array();
                      }else{ 
                        $jual = $this->model_reseller->jual_reseller($row['id_reseller'],$row['id_produk'])->row_array();
                        $beli = $this->model_reseller->beli_reseller($row['id_reseller'],$row['id_produk'])->row_array();
                      }
                    echo "<tr><td>$no</td>
                              <td>$row[nama_produk] 
                              <td>".rupiah($row['harga_beli'])."</td>
                              <td>".rupiah($row['harga_reseller'])."</td>
                              <td>".rupiah($row['harga_konsumen'])."</td>
                              <td>".($beli['beli']-$jual['jual'])."</td>
                              <td>$row[satuan]</td>
                              <td>$row[berat] G</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url()."main/edit_produk/$row[id_produk]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url()."main/delete_produk/$row[id_produk]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
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