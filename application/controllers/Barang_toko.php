<?php

/**
 * @property Barang_model $barang
 * @property Harga_model $harga
 * @property Kategori_model $kategori
 * @property Barang_history_model $barang_history
 * @property Satuan_model $satuan
 * @property Toko_model $toko
 * @property Logfile_model $logfile
 * @property User_model $user
 * @property form_validation $form_validation
 * @property db $db
 * @property input $input
 * @property session $session
 * @property zend $zend
 * @property upload $upload
 * @property secure $secure
 * @property datelib $datelib
 * @property user $user
 */
class Barang_toko extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Barang_model', 'barang');
        $this->load->model('Barang_history_model', 'barang_history');
        $this->load->model('Harga_model', 'harga');
        $this->load->model('Toko_model', 'toko');
        $this->load->model('Logfile_model', 'logfile');
        $this->load->model('User_model', 'user');
        $this->load->model('Kategori_model', 'kategori');
        $this->datelib->asiaJakartaDate();
        cek_login();
    }

    public function index()
    {

        $data['title_menu'] = "Barang";
        $data['title']      = "Barang Toko";
        $data['toko']       = $this->toko->ambilSemuaToko();

        if ($this->session->userdata('toko_id')) {
            // $role_id = $this->session->userdata('role_id');
            $toko_id                  = $this->session->userdata('toko_id');
            
            $tokoku = $this->db->where("id_toko", $toko_id)->get("tbl_toko")->row_array();

            $data['title']      = "Barang Toko ".$tokoku["nama_toko"];

            // $data['admin_toko_id']          = $role_id;
            $data['toko_id']          = $toko_id;
            $data['harga_barang']     = $this->barang->getBarangHargaToko($toko_id);
            $data['harga_temp']       = $this->harga->ambilBarangTemp($toko_id)->num_rows();
            $data['kategori_barang']  = $this->kategori->ambilSemuaKategori();
        } else {
            if ($this->input->get('toko')) {

                // $role_id = $this->session->userdata('role_id');
                $id_toko                = $this->input->get('toko');
                $decrypt_id             = $this->secure->decrypt_url($id_toko);
                
                $tokoku = $this->db->where("id_toko", $decrypt_id)->get("tbl_toko")->row_array();
                
                $data['title']      = "Barang Toko ".$tokoku["nama_toko"];
                
                // $data['admin_toko_id']          = $role_id;
                $data["data_toko"]      = $this->toko->ambilDetailToko($decrypt_id);
                $data['harga_barang']   = $this->barang->getHargaBarangToko($decrypt_id);
                $data['harga_temp']     = $this->harga->ambilBarangTemp($decrypt_id)->num_rows();
                $data['toko_id']        = $decrypt_id;
                $data['kategori_barang'] = $this->kategori->ambilSemuaKategori();
                
                 $id_category = $this->input->post('category');
               

                if($id_category){
                    $data['kategori_id'] = $id_category;
                }else{
                    $data['kategori_id'] = '';
                }

                if (!$data["data_toko"]) {
                    redirect("auth/logout");
                }
            } else {
                $data['barang']     = $this->barang->getAllBarang();
                $data['toko_id']    = null;
                $username           = $this->session->userdata('username');
                $user               = $this->user->findByUsername($username);

                if ($user['role_id'] == 1) {
                    $data['harga_barang'] = $this->barang->getHargaBarangTokoAll();
                } else {
                    $data['harga_barang'] = $this->barang->getHargaBarangFindUsername();
                }
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('barang_toko/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function ajaxBarangToko()
    {
        // $role_id = $this->session->userdata('role_id');
     
        $toko_id = $this->input->post("toko_id");
        $enkripsi_toko_id = $this->secure->encrypt_url($toko_id);
        $kategori_id = $this->input->post('kategori_id');
        
        $data = $this->barang->ambilSemuaBarangToko($toko_id, $kategori_id);
        $hitungBarangToko = $this->barang->ambilHitungBarangToko($toko_id);
        $filterBarangToko = $this->barang->ambilFilterBarangToko($toko_id);

        $row = [];
        $no = 1;

        foreach ($data as $d) {
            // $harga_pokok = (!in_array($role_id, [18, 21]));

            $stok_tersedia = $d['stok_toko'] + $d['stok_gudang'];

            $resultCheckDelete = check_request_delete_barang_toko($d['id_harga']);
            $resultCheckRejectedOrAccepted = checkRejectedOrAccepted($d['id_harga']);

            $col = [];

            $col[]  = $no;
            $col[] = "<input type='checkbox' class='form-check-input checkbox_data' value='" . $d['id_harga'] . "'>";
            $col[]  = ($d["barcode_barang"] == null) ? "<a class='btn btn-info btn-sm' href='" . base_url('barang/create_barcode/') . $d['kode_barang'] . "'>Create Barcode</a>" : "<img src='" . base_url('assets/barcodes/') . $d['barcode_barang'] . '.png' . "' alt='' srcset=''>";
            $col[]  = $d["nama_barang"];
            $col[]  = $d["nama_kategori"];
            // if($harga_pokok){
                $col[]  = "Rp" . number_format($d['harga_pokok']);
            // }
            $col[]  = "Rp" . number_format($d['harga_jual']);
            $col[] = $d['stok_toko'];
            $col[] = (!empty($d['stok_gudang'])) ? $d['stok_gudang'] : 0;
            $col[]  = $stok_tersedia;
            $col[]  = $d['berat_barang'] . " " . $d['satuan'];
            $col[]  = "<a href='" . base_url("barang/barang_toko/ubah/") . base64_encode($d['id_harga']) . "?toko=$enkripsi_toko_id" . "' class='btn btn-sm btn-warning'><i class='bx bx-edit-alt me-1'></i>&nbsp; Edit</a>
                    <a href='" . base_url("barang/barang_toko/status/" . base64_encode((($d['is_active'] == 1) ? 0 : 1) . "-" . $d['id_harga']) . "?toko=$enkripsi_toko_id") . "' class='btn btn-sm btn-info'><i class='bx bx-block me-1'></i>" . (($d['is_active'] == 1) ? 'Nonaktifkan' : 'Aktifkan') . "</a>
                    <button id='history_barang_toko' class='btn btn-secondary btn-sm' data-barangid='" . $d['barang_id'] . "' data-idtoko='" . $toko_id . "' data-idharga='" . $d['id_harga'] . "' data-bs-toggle='modal' data-bs-target='#history_barang_toko_modal'><span class='tf-icons bx bx-money'></span>&nbsp; History Harga</button> ";
            if ($resultCheckDelete == 1) {
                $contentDisable =  " ";
                if ($resultCheckRejectedOrAccepted['err_code'] == 0) {
                    if ($resultCheckRejectedOrAccepted['data']['is_deleted'] == 0) {
                        $contentDisable =  " <button disabled class='btn btn-sm btn-danger removes' ><span class='tf-icons bx bx-trash'></span>&nbsp; Sudah Request Hapus</button>";
                    } elseif ($resultCheckRejectedOrAccepted['data']['is_deleted'] == 2) {
                        $contentDisable =  " <button class='btn btn-sm btn-danger re-removes-again' data-id='" . base64_encode($d["id_harga"]) . "' data-idreq='" . base64_encode($resultCheckRejectedOrAccepted['data']['id_request']) . "' data-bs-toggle='modal' data-bs-target='#remove_again_from_rejected'><span class='tf-icons bx bx-trash'></span>&nbsp; Request Hapus Lagi</button> ";
                    }
                }
                $col[11] .= $contentDisable;
            } else {
                $contentButton =  " <button class='btn btn-sm btn-danger removes' data-id='" . base64_encode($d["id_harga"]) . "' data-bs-toggle='modal' data-bs-target='#remove_modal'><span class='tf-icons bx bx-trash'></span>&nbsp; Hapus</button>";
                $col[11] .= $contentButton;
            }

            array_push($row, $col);
            $no++;
        }


        $data_json = [
            'draw' => $this->input->post('draw'),
            'data' => $row,
            'recordsTotal' => $hitungBarangToko,
            'recordsFiltered' => $filterBarangToko,
        ];

        echo json_encode($data_json);
    }

    public function tambah()
    {

        $this->form_validation->set_rules(
            "barang_id",
            "barang",
            "required",
            [
                "required" => "%s wajib diisi"
            ]
        );
        $this->form_validation->set_rules(
            "toko_id",
            "toko",
            "required",
            [
                "required" => "%s wajib diisi"
            ]
        );
        $this->form_validation->set_rules(
            "harga_jual",
            "harga",
            "required|max_length[20]",
            [
                "required" => "%s wajib diisi",
                "max_length" => "%s maksimal 20 karakter"
            ]
        );
        $this->form_validation->set_rules(
            "stok_toko",
            "stok",
            "required|max_length[20]",
            [
                "required" => "%s wajib diisi",
                "max_length" => "%s maksimal 20 karakter"
            ]
        );
        if ($this->form_validation->run() == false) {

            $data['title_menu'] = "Barang";
            $data['title']      = "Barang Toko";
            $data['toko']       = $this->toko->ambilSemuaToko();

            if (!$this->input->get('toko')) {

                $this->session->set_flashdata('message_error', 'Maaf Kamu Belum Memilih Toko');
                redirect('barang/barang_toko');
            } else {

                $id_toko            = $this->input->get('toko');
                $decrypt_id         = $this->secure->decrypt_url($id_toko);
                $data['toko_id']    = $decrypt_id;
                $data['barang']     = $this->barang->getAllBarangNotInHarga($data['toko_id']);
            }
            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/navbar', $data);
            $this->load->view('barang_toko/tambah', $data);
            $this->load->view('layout/footer', $data);
            $this->load->view('layout/script', $data);
        } else {

            $data = [
                "barang_id"     => htmlspecialchars($this->input->post("barang_id")),
                "toko_id"       => htmlspecialchars($this->input->post("toko_id")),
                "harga_jual"    => htmlspecialchars($this->input->post("harga_jual")),
                "stok_toko"     => htmlspecialchars($this->input->post("stok_toko")),
                "is_active"     => 1,
                "user_input"    => $this->session->userdata("nama"),
                "created_at"    => date("Y-m-d H:i:s"),
            ];

            $toko_id_encrypt = htmlspecialchars($this->input->post("toko_id"));
            $encrypt_id_toko = $this->secure->encrypt_url($toko_id_encrypt);

            $harga_pokok  = $this->barang->getFindById($this->input->post('barang_id'));
            if ($harga_pokok['harga_pokok'] < $data['harga_jual']) {
                $resultAddBarangToko = $this->harga->tambahBarangToko($data);

                if ($resultAddBarangToko == true) {

                    $this->session->set_flashdata('message', 'Data berhasil ditambah');
                    ($this->session->userdata("toko_id")) ? redirect('barang/barang_toko') : "";
                    redirect('barang/barang_toko?toko=' . $encrypt_id_toko);
                } else {
                    $this->session->set_flashdata('message_error', 'Data gagal ditambahkan');
                    ($this->session->userdata("toko_id")) ? redirect('barang/barang_toko') : "";
                    redirect('barang/barang_toko?toko=' . $encrypt_id_toko);
                }
            } else {
                $this->session->set_flashdata('message_error', 'Harga Jual Kurang');
                ($this->session->userdata("toko_id")) ? redirect('barang/barang_toko/tambah?toko=' . $encrypt_id_toko) : "";
                redirect('barang/barang_toko/tambah?toko=' . $encrypt_id_toko);
            }
        }
    }

    public function ubah($id)
    {
        $this->form_validation->set_rules(
            'nama_barang',
            'Nama Barang',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'barang_id',
            'Barang',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'toko_id',
            'Toko',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'harga_jual',
            'Harga Jual',
            'required|max_length[100]'
        );
        $this->form_validation->set_rules(
            'stok_toko',
            'Stok',
            'required|max_length[100]'
        );

        if ($this->form_validation->run() == FALSE) {

            $data['title_menu'] = "Barang";
            $data['title']      = "Barang Toko";
            $data['toko']       = $this->toko->ambilSemuaToko();

            $data['decode_harga_id'] = base64_decode($id);

            $getHarga = $this->harga->getTableHargaId($data['decode_harga_id']);
            if ($getHarga) {
                $getBarang      = $this->barang->getFindById($getHarga['barang_id']);
                $data['harga']  = $getHarga;
                $data['barang'] = $getBarang;
            }
            if (!$this->input->get('toko')) {
                $this->session->set_flashdata('message_error', 'Maaf Kamu Belum Memilih Toko');
                redirect('barang/barang_toko');
            } else {
                $id_toko            = $this->input->get('toko');
                $decrypt_id         = $this->secure->decrypt_url($id_toko);
                $data['toko_id']    = $decrypt_id;
            }
            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/navbar', $data);
            $this->load->view('barang_toko/ubah', $data);
            $this->load->view('layout/footer', $data);
            $this->load->view('layout/script', $data);
        } else {
            $data = [
                'nama_barang'   => htmlspecialchars($this->input->post('nama_barang')),
                'id_harga'      => htmlspecialchars($this->input->post('id_harga')),
                'barang_id'     => htmlspecialchars($this->input->post('barang_id')),
                'toko_id'       => htmlspecialchars($this->input->post('toko_id')),
                'harga_jual'    => htmlspecialchars($this->input->post('harga_jual')),
                'stok_toko'     => htmlspecialchars($this->input->post('stok_toko')),
            ];

            $harga_pokok  = $this->barang->getFindById($this->input->post('barang_id'));

            if ($harga_pokok['harga_pokok'] < $data['harga_jual']) {

                $resultUpdate = $this->harga->updateHargaToko($data);

                if ($resultUpdate == true) {
                    $this->session->set_flashdata('message', 'Data berhasil diubah');
                    $toko_id_encrypt = htmlspecialchars($this->input->post("toko_id"));
                    $encrypt_id_toko = $this->secure->encrypt_url($toko_id_encrypt);
                    ($this->session->userdata("toko_id")) ? redirect('barang/barang_toko') : "";
                    redirect('barang/barang_toko?toko=' . $encrypt_id_toko);
                } else {
                    $this->session->set_flashdata('message_error', 'Maaf Data Gagal Ditambahkan');
                    $toko_id_encrypt = htmlspecialchars($this->input->post("toko_id"));
                    $encrypt_id_toko = $this->secure->encrypt_url($toko_id_encrypt);
                    ($this->session->userdata("toko_id")) ? redirect('barang/barang_toko') : "";
                    redirect('barang/barang_toko?toko=' . $encrypt_id_toko);
                }
            } else {
                $this->session->set_flashdata('message_error', 'Harga Jual Kurang');
                $toko_id_encrypt = htmlspecialchars($this->input->post("toko_id"));
                $encrypt_id_toko = $this->secure->encrypt_url($toko_id_encrypt);
                ($this->session->userdata("toko_id")) ? redirect('barang/barang_toko/ubah/' . $id . '?toko=' . $encrypt_id_toko) : "";
                redirect('barang/barang_toko/ubah/' . $id . '?toko=' . $encrypt_id_toko);
            }
        }
    }

    public function hapus($id)
    {
        $err_code = 0;
        $message = "";

        if ($this->session->userdata('toko_id')) {
            $this->form_validation->set_rules(
                'keterangan',
                'Keterangan',
                'required|max_length[100]'
            );

            if ($this->form_validation->run() == false) {
                $err_code++;
                $message = validation_errors();
            } else {
                $id = base64_decode($id);
                $keterangan = htmlspecialchars($this->input->post('keterangan'));
                $type_request = DELETE_REQUEST_BARANG_TOKO;
                $requestDelete = request_delete($id, $keterangan, $type_request);
                if ($requestDelete['err_code'] == 0) {
                    $err_code = 0;
                    $message = $requestDelete['message'];
                } else {
                    $err_code++;
                    $message = "Request Delete Gagal";
                }
                $this->session->set_flashdata("message_error", $message);
            }
        } else {
            if (!$this->harga->cekHargaPadaOrderDetail(base64_decode($id))) {

                $message = "Data telah digunakan";
                $this->session->set_flashdata("message_error", $message);
            } else {

                $res = $this->harga->hapusBarangMelaluiHargaId(base64_decode($id));

                if ($res) {

                    $message = "Data berhasil di hapus";
                    $this->session->set_flashdata("message", $message);
                } else {

                    $message = "Data gagal di hapus";
                    $this->session->set_flashdata("message_error", $message);
                }
            }
        }

        $resultDelete = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        echo json_encode($resultDelete);

        // $this->session->set_flashdata('message', 'Data berhasil di hapus');
        // redirect('barang');
    }
    public function request_hapus_again($id)
    {
        $err_code = 0;
        $message = "";

        $id_request_reremove = $this->input->post('id_request_reremove');

        if ($this->session->userdata('toko_id')) {
            $this->form_validation->set_rules(
                'keterangan',
                'Keterangan',
                'required|max_length[100]'
            );

            if ($this->form_validation->run() == false) {
                $err_code++;
                $message = validation_errors();
            } else {
                $id = base64_decode($id);
                $id_request = base64_decode($id_request_reremove);
                $keterangan = htmlspecialchars($this->input->post('keterangan'));
                $type_request = DELETE_REQUEST_BARANG_TOKO;
                $requestDelete = re_request_delete($id_request, $keterangan, $type_request);
                if ($requestDelete['err_code'] == 0) {
                    $err_code = 0;
                    $message = $requestDelete['message'];
                } else {
                    $err_code++;
                    $message = "Request Delete Gagal";
                }
                $this->session->set_flashdata("message_error", $message);
            }
        } else {
            if (!$this->harga->cekHargaPadaOrderDetail(base64_decode($id))) {

                $message = "Data telah digunakan";
                $this->session->set_flashdata("message_error", $message);
            } else {

                $res = $this->harga->hapusBarangMelaluiHargaId(base64_decode($id));

                if ($res) {

                    $message = "Data berhasil di hapus";
                    $this->session->set_flashdata("message", $message);
                } else {

                    $message = "Data gagal di hapus";
                    $this->session->set_flashdata("message_error", $message);
                }
            }
        }

        $resultDelete = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        echo json_encode($resultDelete);

        // $this->session->set_flashdata('message', 'Data berhasil di hapus');
        // redirect('barang');
    }

    public function deleteSelectedItems()
    {
        $err_code = 0;
        $message = '';
        $selectedIds = $this->input->post('ids');
        $toko = $this->input->post('toko');

        $decrypt_toko = $this->secure->decrypt_url($toko);

        $selectedIds = is_array($selectedIds) ? $selectedIds : [$selectedIds];


        $checkRelation = checkRelationTableMultiple('tbl_order_detail', 'harga_id', $selectedIds);
        if ($checkRelation['err_code'] == 0) {
            $doDelete = $this->harga->deleteHargaBySelectedItems($selectedIds, $decrypt_toko);
            if ($doDelete) {
                $err_code = 0;
                $message = "Data Berhasil Dihapus";
            } else {
                $err_code++;
                $message = "Data Gagal Dihapus";
            }
        } else {
            $err_code++;
            $message = $checkRelation['message'];
        }
        $dataResult = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        echo json_encode($dataResult);
    }

    public function status($param)
    {
        $param = base64_decode($param);
        $param = explode("-", $param);

        $res = $this->harga->ubahStatusHargaMelaluiIdDanStatus($param);

        if ($res) {

            $this->session->set_flashdata("message", "Data berhasil di " . (($param[0] == 1) ? "aktifkan" : "nonaktifkan"));
            ($this->session->userdata("toko_id")) ? redirect('barang/barang_toko') : "";
            redirect("barang/barang_toko?toko=" . $_GET["toko"]);
        } else {

            $this->session->set_flashdata("message_error", "Data gagal di " . (($param[0] == 1) ? "aktifkan" : "nonaktifkan"));
            ($this->session->userdata("toko_id")) ? redirect('barang/barang_toko') : "";
            redirect("barang/barang_toko?toko=" . $_GET["toko"]);
        }
    }

    public function importExcel()
    {

        $form_validation = $this->form_validation;

        $field = [

            [
                "field"     => "toko_id",
                "label"     => "Toko",
                "rules"     => "required|trim"
            ]

        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $fileBarangToko = $_FILES['file_barang'];

            if ($fileBarangToko['name'] == "" || $fileBarangToko['error'] == 4 || ($fileBarangToko['size'] == 0 && $fileBarangToko['error'] == 0)) {

                $this->session->set_flashdata('message_error', 'Tidak Ada File yang diupload');
                $toko_id = $this->secure->encrypt_url(htmlspecialchars($this->input->post("toko_id")));
                ($this->session->userdata("toko_id")) ? redirect("barang/barang_toko") : redirect("barang/barang_toko?toko='$toko_id'");
            }

            if (!empty($fileBarangToko)) {
                if ($fileBarangToko['size'] > 5000000) {

                    $this->session->set_flashdata('message_error', 'Maximum File 5 Mb');
                    $toko_id = $this->secure->encrypt_url(htmlspecialchars($this->input->post("toko_id")));
                    ($this->session->userdata("toko_id")) ? redirect("barang/barang_toko") : redirect("barang/barang_toko?toko='$toko_id'");
                }
            } else {

                $this->session->set_flashdata('message_error', 'Tidak ada file yang di upload');
                $toko_id = $this->secure->encrypt_url(htmlspecialchars($this->input->post("toko_id")));
                ($this->session->userdata("toko_id")) ? redirect("barang/barang_toko") : redirect("barang/barang_toko?toko='$toko_id'");
            }

            if (!empty($_FILES['file_barang']['name'])) {

                $data = [
                    'path' => realpath($_FILES['file_barang']['tmp_name']),
                    'file' => $_FILES['file_barang']['name'],
                    'wk_input' =>  $this->datelib->asiaJakartaDate('Y-m-d H:i:s')
                ];

                $res = $this->logfile->insertLog($data);

                if ($res) {

                    $this->load->library('Excel_library', array('data' => $_FILES, 'func' => 'import', 'jenis' => 'barang_toko', 'toko_id'  => htmlspecialchars($this->input->post("toko_id"))));
                } else {

                    $this->session->set_flashdata('message_error', 'Gagal dalam menyimpan file patch');
                    $toko_id = $this->secure->encrypt_url(htmlspecialchars($this->input->post("toko_id")));
                    ($this->session->userdata("toko_id")) ? redirect("barang/barang_toko") : redirect("barang/barang_toko?toko='$toko_id'");
                }
            }
        } else {

            $this->session->set_flashdata('message_error', validation_errors());
            $toko_id = $this->secure->encrypt_url(htmlspecialchars($this->input->post("toko_id")));
            ($this->session->userdata("toko_id")) ? redirect("barang/barang_toko") : redirect("barang/barang_toko?toko='$toko_id'");
        }
    }

    public function exportExcel()
    {
        $toko_id = "";
        ($this->session->userdata("toko_id")) ? $toko_id = $this->session->userdata("toko_id") : $toko_id = (isset($_GET["toko"]) && !empty($_GET["toko"])) ? $this->secure->decrypt_url($_GET["toko"]) : "";

        if ($toko_id) {

            $result = $this->harga->ambilSemuaBarangTokoSesuaiIdToko($toko_id);

            if (count($result) > 0) {

                $libary_excel = [
                    'func' => 'export',
                    'jenis' => 'barang_toko',
                    'filename' => 'barang_toko_' . strtolower(str_replace(" ", "_", $result[0]["nama_toko"])) . '_' . date('Y_m_d_His') . ".xlsx",
                    'data_barang_toko' => $result,
                ];
                
                $this->load->library('Excel_library', $libary_excel);
            } else {

                $this->session->set_flashdata("message_error", "Data kosong!");
                redirect(($this->session->userdata("toko_id")) ? "barang/barang_toko" : ((isset($_GET["toko"]) && !empty($_GET["toko"])) ? "barang/barang_toko?toko=" . $_GET["toko"] : "barang/barang_toko"));
            }
        } else {

            $this->session->set_flashdata("message_error", "Data tidak ditemukan!");
            redirect(($this->session->userdata("toko_id")) ? "barang/barang_toko" : ((isset($_GET["toko"]) && !empty($_GET["toko"])) ? "barang/barang_toko?toko=" . $_GET["toko"] : "barang/barang_toko"));
        }
    }

    public function getHistoryPriceBarangToko()
    {
        $id_harga = $this->input->get('id_harga');
        $toko_id = $this->input->get('toko_id');
        $barang_id = $this->input->get('barang_id');

        $data['history_barang'] = $this->barang_history->getBarangHistoryHargaTokoId($id_harga, $toko_id, $barang_id);

        $dataNumRows = $this->barang_history->getBarangHistoryHargaTokoIdNumRows($id_harga, $toko_id, $barang_id);
        $viewBarangHistory = $this->load->view('barang_toko/barang_toko_history', $data, TRUE);

        $result = [
            'total' => $dataNumRows,
            'view' => $viewBarangHistory
        ];

        echo json_encode($result);
    }

    public function temp($toko_id)
    {

        $toko_id = $this->secure->decrypt_url($toko_id);

        $data['title_menu']     = "Barang";
        $data['title']          = "Barang Toko";
        $data['toko_id']        = $toko_id;
        $data['harga_temp']     = $this->harga->ambilBarangTemp($toko_id)->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('barang_toko/temp', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }
}
