<?php

class Masuk_cacat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->library('form_validation');
        $this->load->model('Barang_model', 'barang');
        $this->load->model('Harga_model', 'harga');
        $this->load->model('Masuk_cacat_model', 'masuk_cacat');
        $this->load->model('Barang_cacat_model', 'barang_cacat');
        $this->load->model('Toko_model', 'toko');
        $this->load->model('User_model', 'user');
        $this->datelib->asiaJakartaDate();
    }

    public function index()
    {

        $this->view["title_menu"]   = "Barang Cacat";
        $this->view["title"]        = "Masuk Cacat";
        $this->view["content"]      = "masuk_cacat/v_masuk_cacat_index";

        if ($this->session->userdata('toko_id')) {
            $this->view['toko_id']  = $this->session->userdata('toko_id');
        } else {
            if ($this->input->get('toko_id')) {
                $this->view['toko_id']   = $this->secure->decrypt_url($this->input->get('toko_id'));
            } else {
                $this->view['toko_id']    = null;
            }  
        }
        $this->view['toko']         = $this->toko->ambilSemuaToko();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function ajaxAmbilSemuaMasukCacat()
    {

        $newArr = [];
        $data   = $this->masuk_cacat->ajaxAmbilSemuaMasukCacat();

        $no = 1;
        foreach ($data as $d) {

            $rsb = "  
                        <a onclick='lihat_masuk_cacat(" . $d['id_masuk_cacat'] . ")'>
                            <button type='button' class='btn btn-sm btn-info'>
                              <span class='tf-icons bx bx-show-alt'></span>&nbsp; Lihat
                            </button>
                        </a>
                     ";


            $row = [];
            $row[] = $no;
            $row[] = $d["kode_barang"];
            $row[] = $d["nama_barang"];
            $row[] = $d["jumlah_masuk"];
            $row[] = $d["tgl_masuk"];
            $row[] = ($d["is_rollback"] == 0) ? "<small class='text-success'>Sukses</small>" : "<small class='text-danger'>Batal</small>";
            $row[] = $rsb;

            array_push($newArr, $row);
            $no++;
        }

        $data_json["draw"]              = $this->input->post("draw");
        $data_json["data"]              = $newArr;
        $data_json["recordsTotal"]      = $this->masuk_cacat->ajaxAmbilHitungSemuaMasukCacat();
        $data_json["recordsFiltered"]   = $this->masuk_cacat->ajaxAmbilFilterSemuaMasukCacat();

        echo json_encode($data_json);
    }

    public function tambah()
    {
        $getTokoId = $this->input->get('toko_id');
        $getDecryptTokoId = base64_decode($getTokoId);
        $getTokoIdSess = $this->session->userdata('toko_id');

        if (empty($getTokoId) && empty($getTokoIdSess)) {
            redirect('barang_cacat/masuk_cacat');
        } else {
            $currentTokoId = '';
            if ($getTokoIdSess) {
                $currentTokoId = $getTokoIdSess;
            } else {
                $currentTokoId = $getDecryptTokoId;
            }

            $this->form_validation->set_rules(
                "id_harga",
                "Barang",
                "required",
                [
                    "required" => "%s wajib diisi"
                ]
            );
            $this->form_validation->set_rules(
                "jumlah_masuk",
                "Jumlah Cacat",
                "required|max_length[20]",
                [
                    "required" => "%s wajib diisi"
                ]
            );
            $this->form_validation->set_rules(
                "status_masuk",
                "Status",
                "required|max_length[20]",
                [
                    "required" => "%s wajib diisi"
                ]
            );

            if ($this->form_validation->run() == false) {
                $this->view['barangtoko']   = $this->harga->ambilBarangBerdasarkanToko($currentTokoId);
                $this->view['toko_id']      = $currentTokoId;

                $this->view["title_menu"]   = "Barang Cacat";
                $this->view["title"]        = "Masuk Cacat";
                $this->view['content']      = "masuk_cacat/v_tambah";

                $this->load->view('layout/header', $this->view);
                $this->load->view('layout/sidebar', $this->view);
                $this->load->view('layout/navbar', $this->view);
                $this->load->view('masuk_cacat/v_tambah', $this->view);
                $this->load->view('layout/footer', $this->view);
                $this->load->view('layout/script', $this->view);
            } else {

                $validationImage = $this->validate_bukti_file($_FILES['bukti_masuk']);

                if ($validationImage['err_code'] == 0) {

                    //dapatkan barang_id dari id_harga yang diplih
                    $harga = $this->harga->getTableHargaId($this->input->post("id_harga"));
                    $stok_toko_update = $harga['stok_toko']-$this->input->post("jumlah_masuk");

                    $barang_cacat = $this->barang_cacat->ambilBarangCacatBerdasarkanBarangdanToko($currentTokoId, $harga['barang_id']);

                    if ($this->input->post("status_masuk") == "Stok Cacat") {

                        //cek apakah barang cacat dengan toko_id dan barang_id ini sudah ada!
                        if ($barang_cacat) {

                            $stok_cacat_update = $barang_cacat['stok_cacat']+$this->input->post("jumlah_masuk");

                            //proses update stok_toko di tbl_harga, kemudian update stok_cacat di tbl_barang_cacat, dan create data tbl_masuk_cacat
                            $update_stok_toko = [
                                "id_harga"   => $this->input->post("id_harga"),
                                "stok_toko"  => $stok_toko_update,
                                "user_edit"  => $this->session->userdata("nama_user"),
                                "updated_at" => date("Y-m-d H:i:s"),
                            ];

                            $insert_masuk_cacat = [
                                "toko_id"          => $currentTokoId,
                                "barang_id"        => $harga['barang_id'],
                                "jumlah_masuk"     => $this->input->post("jumlah_masuk"),
                                "bukti_masuk"      => $validationImage['filename'],
                                "tgl_masuk"        => date("Y-m-d H:i:s"),
                                "status_masuk"     => $this->input->post("status_masuk"),
                                "user_input"       => $this->session->userdata("nama_user"),
                                "created_at"       => date("Y-m-d H:i:s"),
                            ];

                            $update_stok_cacat = [
                                "id_barang_cacat"   => $barang_cacat['id_barang_cacat'],
                                "stok_cacat"        => $stok_cacat_update,
                                "user_edit"         => $this->session->userdata("nama_user"),
                                "updated_at"        => date("Y-m-d H:i:s"),
                            ];

                            if ($this->masuk_cacat->prosesstokcacat_if_barangcacat($update_stok_toko, $insert_masuk_cacat, $update_stok_cacat)) {
                                $this->session->set_flashdata("berhasil", "Data stok cacat berhasil ditambahkan!");
                                redirect('barang_cacat/masuk_cacat');
                            } else {
                                $this->session->set_flashdata("gagal", "Data stok cacat gagal ditambahkan!");
                                redirect('barang_cacat/masuk_cacat/tambah');
                            }
                            
                        } else {

                            //proses update stok_toko di tbl_harga, kemudian create data di tbl_barang_cacat, dan create data tbl_masuk_cacat

                            $update_stok_toko = [
                                "id_harga"   => $this->input->post("id_harga"),
                                "stok_toko"  => $stok_toko_update,
                                "user_edit"  => $this->session->userdata("nama_user"),
                                "updated_at" => date("Y-m-d H:i:s"),
                            ];

                            $insert_masuk_cacat = [
                                "toko_id"          => $currentTokoId,
                                "barang_id"        => $harga['barang_id'],
                                "jumlah_masuk"     => $this->input->post("jumlah_masuk"),
                                "bukti_masuk"      => $validationImage['filename'],
                                "tgl_masuk"        => date("Y-m-d H:i:s"),
                                "status_masuk"     => $this->input->post("status_masuk"),
                                "user_input"       => $this->session->userdata("nama_user"),
                                "created_at"       => date("Y-m-d H:i:s"),
                            ];

                            $insert_master_cacat = [
                                "barang_id"         => $harga['barang_id'],
                                "toko_id"           => htmlspecialchars($this->input->post("toko_id")),
                                "kd_barang_cacat"   => $this->createKodeBarangCacat(),
                                "stok_cacat"        => htmlspecialchars($this->input->post("jumlah_masuk")),
                                "status"            => 1,
                                "user_input"        => $this->session->userdata("nama_user"),
                                "created_at"        => date("Y-m-d H:i:s"),
                            ];

                            if ($this->masuk_cacat->prosesstokcacat_if_notbarangcacat($update_stok_toko, $insert_masuk_cacat, $insert_master_cacat)) {
                                $this->session->set_flashdata("berhasil", "Data stok cacat berhasil ditambahkan!");
                                redirect('barang_cacat/masuk_cacat');
                            } else {
                                $this->session->set_flashdata("gagal", "Data stok cacat gagal ditambahkan!");
                                redirect('barang_cacat/masuk_cacat/tambah');
                            }
                        }

                    } elseif ($this->input->post("status_masuk") == "Dipakai") {

                        if ($barang_cacat) {
                            $barang_cacat_id = $barang_cacat['id_barang_cacat'];

                            //proses update stok_toko di tbl_harga, kemudian create data tbl_masuk_cacat, dan ambil id_barang_cacat nya untuk create data tbl_keluar_cacat;

                            $update_stok_toko = [
                                "id_harga"   => $this->input->post("id_harga"),
                                "stok_toko"  => $stok_toko_update,
                                "user_edit"  => $this->session->userdata("nama_user"),
                                "updated_at" => date("Y-m-d H:i:s"),
                            ];

                            $insert_masuk_cacat = [
                                "toko_id"          => $currentTokoId,
                                "barang_id"        => $harga['barang_id'],
                                "jumlah_masuk"     => $this->input->post("jumlah_masuk"),
                                "bukti_masuk"      => $validationImage['filename'],
                                "tgl_masuk"        => date("Y-m-d H:i:s"),
                                "status_masuk"     => $this->input->post("status_masuk"),
                                "user_input"       => $this->session->userdata("nama_user"),
                                "created_at"       => date("Y-m-d H:i:s"),
                            ];

                            $insert_keluar_cacat = [
                                "barang_cacat_id"       => $barang_cacat_id,
                                "jenis_keluar_cacat"    => $this->input->post("status_masuk"),
                                "jml_keluar_cacat"      => $this->input->post("jumlah_masuk"),
                                "bukti_keluar_cacat"    => $validationImage['filename'],
                                "tanggal_keluar_cacat"  => date("Y-m-d H:i:s"),
                                "user_input"            => $this->session->userdata("nama_user"),
                                "created_at"            => date("Y-m-d H:i:s"),
                            ];

                            if ($this->masuk_cacat->prosesdipakai_if_barangcacat($update_stok_toko, $insert_masuk_cacat, $insert_keluar_cacat)) {
                                $this->session->set_flashdata("berhasil", "Data dipakai cacat berhasil ditambahkan!");
                                redirect('barang_cacat/masuk_cacat');
                            } else {
                                $this->session->set_flashdata("gagal", "Data dipakai cacat gagal ditambahkan!");
                                redirect('barang_cacat/masuk_cacat/tambah');
                            }
                            
                        } else {
                            //proses update stok_toko di tbl_harga, kemudian create data tbl_masuk_cacat, create data di tbl_barang_cacat (tanpa memasukkan jumlah_masuk), dan ambil id_barang_cacat nya untuk create data tbl_keluar_cacat;

                            $update_stok_toko = [
                                "id_harga"   => $this->input->post("id_harga"),
                                "stok_toko"  => $stok_toko_update,
                                "user_edit"  => $this->session->userdata("nama_user"),
                                "updated_at" => date("Y-m-d H:i:s"),
                            ];

                            $insert_masuk_cacat = [
                                "toko_id"          => $currentTokoId,
                                "barang_id"        => $harga['barang_id'],
                                "jumlah_masuk"     => $this->input->post("jumlah_masuk"),
                                "bukti_masuk"      => $validationImage['filename'],
                                "tgl_masuk"        => date("Y-m-d H:i:s"),
                                "status_masuk"     => $this->input->post("status_masuk"),
                                "user_input"       => $this->session->userdata("nama_user"),
                                "created_at"       => date("Y-m-d H:i:s"),
                            ];

                            $insert_master_cacat = [
                                "barang_id"         => $harga['barang_id'],
                                "toko_id"           => htmlspecialchars($this->input->post("toko_id")),
                                "kd_barang_cacat"   => $this->createKodeBarangCacat(),
                                "status"            => 1,
                                "user_input"        => $this->session->userdata("nama_user"),
                                "created_at"        => date("Y-m-d H:i:s"),
                            ];

                            $insert_keluar_cacat = [
                                "jenis_keluar_cacat"    => $this->input->post("status_masuk"),
                                "jml_keluar_cacat"      => $this->input->post("jumlah_masuk"),
                                "bukti_keluar_cacat"    => $validationImage['filename'],
                                "tanggal_keluar_cacat"  => date("Y-m-d H:i:s"),
                                "user_input"            => $this->session->userdata("nama_user"),
                                "created_at"            => date("Y-m-d H:i:s"),
                            ];

                            if ($this->masuk_cacat->prosesdipakai_if_notbarangcacat($update_stok_toko, $insert_masuk_cacat, $insert_master_cacat, $insert_keluar_cacat)) {
                                $this->session->set_flashdata("berhasil", "Data dipakai cacat berhasil ditambahkan!");
                                redirect('barang_cacat/masuk_cacat');
                            } else {
                                $this->session->set_flashdata("gagal", "Data dipakai cacat gagal ditambahkan!");
                                redirect('barang_cacat/masuk_cacat/tambah');
                            }
                        }

                    } elseif ($this->input->post("status_masuk") == "Musnah") {
                        //proses update stok_toko di tbl_harga, kemudian create data tbl_masuk_cacat, dan create data tbl_barang_musnah;

                        $update_stok_toko = [
                            "id_harga"   => $this->input->post("id_harga"),
                            "stok_toko"  => $stok_toko_update,
                            "user_edit"  => $this->session->userdata("nama_user"),
                            "updated_at" => date("Y-m-d H:i:s"),
                        ];

                        $insert_masuk_cacat = [
                            "toko_id"          => $currentTokoId,
                            "barang_id"        => $harga['barang_id'],
                            "jumlah_masuk"     => $this->input->post("jumlah_masuk"),
                            "bukti_masuk"      => $validationImage['filename'],
                            "tgl_masuk"        => date("Y-m-d H:i:s"),
                            "status_masuk"     => $this->input->post("status_masuk"),
                            "user_input"       => $this->session->userdata("nama_user"),
                            "created_at"       => date("Y-m-d H:i:s"),
                        ];

                        $insert_barang_musnah = [
                            "barang_id"        => $currentTokoId,
                            "toko_id"          => $harga['barang_id'],
                            "qty_sampah"       => $this->input->post("jumlah_masuk"),
                            "tgl_musnah"       => date("Y-m-d H:i:s"),
                            "bukti_musnah"     => $validationImage['filename'],
                            "user_input"       => $this->session->userdata("nama_user"),
                            "created_at"       => date("Y-m-d H:i:s"),
                        ];

                        if ($this->masuk_cacat->prosesmusnah($update_stok_toko, $insert_masuk_cacat, $insert_barang_musnah)) {
                            $this->session->set_flashdata("berhasil", "Data musnah cacat berhasil ditambahkan!");
                            redirect('barang_cacat/masuk_cacat');
                        } else {
                            $this->session->set_flashdata("gagal", "Data musnah cacat gagal ditambahkan!");
                            redirect('barang_cacat/masuk_cacat/tambah');
                        }

                    }

                } else {
                    $this->session->set_flashdata('gagal', $validationImage['message']);
                    redirect('barang_cacat/masuk_cacat/tambah?toko_id=' . base64_encode($currentTokoId));
                }
            }
        }
    }

    private function createKodeBarangCacat()
    {
        $query_max_pendaftaran = $this->barang_cacat->getKodeBarangCacat();
        $kode = "";
        if ($query_max_pendaftaran->num_rows() > 0) {
            //cek kode jika telah tersedia    
            foreach ($query_max_pendaftaran->result_array() as $k) {
                $tmp = ((int)$k['kd_barang_cacat']) + 1;
                $kode = sprintf("%06s", $tmp);
            }
        } else {
            $kode = "000001";
        }

        $batas = str_pad($kode, 6, "0", STR_PAD_LEFT);
        // $prefix = "BRG-000001";
        $prefix = "CCD-";

        $kodebaru = $prefix . $batas;
        return $kodebaru;
    }

    private function validate_bukti_file($file)
    {
        $err_code = 0;
        $message = '';
        $data = '';
        $fileName = '';

        if (empty($file['name'])) {
            $err_code++;
            $message = "Bukti Cacat harus diisi";
        } else { 
            $this->load->library('encryption');
            $key = bin2hex(random_bytes(32));
            $encrypted_name = $this->encryption->encrypt($file['name']);


            // $config['file_name']            = $file['name'];
            $config['upload_path']          = './assets/file_bukti_cacat';
            $config['allowed_types']        = 'pdf|gif|jpg|jpeg|png|JPEG|JPG|PNG';
            $config['max_size']             = 2048;
            $config['remove_spaces'] = TRUE;
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('bukti_masuk')) {
                $err_code++;
                $message = "Gagal upload file: " . $this->upload->display_errors();
            } else {

                $upload_data = $this->upload->data();
                $fileName = $upload_data['file_name'];
                $file_path = $config['upload_path'] . $upload_data['file_name'];
            }
        }

        $dataRes = [
            'err_code' => $err_code,
            'message' => $message,
            'data' => $data,
            'filename' => $fileName,
        ];

        return $dataRes;
    }

    public function lihat_masukcacat_model($id_masuk_cacat)
    {
        $where = ['tbl_masuk_cacat.id_masuk_cacat' => base64_decode($id_masuk_cacat)];

        $this->view["data_masuk_cacat"] = $this->masuk_cacat->get_where_BarangMasukCacat($where);

        $this->load->view("barang_keluar/v_lihat_Modal", $this->view);
    }
}
