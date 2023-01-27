<?php
/*
-- ---------------------------------------------------------------
-- MARKETPLACE MULTI BUYER MULTI SELLER + SUPPORT RESELLER SYSTEM
-- CREATED BY : ROBBY PRIHANDAYA
-- COPYRIGHT  : Copyright (c) 2018 - 2019, PHPMU.COM. (https://phpmu.com/)
-- LICENSE    : http://opensource.org/licenses/MIT  MIT License
-- CREATED ON : 2019-03-26
-- UPDATED ON : 2019-03-27
-- ---------------------------------------------------------------
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends CI_Controller {
	function index(){
		if (isset($_POST['submit'])){
            if ($this->input->post()){
                $username = $this->input->post('a');
    			$password = hash("sha512", md5($this->input->post('b')));
    			$cek = $this->model_app->cek_login($username,$password,'users');
    		    $row = $cek->row_array();
    		    $total = $cek->num_rows();
    			if ($total > 0){
    				$this->session->set_userdata('upload_image_file_manager',true);
    				$this->session->set_userdata(array('username'=>$row['username'],
    								   'level'=>$row['level'],
                                       'id_session'=>$row['id_session']));
    				redirect($this->uri->segment(1).'main/home');
    			}else{
                    echo $this->session->set_flashdata('message', '<div class="alert alert-danger"><center>Username dan Password Salah!!</center></div>');
    				redirect($this->uri->segment(1));
    			}
            }
		}else{
            if ($this->session->level!=''){
              redirect($this->uri->segment(1).'main/home');
            }
            else{
    			$data['title'] = 'Administrator &rsaquo; Log In';
    			$this->load->view('/view_login',$data);
            }
		}
	}

    function reset_password(){
        if (isset($_POST['submit'])){
            $usr = $this->model_app->edit('admin', array('id_session' => $this->input->post('id_session')));
            if ($usr->num_rows()>=1){
                if ($this->input->post('a')==$this->input->post('b')){
                    $data = array('password'=>hash("sha512", md5($this->input->post('a'))));
                    $where = array('id_session' => $this->input->post('id_session'));
                    $this->model_app->update('admin', $data, $where);

                    $row = $usr->row_array();
                    $this->session->set_userdata('upload_image_file_manager',true);
                    $this->session->set_userdata(array('username'=>$row['username'],
                                       'level'=>$row['level'],
                                       'id_session'=>$row['id_session']));
                    redirect($this->uri->segment(1).'/home');
                }else{
                    $data['title'] = 'Password Tidak sama!';
                    $this->load->view('/view_reset',$data);
                }
            }else{
                $data['title'] = 'Terjadi Kesalahan!';
                $this->load->view('/view_reset',$data);
            }
        }else{
            $this->session->set_userdata(array('id_session'=>$this->uri->segment(3)));
            $data['title'] = 'Reset Password';
            $this->load->view('/view_reset',$data);
        }
    }

    function lupapassword(){
        if (isset($_POST['lupa'])){
            $email = strip_tags($this->input->post('email'));
            $cekemail = $this->model_app->edit('users', array('email' => $email))->num_rows();
            if ($cekemail <= 0){
                $data['title'] = 'Alamat email tidak ditemukan';
                $this->load->view('/view_login',$data);
            }else{
                $iden = $this->model_app->edit('identitas', array('id_identitas' => 1))->row_array();
                $usr = $this->model_app->edit('users', array('email' => $email))->row_array();
                $this->load->library('email');

                $tgl = date("d-m-Y H:i:s");
                $subject      = 'Lupa Password ...';
                $message      = "<html><body>
                                    <table style='margin-left:25px'>
                                        <tr><td>Halo $usr[nama_lengkap],<br>
                                        Seseorang baru saja meminta untuk mengatur ulang kata sandi Anda di <span style='color:red'>$iden[url]</span>.<br>
                                        Klik di sini untuk mengganti kata sandi Anda.<br>
                                        Atau Anda dapat copas (Copy Paste) url dibawah ini ke address Bar Browser anda :<br>
                                        <a href='".base_url().$this->uri->segment(1)."/reset_password/$usr[id_session]'>".base_url().$this->uri->segment(1)."/reset_password/$usr[id_session]</a><br><br>

                                        Tidak meminta penggantian ini?<br>
                                        Jika Anda tidak meminta kata sandi baru, segera beri tahu kami.<br>
                                        Email. $iden[email], No Telp. $iden[no_telp]</td></tr>
                                    </table>
                                </body></html> \n";
                
                $this->email->from($iden['email'], $iden['nama_website']);
                $this->email->to($usr['email']);
                $this->email->cc('');
                $this->email->bcc('');

                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->set_mailtype("html");
                $this->email->send();
                
                $config['protocol'] = 'sendmail';
                $config['mailpath'] = '/usr/sbin/sendmail';
                $config['charset'] = 'utf-8';
                $config['wordwrap'] = TRUE;
                $config['mailtype'] = 'html';
                $this->email->initialize($config);

                $data['title'] = 'Password terkirim ke '.$usr['email'];
                $this->load->view('/view_login',$data);
            }
        }else{
            redirect($this->uri->segment(1));
        }
    }

	function home(){
        if ($this->session->level=='admin'){
		  $this->template->load('/template','/view_home_admin');
        }else{
          $data['users'] = $this->model_app->view_where('users',array('username'=>$this->session->username))->row_array();
          $data['modul'] = $this->model_app->view_join_one('users','users_modul','id_session','id_umod','DESC');
          $this->template->load('/template','/view_home_users',$data);
        }
	}

	// Controller Modul User

	function manajemenuser(){
		cek_session_akses('manajemenuser',$this->session->id_session);
		$data['record'] = $this->model_app->view_ordering('users','username','DESC');
		$this->template->load('/template','/mod_users/view_users',$data);
	}

	function tambah_manajemenuser(){
		cek_session_akses('manajemenuser',$this->session->id_session);
		$id = $this->session->username;
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_user/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
            $config['max_size'] = '1000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('f');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'password'=>hash("sha512", md5($this->input->post('b'))),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'level'=>$this->db->escape_str($this->input->post('g')),
                                    'blokir'=>'N',
                                    'id_session'=>md5($this->input->post('a')).'-'.date('YmdHis'));
            }else{
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'password'=>hash("sha512", md5($this->input->post('b'))),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'foto'=>$hasil['file_name'],
                                    'level'=>$this->db->escape_str($this->input->post('g')),
                                    'blokir'=>'N',
                                    'id_session'=>md5($this->input->post('a')).'-'.date('YmdHis'));
            }
            $this->model_app->insert('users',$data);

              $mod=count($this->input->post('modul'));
              $modul=$this->input->post('modul');
              $sess = md5($this->input->post('a')).'-'.date('YmdHis');
              for($i=0;$i<$mod;$i++){
                $datam = array('id_session'=>$sess,
                              'id_modul'=>$modul[$i]);
                $this->model_app->insert('users_modul',$datam);
              }

			redirect($this->uri->segment(1).'/edit_manajemenuser/'.$this->input->post('a'));
		}else{
            $proses = $this->model_app->view_where_ordering('modul', array('publish' => 'Y','status' => 'user'), 'id_modul','DESC');
            $data = array('record' => $proses);
			$this->template->load('/template','/mod_users/view_users_tambah',$data);
		}
	}

	function edit_manajemenuser(){
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/foto_user/';
            $config['allowed_types'] = 'gif|jpg|png|JPG|JPEG';
            $config['max_size'] = '1000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('f');
            $hasil=$this->upload->data();
            if ($hasil['file_name']=='' AND $this->input->post('b') ==''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')));
            }elseif ($hasil['file_name']!='' AND $this->input->post('b') ==''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'foto'=>$hasil['file_name']);
            }elseif ($hasil['file_name']=='' AND $this->input->post('b') !=''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'password'=>hash("sha512", md5($this->input->post('b'))),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')));
            }elseif ($hasil['file_name']!='' AND $this->input->post('b') !=''){
                    $data = array('username'=>$this->db->escape_str($this->input->post('a')),
                                    'password'=>hash("sha512", md5($this->input->post('b'))),
                                    'nama_lengkap'=>$this->db->escape_str($this->input->post('c')),
                                    'email'=>$this->db->escape_str($this->input->post('d')),
                                    'no_telp'=>$this->db->escape_str($this->input->post('e')),
                                    'foto'=>$hasil['file_name']);
            }
            $where = array('username' => $this->input->post('id'));
            $this->model_app->update('users', $data, $where);

              $mod=count($this->input->post('modul'));
              $modul=$this->input->post('modul');
              for($i=0;$i<$mod;$i++){
                $datam = array('id_session'=>$this->input->post('ids'),
                              'id_modul'=>$modul[$i]);
                $this->model_app->insert('users_modul',$datam);
              }

			redirect($this->uri->segment(1).'/edit_manajemenuser/'.$this->input->post('id'));
		}else{
            if ($this->session->username==$this->uri->segment(3) OR $this->session->level=='admin'){
                $proses = $this->model_app->edit('users', array('username' => $id))->row_array();
                $akses = $this->model_app->view_join_where('users_modul','modul','id_modul', array('id_session' => $proses['id_session']),'id_umod','DESC');
                $modul = $this->model_app->view_where_ordering('modul', array('publish' => 'Y','status' => 'user'), 'id_modul','DESC');
                $data = array('rows' => $proses, 'record' => $modul, 'akses' => $akses);
    			$this->template->load('/template','/mod_users/view_users_edit',$data);
            }else{
                redirect($this->uri->segment(1).'/edit_manajemenuser/'.$this->session->username);
            }
		}
	}

	function delete_manajemenuser(){
        cek_session_akses('manajemenuser',$this->session->id_session);
		$id = array('username' => $this->uri->segment(3));
        $this->model_app->delete('users',$id);
		redirect($this->uri->segment(1).'/manajemenuser');
	}

    function delete_akses(){
        cek_session_admin();
        $id = array('id_umod' => $this->uri->segment(3));
        $this->model_app->delete('users_modul',$id);
        redirect($this->uri->segment(1).'/edit_manajemenuser/'.$this->uri->segment(4));
    }

	

	// Controller Modul Modul

	function manajemenmodul(){
		cek_session_akses('manajemenmodul',$this->session->id_session);
        if ($this->session->level=='admin'){
            $data['record'] = $this->model_app->view_ordering('modul','id_modul','DESC');
        }else{
            $data['record'] = $this->model_app->view_where_ordering('modul',array('username'=>$this->session->username),'id_modul','DESC');
        }
		$this->template->load('/template','/mod_modul/view_modul',$data);
	}

	function tambah_manajemenmodul(){
		cek_session_akses('manajemenmodul',$this->session->id_session);
		if (isset($_POST['submit'])){
			$data = array('nama_modul'=>$this->db->escape_str($this->input->post('a')),
                        'username'=>$this->session->username,
                        'link'=>$this->db->escape_str($this->input->post('b')),
                        'static_content'=>'',
                        'gambar'=>'',
                        'publish'=>$this->db->escape_str($this->input->post('c')),
                        'status'=>$this->db->escape_str($this->input->post('e')),
                        'aktif'=>$this->db->escape_str($this->input->post('d')),
                        'urutan'=>'0',
                        'link_seo'=>'');
            $this->model_app->insert('modul',$data);
			redirect($this->uri->segment(1).'/manajemenmodul');
		}else{
			$this->template->load('/template','/mod_modul/view_modul_tambah');
		}
	}

	function edit_manajemenmodul(){
		cek_session_akses('manajemenmodul',$this->session->id_session);
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
            $data = array('nama_modul'=>$this->db->escape_str($this->input->post('a')),
                        'username'=>$this->session->username,
                        'link'=>$this->db->escape_str($this->input->post('b')),
                        'static_content'=>'',
                        'gambar'=>'',
                        'publish'=>$this->db->escape_str($this->input->post('c')),
                        'status'=>$this->db->escape_str($this->input->post('e')),
                        'aktif'=>$this->db->escape_str($this->input->post('d')),
                        'urutan'=>'0',
                        'link_seo'=>'');
            $where = array('id_modul' => $this->input->post('id'));
            $this->model_app->update('modul', $data, $where);
			redirect($this->uri->segment(1).'/manajemenmodul');
		}else{
            if ($this->session->level=='admin'){
                 $proses = $this->model_app->edit('modul', array('id_modul' => $id))->row_array();
            }else{
                $proses = $this->model_app->edit('modul', array('id_modul' => $id, 'username' => $this->session->username))->row_array();
            }
            $data = array('rows' => $proses);
			$this->template->load('/template','/mod_modul/view_modul_edit',$data);
		}
	}

	function delete_manajemenmodul(){
        cek_session_akses('manajemenmodul',$this->session->id_session);
		if ($this->session->level=='admin'){
            $id = array('id_modul' => $this->uri->segment(3));
        }else{
            $id = array('id_modul' => $this->uri->segment(3), 'username'=>$this->session->username);
        }
        $this->model_app->delete('modul',$id);
		redirect($this->uri->segment(1).'/manajemenmodul');
	}



    // RESELLER MODUL ==============================================================================================================================================================================

        // Controller Modul Konsumen

    function konsumen(){
        cek_session_akses('konsumen',$this->session->id_session);
        $data['record'] = $this->model_app->view_ordering('rb_konsumen','id_konsumen','DESC');
        $data['record1'] = $this->model_app->view_ordering('rb_produk','id_produk','DESC');
        $this->template->load('/template','/mod_konsumen/view_konsumen',$data);
    }

    function tambah_konsumen(){
        cek_session_akses('konsumen',$this->session->id_session);
        if (isset($_POST['submit'])){
            $config['upload_path'] = 'asset/foto_user/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '5000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('gg');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                $data = array('username'=>$this->input->post('aa'),
                            'password'=>hash("sha512", md5($this->input->post('a'))),
                            'nama_lengkap'=>$this->input->post('b'),
                            'email'=>$this->input->post('c'),
                            'jenis_kelamin'=>$this->input->post('d'),
                            'tanggal_lahir'=>$this->input->post('e'),
                            'alamat_lengkap'=>$this->input->post('g'),
                            'no_hp'=>$this->input->post('k'),
                            'kecamatan'=>$this->input->post('ia'),
                            'kota_id'=>$this->input->post('ga'),
                            'tanggal_daftar'=>date('Y-m-d'));
            }else{
                $data = array('username'=>$this->input->post('aa'),
                            'password'=>hash("sha512", md5($this->input->post('a'))),
                            'nama_lengkap'=>$this->input->post('b'),
                            'email'=>$this->input->post('c'),
                            'jenis_kelamin'=>$this->input->post('d'),
                            'tanggal_lahir'=>$this->input->post('e'),
                            'alamat_lengkap'=>$this->input->post('g'),
                            'no_hp'=>$this->input->post('k'),
                            'kecamatan'=>$this->input->post('ia'),
                            'kota_id'=>$this->input->post('ga'),
                            'foto'=>$hasil['file_name'],
                            'tanggal_daftar'=>date('Y-m-d'));
            }
            $this->model_app->insert('rb_konsumen',$data);
            redirect('main/konsumen');
        }else{
            $data['negara'] = $this->model_app->view_ordering('rb_provinsi','provinsi_id','DESC');
            $this->template->load('/template','/mod_konsumen/view_konsumen_tambah',$data);
        }
    }

    function edit_konsumen(){
        cek_session_akses('konsumen',$this->session->id_session);
        $id = $this->uri->segment(3);
        if (isset($_POST['submit'])){
            $config['upload_path'] = 'asset/foto_user/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '5000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('gg');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
                if (trim($this->input->post('a')) != ''){
                    $data = array('password'=>hash("sha512", md5($this->input->post('a'))),
                                    'nama_lengkap'=>$this->db->escape_str(strip_tags($this->input->post('b'))),
                                    'email'=>$this->db->escape_str(strip_tags($this->input->post('c'))),
                                    'jenis_kelamin'=>$this->db->escape_str($this->input->post('d')),
                                    'tanggal_lahir'=>$this->db->escape_str($this->input->post('e')),
                                    'alamat_lengkap'=>$this->db->escape_str(strip_tags($this->input->post('g'))),
                                    'kecamatan'=>$this->input->post('ia'),
                                    'kota_id'=>$this->input->post('ga'),
                                    'no_hp'=>$this->input->post('k'));
                }else{
                   $data = array('nama_lengkap'=>$this->db->escape_str(strip_tags($this->input->post('b'))),
                                    'email'=>$this->db->escape_str(strip_tags($this->input->post('c'))),
                                    'jenis_kelamin'=>$this->db->escape_str($this->input->post('d')),
                                    'tanggal_lahir'=>$this->db->escape_str($this->input->post('e')),
                                    'alamat_lengkap'=>$this->db->escape_str(strip_tags($this->input->post('g'))),
                                    'kecamatan'=>$this->input->post('ia'),
                                    'kota_id'=>$this->input->post('ga'),
                                    'no_hp'=>$this->input->post('k'));
                }
            }else{
                if (trim($this->input->post('a')) != ''){
                    $data = array('password'=>hash("sha512", md5($this->input->post('a'))),
                                    'nama_lengkap'=>$this->db->escape_str(strip_tags($this->input->post('b'))),
                                    'email'=>$this->db->escape_str(strip_tags($this->input->post('c'))),
                                    'jenis_kelamin'=>$this->db->escape_str($this->input->post('d')),
                                    'tanggal_lahir'=>$this->db->escape_str($this->input->post('e')),
                                    'alamat_lengkap'=>$this->db->escape_str(strip_tags($this->input->post('g'))),
                                    'kecamatan'=>$this->input->post('ia'),
                                    'kota_id'=>$this->input->post('ga'),
                                    'no_hp'=>$this->input->post('k'),
                                    'foto'=>$hasil['file_name']);
                }else{
                   $data = array('nama_lengkap'=>$this->db->escape_str(strip_tags($this->input->post('b'))),
                                    'email'=>$this->db->escape_str(strip_tags($this->input->post('c'))),
                                    'jenis_kelamin'=>$this->db->escape_str($this->input->post('d')),
                                    'tanggal_lahir'=>$this->db->escape_str($this->input->post('e')),
                                    'alamat_lengkap'=>$this->db->escape_str(strip_tags($this->input->post('g'))),
                                    'kecamatan'=>$this->input->post('ia'),
                                    'kota_id'=>$this->input->post('ga'),
                                    'no_hp'=>$this->input->post('k'),
                                    'foto'=>$hasil['file_name']);
                }
            }
            $where = array('id_konsumen' => $this->input->post('id'));
            $this->model_app->update('rb_konsumen', $data, $where);
            redirect('main/konsumen');
        }else{
            $data['rows'] = $this->model_reseller->profile_konsumen($id)->row_array();
            $row = $this->model_reseller->profile_konsumen($id)->row_array();
            $data['provinsi'] = $this->model_app->view_ordering('rb_provinsi','provinsi_id','ASC');
            $data['kota'] = $this->model_app->view_ordering('rb_kota','kota_id','ASC');
            // $data['rowse'] = $this->db->query("SELECT provinsi_id FROM rb_kota where kota_id='$row[kota_id]'")->row_array();
            $this->template->load('/template','/mod_konsumen/view_konsumen_edit',$data);
        }
    }
    
    function detail_konsumen(){
        cek_session_akses('konsumen',$this->session->id_session);
        $id = $this->uri->segment(3);
        $record = $this->model_reseller->orders_report($id,'reseller');
        $edit = $this->model_reseller->profile_konsumen($id)->row_array();
        $data = array('rows' => $edit,'record'=>$record);
        $this->template->load('/template','/mod_konsumen/view_konsumen_detail',$data);
    }

    function delete_konsumen(){
        cek_session_akses('konsumen',$this->session->id_session);
        $id = array('id_konsumen' => $this->uri->segment(3));
        $this->model_app->delete('rb_konsumen',$id);
        redirect('main/konsumen');
    }

    // Controller Modul Kategori Produk

    function kategori_produk(){
        cek_session_akses('kategori_produk',$this->session->id_session);
        $data['record'] = $this->model_app->view_ordering('rb_kategori_produk','id_kategori_produk','DESC');
        $this->template->load('/template','/mod_kategori_produk/view_kategori_produk',$data);
    }

    function tambah_kategori_produk(){
        cek_session_akses('kategori_produk',$this->session->id_session);
        if (isset($_POST['submit'])){
            $data = array('nama_kategori'=>$this->input->post('a'),'kategori_seo'=>seo_title($this->input->post('a')));
            $this->model_app->insert('rb_kategori_produk',$data);
            redirect('main/kategori_produk');
        }else{
            $this->template->load('/template','/mod_kategori_produk/view_kategori_produk_tambah');
        }
    }

    function edit_kategori_produk(){
        cek_session_akses('kategori_produk',$this->session->id_session);
        $id = $this->uri->segment(3);
        if (isset($_POST['submit'])){
            $data = array('nama_kategori'=>$this->input->post('a'),'kategori_seo'=>seo_title($this->input->post('a')));
            $where = array('id_kategori_produk' => $this->input->post('id'));
            $this->model_app->update('rb_kategori_produk', $data, $where);
            redirect('main/kategori_produk');
        }else{
            $edit = $this->model_app->edit('rb_kategori_produk',array('id_kategori_produk'=>$id))->row_array();
            $data = array('rows' => $edit);
            $this->template->load('/template','/mod_kategori_produk/view_kategori_produk_edit',$data);
        }
    }

    function delete_kategori_produk(){
        cek_session_akses('kategori_produk',$this->session->id_session);
        $id = array('id_kategori_produk' => $this->uri->segment(3));
        $this->model_app->delete('rb_kategori_produk',$id);
        redirect('main/kategori_produk');
    }

    // Controller Modul Produk 

    function produk(){
        cek_session_akses('produk',$this->session->id_session);
        $data['record'] = $this->model_app->view_ordering('rb_produk','id_produk','DESC');
        $this->template->load('/template','/mod_produk/view_produk',$data);
    }

    function tambah_produk(){
        cek_session_akses('produk',$this->session->id_session);
        if (isset($_POST['submit'])){
            $files = $_FILES;
            $cpt = count($_FILES['userfile']['name']);
            for($i=0; $i<$cpt; $i++){
                $_FILES['userfile']['name']= $files['userfile']['name'][$i];
                $_FILES['userfile']['type']= $files['userfile']['type'][$i];
                $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
                $_FILES['userfile']['error']= $files['userfile']['error'][$i];
                $_FILES['userfile']['size']= $files['userfile']['size'][$i];
                $this->load->library('upload');
                $this->upload->initialize($this->set_upload_options());
                $this->upload->do_upload();
                $fileName = $this->upload->data()['file_name'];
                $images[] = $fileName;
            }
            $fileName = implode(';',$images);
            $fileName = str_replace(' ','_',$fileName);
            if (trim($fileName)!=''){
                $data = array('id_kategori_produk'=>$this->input->post('a'),
                              'nama_produk'=>$this->input->post('b'),
                              'produk_seo'=>seo_title($this->input->post('b')),
                              'satuan'=>$this->input->post('c'),
                              'harga_konsumen'=>$this->input->post('f'),
                              'berat'=>$this->input->post('berat'),
                              'gambar'=>$fileName,
                              'keterangan'=>$this->input->post('ff'),
                              'username'=>$this->session->username,
                              'waktu_input'=>date('Y-m-d H:i:s'));
            }else{
                $data = array('id_kategori_produk'=>$this->input->post('a'),
                              'nama_produk'=>$this->input->post('b'),
                              'produk_seo'=>seo_title($this->input->post('b')),
                              'satuan'=>$this->input->post('c'),
                              'harga_konsumen'=>$this->input->post('f'),
                              'berat'=>$this->input->post('berat'),
                              'keterangan'=>$this->input->post('ff'),
                              'username'=>$this->session->username,
                              'waktu_input'=>date('Y-m-d H:i:s'));
            }
            $this->model_app->insert('rb_produk',$data);
            redirect('main/produk');
        }else{
            $data['record'] = $this->model_app->view_ordering('rb_kategori_produk','id_kategori_produk','DESC');
            $this->template->load('/template','/mod_produk/view_produk_tambah',$data);
        }
    }

    function edit_produk(){
        cek_session_akses('produk',$this->session->id_session);
        $id = $this->uri->segment(3);
        if (isset($_POST['submit'])){
            $files = $_FILES;
            $cpt = count($_FILES['userfile']['name']);
            for($i=0; $i<$cpt; $i++){
                $_FILES['userfile']['name']= $files['userfile']['name'][$i];
                $_FILES['userfile']['type']= $files['userfile']['type'][$i];
                $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
                $_FILES['userfile']['error']= $files['userfile']['error'][$i];
                $_FILES['userfile']['size']= $files['userfile']['size'][$i];
                $this->load->library('upload');
                $this->upload->initialize($this->set_upload_options());
                $this->upload->do_upload();
                $fileName = $this->upload->data()['file_name'];
                $images[] = $fileName;
            }
            $fileName = implode(';',$images);
            $fileName = str_replace(' ','_',$fileName);
            if (trim($fileName)!=''){
                $data = array('id_kategori_produk'=>$this->input->post('a'),
                              'id_kategori_produk_sub'=>$this->input->post('aa'),
                              'nama_produk'=>$this->input->post('b'),
                              'produk_seo'=>seo_title($this->input->post('b')),
                              'satuan'=>$this->input->post('c'),
                              'harga_beli'=>$this->input->post('d'),
                              'harga_reseller'=>$this->input->post('e'),
                              'harga_konsumen'=>$this->input->post('f'),
                              'berat'=>$this->input->post('berat'),
                              'gambar'=>$fileName,
                              'keterangan'=>$this->input->post('ff'),
                              'username'=>$this->session->username);
            }else{
                $data = array('id_kategori_produk'=>$this->input->post('a'),
                              'id_kategori_produk_sub'=>$this->input->post('aa'),
                              'nama_produk'=>$this->input->post('b'),
                              'produk_seo'=>seo_title($this->input->post('b')),
                              'satuan'=>$this->input->post('c'),
                              'harga_beli'=>$this->input->post('d'),
                              'harga_reseller'=>$this->input->post('e'),
                              'harga_konsumen'=>$this->input->post('f'),
                              'berat'=>$this->input->post('berat'),
                              'keterangan'=>$this->input->post('ff'),
                              'username'=>$this->session->username);
            }

            $where = array('id_produk' => $this->input->post('id'));
            $this->model_app->update('rb_produk', $data, $where);
            redirect('main/produk');
        }else{
            $data['record'] = $this->model_app->view_ordering('rb_kategori_produk','id_kategori_produk','DESC');
            $data['rows'] = $this->model_app->edit('rb_produk',array('id_produk'=>$id))->row_array();
            $this->template->load('/template','/mod_produk/view_produk_edit',$data);
        }
    }

    private function set_upload_options(){
        $config = array();
        $config['upload_path'] = 'asset/foto_produk/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '5000'; // kb
        $config['encrypt_name'] = FALSE;
        $this->load->library('upload', $config);
      return $config;
    }

    function delete_produk(){
        cek_session_akses('produk',$this->session->id_session);
        $id = array('id_produk' => $this->uri->segment(3));
        $this->model_app->delete('rb_produk',$id);
        redirect('main/produk');
    }

    // Controller Modul Pembelian

    function pembelian(){
        cek_session_akses('pembelian',$this->session->id_session);
        $this->session->unset_userdata('idp');
        $data['record'] = $this->model_app->view_join_one('rb_pembelian','rb_supplier','id_supplier','id_pembelian','DESC');
        $this->template->load('/template','/mod_pembelian/view_pembelian',$data);
    }

    function detail_pembelian(){
        cek_session_akses('pembelian',$this->session->id_session);
        $data['rows'] = $this->model_reseller->view_join_rows('rb_pembelian','rb_supplier','id_supplier',array('id_pembelian'=>$this->uri->segment(3)),'id_pembelian','DESC')->row_array();
        $data['record'] = $this->model_app->view_join_where('rb_pembelian_detail','rb_produk','id_produk',array('id_pembelian'=>$this->uri->segment(3)),'id_pembelian_detail','DESC');
        $this->template->load('/template','/mod_pembelian/view_pembelian_detail',$data);
    }

    function tambah_pembelian(){
        cek_session_akses('pembelian',$this->session->id_session);
        if (isset($_POST['submit1'])){
            if ($this->session->idp == ''){
                $data = array('kode_pembelian'=>$this->input->post('a'),
                              'id_supplier'=>$this->input->post('b'),
                              'waktu_beli'=>date('Y-m-d H:i:s'));
                $this->model_app->insert('rb_pembelian',$data);
                $idp = $this->db->insert_id();
                $this->session->set_userdata(array('idp'=>$idp));
            }else{
                $data = array('kode_pembelian'=>$this->input->post('a'),
                              'id_supplier'=>$this->input->post('b'));
                $where = array('id_pembelian' => $this->session->idp);
                $this->model_app->update('rb_pembelian', $data, $where);
            }
            redirect('/tambah_pembelian');

        }elseif(isset($_POST['submit'])){
            if ($this->input->post('idpd')==''){
                $data = array('id_pembelian'=>$this->session->idp,
                              'id_produk'=>$this->input->post('aa'),
                              'harga_pesan'=>$this->input->post('bb'),
                              'jumlah_pesan'=>$this->input->post('cc'),
                              'satuan'=>$this->input->post('dd'));
                $this->model_app->insert('rb_pembelian_detail',$data);
            }else{
                $data = array('id_produk'=>$this->input->post('aa'),
                              'harga_pesan'=>$this->input->post('bb'),
                              'jumlah_pesan'=>$this->input->post('cc'),
                              'satuan'=>$this->input->post('dd'));
                $where = array('id_pembelian_detail' => $this->input->post('idpd'));
                $this->model_app->update('rb_pembelian_detail', $data, $where);
            }
            redirect('/tambah_pembelian');
        }else{
            $data['rows'] = $this->model_reseller->view_join_rows('rb_pembelian','rb_supplier','id_supplier',array('id_pembelian'=>$this->session->idp),'id_pembelian','DESC')->row_array();
            $data['record'] = $this->model_app->view_join_where('rb_pembelian_detail','rb_produk','id_produk',array('id_pembelian'=>$this->session->idp),'id_pembelian_detail','DESC');
            $data['barang'] = $this->model_app->view_where_ordering('rb_produk',array('id_reseller'=>'0'),'id_produk','ASC');
            $data['supplier'] = $this->model_app->view_ordering('rb_supplier','id_supplier','ASC');
            if ($this->uri->segment(3)!=''){
                $data['row'] = $this->model_app->view_where('rb_pembelian_detail',array('id_pembelian_detail'=>$this->uri->segment(3)))->row_array();
            }
            $this->template->load('/template','/mod_pembelian/view_pembelian_tambah',$data);
        }
    }

    function edit_pembelian(){
        cek_session_akses('pembelian',$this->session->id_session);
        if (isset($_POST['submit1'])){
            $data = array('kode_pembelian'=>$this->input->post('a'),
                          'id_supplier'=>$this->input->post('b'),
                          'waktu_beli'=>$this->input->post('c'));
            $where = array('id_pembelian' => $this->input->post('idp'));
            $this->model_app->update('rb_pembelian', $data, $where);
            redirect('/edit_pembelian/'.$this->input->post('idp'));

        }elseif(isset($_POST['submit'])){
            if ($this->input->post('idpd')==''){
                $data = array('id_pembelian'=>$this->input->post('idp'),
                              'id_produk'=>$this->input->post('aa'),
                              'harga_pesan'=>$this->input->post('bb'),
                              'jumlah_pesan'=>$this->input->post('cc'),
                              'satuan'=>$this->input->post('dd'));
                $this->model_app->insert('rb_pembelian_detail',$data);
            }else{
                $data = array('id_produk'=>$this->input->post('aa'),
                              'harga_pesan'=>$this->input->post('bb'),
                              'jumlah_pesan'=>$this->input->post('cc'),
                              'satuan'=>$this->input->post('dd'));
                $where = array('id_pembelian_detail' => $this->input->post('idpd'));
                $this->model_app->update('rb_pembelian_detail', $data, $where);
            }
            redirect('/edit_pembelian/'.$this->input->post('idp'));
        }else{
            $data['rows'] = $this->model_reseller->view_join_rows('rb_pembelian','rb_supplier','id_supplier',array('id_pembelian'=>$this->uri->segment(3)),'id_pembelian','DESC')->row_array();
            $data['record'] = $this->model_app->view_join_where('rb_pembelian_detail','rb_produk','id_produk',array('id_pembelian'=>$this->uri->segment(3)),'id_pembelian_detail','DESC');
            $data['barang'] = $this->model_app->view_where_ordering('rb_produk',array('id_reseller'=>'0'),'id_produk','ASC');
            $data['supplier'] = $this->model_app->view_ordering('rb_supplier','id_supplier','ASC');
            if ($this->uri->segment(4)!=''){
                $data['row'] = $this->model_app->view_where('rb_pembelian_detail',array('id_pembelian_detail'=>$this->uri->segment(4)))->row_array();
            }
            $this->template->load('/template','/mod_pembelian/view_pembelian_edit',$data);
        }
    }

    function delete_pembelian(){
        cek_session_akses('pembelian',$this->session->id_session);
        $id = array('id_pembelian' => $this->uri->segment(3));
        $this->model_app->delete('rb_pembelian',$id);
        $this->model_app->delete('rb_pembelian_detail',$id);
        redirect('/pembelian');
    }

    function delete_pembelian_detail(){
        cek_session_akses('pembelian',$this->session->id_session);
        $id = array('id_pembelian_detail' => $this->uri->segment(4));
        $this->model_app->delete('rb_pembelian_detail',$id);
        redirect('/edit_pembelian/'.$this->uri->segment(3));
    }

    function delete_pembelian_tambah_detail(){
        cek_session_akses('pembelian',$this->session->id_session);
        $id = array('id_pembelian_detail' => $this->uri->segment(3));
        $this->model_app->delete('rb_pembelian_detail',$id);
        redirect('/tambah_pembelian');
    }



    // Controller Modul Penjualan

    function penjualan(){
        cek_session_akses('penjualan',$this->session->id_session);
        $this->session->unset_userdata('idp');
        $data['record'] = $this->model_reseller->penjualan_list(1,'admin');
        $this->template->load('/template','/mod_penjualan/view_penjualan',$data);
    }

    function detail_penjualan(){
        cek_session_akses('penjualan',$this->session->id_session);
        $data['rows'] = $this->model_reseller->penjualan_detail($this->uri->segment(3))->row_array();
        $data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->uri->segment(3)),'id_penjualan_detail','DESC');
        $this->template->load('/template','/mod_penjualan/view_penjualan_detail',$data);
    }

    function tambah_penjualan(){
        cek_session_akses('penjualan',$this->session->id_session);
        if (isset($_POST['submit1'])){
            if ($this->session->idp == ''){
                $data = array('kode_transaksi'=>$this->input->post('a'),
                              'id_pembeli'=>$this->input->post('b'),
                              'id_penjual'=>0,
                              'status_pembeli'=>'reseller',
                              'status_penjual'=>'admin',
                              'waktu_transaksi'=>date('Y-m-d H:i:s'),
                              'proses'=>'0');
                $this->model_app->insert('rb_penjualan',$data);
                $idp = $this->db->insert_id();
                $this->session->set_userdata(array('idp'=>$idp));
            }else{
                $data = array('kode_transaksi'=>$this->input->post('a'),
                              'id_pembeli'=>$this->input->post('b'));
                $where = array('id_penjualan' => $this->session->idp);
                $this->model_app->update('rb_penjualan', $data, $where);
            }
                redirect('/tambah_penjualan');

        }elseif(isset($_POST['submit'])){
            $jual = $this->model_reseller->jual($this->input->post('aa'))->row_array();
            $beli = $this->model_reseller->beli($this->input->post('aa'))->row_array();
            $stok = $beli['beli']-$jual['jual'];
            if ($this->input->post('dd') > $stok){
                echo "<script>window.alert('Maaf, Stok Tidak Mencukupi!');
                                  window.location=('".base_url()."/tambah_penjualan')</script>";
            }else{
                if ($this->input->post('idpd')==''){
                    $data = array('id_penjualan'=>$this->session->idp,
                                  'id_produk'=>$this->input->post('aa'),
                                  'jumlah'=>$this->input->post('dd'),
                                  'diskon'=>$this->input->post('cc'),
                                  'harga_jual'=>$this->input->post('bb'),
                                  'satuan'=>$this->input->post('ee'));
                    $this->model_app->insert('rb_penjualan_detail',$data);
                }else{
                    $data = array('id_produk'=>$this->input->post('aa'),
                                  'jumlah'=>$this->input->post('dd'),
                                  'diskon'=>$this->input->post('cc'),
                                  'harga_jual'=>$this->input->post('bb'),
                                  'satuan'=>$this->input->post('ee'));
                    $where = array('id_penjualan_detail' => $this->input->post('idpd'));
                    $this->model_app->update('rb_penjualan_detail', $data, $where);
                }
                redirect('/tambah_penjualan');
            }
            
        }else{
            $data['rows'] = $this->model_reseller->penjualan_detail($this->session->idp)->row_array();
            $data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->session->idp),'id_penjualan_detail','DESC');
            $data['barang'] = $this->model_app->view_ordering('rb_produk','id_produk','ASC');
            $data['reseller'] = $this->model_app->view_ordering('rb_reseller','id_reseller','ASC');
            if ($this->uri->segment(3)!=''){
                $data['row'] = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan_detail'=>$this->uri->segment(3)))->row_array();
            }
            $this->template->load('/template','/mod_penjualan/view_penjualan_tambah',$data);
        }
    }

    function edit_penjualan(){
        cek_session_akses('penjualan',$this->session->id_session);
        if (isset($_POST['submit1'])){
            $data = array('kode_transaksi'=>$this->input->post('a'),
                          'id_pembeli'=>$this->input->post('b'),
                          'waktu_transaksi'=>$this->input->post('c'));
            $where = array('id_penjualan' => $this->input->post('idp'));
            $this->model_app->update('rb_penjualan', $data, $where);
            redirect('/edit_penjualan/'.$this->input->post('idp'));

        }elseif(isset($_POST['submit'])){
            $cekk = $this->db->query("SELECT * FROM rb_penjualan_detail where id_penjualan='".$this->input->post('idp')."' AND id_produk='".$this->input->post('aa')."'")->row_array();
            $jual = $this->model_reseller->jual($this->input->post('aa'))->row_array();
            $beli = $this->model_reseller->beli($this->input->post('aa'))->row_array();
            $stok = $beli['beli']-$jual['jual']+$cekk['jumlah'];
            if ($this->input->post('dd') > $stok){
                echo "<script>window.alert('Maaf, Stok Tidak Mencukupi!');
                                  window.location=('".base_url()."/edit_penjualan/".$this->input->post('idp')."')</script>";
            }else{
                if ($this->input->post('idpd')==''){
                    $data = array('id_penjualan'=>$this->input->post('idp'),
                                  'id_produk'=>$this->input->post('aa'),
                                  'jumlah'=>$this->input->post('dd'),
                                  'diskon'=>$this->input->post('cc'),
                                  'harga_jual'=>$this->input->post('bb'),
                                  'satuan'=>$this->input->post('ee'));
                    $this->model_app->insert('rb_penjualan_detail',$data);
                }else{
                    $data = array('id_produk'=>$this->input->post('aa'),
                                  'jumlah'=>$this->input->post('dd'),
                                  'diskon'=>$this->input->post('cc'),
                                  'harga_jual'=>$this->input->post('bb'),
                                  'satuan'=>$this->input->post('ee'));
                    $where = array('id_penjualan_detail' => $this->input->post('idpd'));
                    $this->model_app->update('rb_penjualan_detail', $data, $where);
                }
                redirect('/edit_penjualan/'.$this->input->post('idp'));
            }
            
        }else{
            $data['rows'] = $this->model_reseller->penjualan_detail($this->uri->segment(3))->row_array();
            $data['record'] = $this->model_app->view_join_where('rb_penjualan_detail','rb_produk','id_produk',array('id_penjualan'=>$this->uri->segment(3)),'id_penjualan_detail','DESC');
            $data['barang'] = $this->model_app->view_ordering('rb_produk','id_produk','ASC');
            $data['reseller'] = $this->model_app->view_ordering('rb_reseller','id_reseller','ASC');
            if ($this->uri->segment(4)!=''){
                $data['row'] = $this->model_app->view_where('rb_penjualan_detail',array('id_penjualan_detail'=>$this->uri->segment(4)))->row_array();
            }
            $this->template->load('/template','/mod_penjualan/view_penjualan_edit',$data);
        }
    }

	function logout(){
		$this->session->sess_destroy();
		redirect('');
	}
}
