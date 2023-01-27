            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Kategori Produk</h3>
                  <a style="border-radius: 10px; width: 100px;" class='pull-right btn btn-primary btn-sm' href='<?php echo base_url(); ?>main/tambah_kategori_produk'><i class="fa fa-plus" aria-hidden="true"></i></a>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Nama Kategori Produk</th>
                        <th style='width:70px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record as $row){
                    echo "<tr><td>$no</td>
                              <td>$row[nama_kategori]</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='".base_url()."main/edit_kategori_produk/$row[id_kategori_produk]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url()."main/delete_kategori_produk/$row[id_kategori_produk]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>

              <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">