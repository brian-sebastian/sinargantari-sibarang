<?php

use PHPUnit\Util\Json;

/**
 * @property Barang_model $barang
 * @property Barang_history_model $barang_history
 * @property Kategori_model $kategori
 * @property Satuan_model $satuan
 * @property Logfile_model $logfile
 * @property form_validation $form_validation
 * @property db $db
 * @property input $input
 * @property session $session
 * @property zend $zend
 * @property upload $upload
 */
class Barang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Barang_model', 'barang');
        $this->load->model('Barang_history_model', 'barang_history');
        $this->load->model('Kategori_model', 'kategori');
        $this->load->model('Satuan_model', 'satuan');
        $this->load->model('Logfile_model', 'logfile');
    }

    public function index()
    {
       
       

        $data['title_menu'] = "Barang";
        $data['title'] = "Barang";
        $data['barang'] = $this->barang->getAllBarang();
        $data['barang_temp'] = $this->barang->ambilSemuaBarangTemp()->num_rows();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('barang/index', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function ajaxBarang()
    {
        $role_id = $this->session->userdata('role_id');
       
 
        $dataBarang = $this->barang->ambilSemuaBarang();
        $hitungBarang = $this->barang->ambilHitungBarang();
        $filterBarang = $this->barang->ambilFilterBarang();

        $row = [];
        $no  = 1;

        foreach ($dataBarang as $d) {

            $show_button = !in_array($role_id,[18, 21]) ? " <button id='history_barang' class='btn btn-dark btn-sm' data-id='" . $d['id_barang'] . "' data-bs-toggle='modal' data-bs-target='#history_barang_modal'><span class='tf-icons bx bx-money'></span>&nbsp; History Harga</button>" : '';

            $resultCheckDelete = check_request_delete_barang($d['id_barang']);
            $resCheckBarangRejectedOrAccepted = checkBarangRejectedOrAccepted($d['id_barang']);

            $col = [];

            $col[]  = $no;
            $col[]  = $d["nama_barang"];
            $col[]  = (!$d["barcode_barang"]) ? ("<a class='btn btn-info btn-sm' href='" . base_url('barang/create_barcode/') . $d['kode_barang'] . "'><span class='tf-icons bx bx-barcode'></span>&nbsp; Buat Barcode</a>") : ("<img src='" . base_url('assets/barcodes/') . $d['barcode_barang'] . '.png' . "' alt='' srcset=''>");
            $col[]  = "<button id='detail_barang' class='btn btn-info btn-sm' data-id='" . $d['id_barang'] . "' data-bs-toggle='modal' data-bs-target='#lihatBarang_Modal'><span class='tf-icons bx bx-show-alt'></span>&nbsp; Detail</button>
            <button class='btn btn-secondary btn-sm ' onclick='detail_button(" . $d['id_barang'] . ")'><span class='tf-icons bx bx-barcode'></span>&nbsp; Ubah Barcode</button>
            
            <a href='" . base_url('barang/tampilan_edit/') . base64_encode($d['id_barang']) . "' class='btn btn-sm btn-warning'><span class='tf-icons bx bx-edit-alt'></span>&nbsp; Edit</a>
            $show_button
            ";
         

            if ($resultCheckDelete == 1) {
                $contentDisable = '';
                if ($resCheckBarangRejectedOrAccepted['err_code'] == 0) {
                    if ($resCheckBarangRejectedOrAccepted['data']['is_deleted'] == 0) {
                        $contentDisable =  " <button disabled class='btn btn-sm btn-danger removes' ><span class='tf-icons bx bx-trash'></span>&nbsp; Sudah Request Hapus</button>";
                    } elseif ($resCheckBarangRejectedOrAccepted['data']['is_deleted'] == 2) {
                        $contentDisable =  " <button class='btn btn-sm btn-danger re-removes-again' data-id='" . base64_encode($d["id_barang"]) . "' data-idreq='" . base64_encode($resCheckBarangRejectedOrAccepted['data']['id_request']) . "' data-bs-toggle='modal' data-bs-target='#remove_again_from_rejected'><span class='tf-icons bx bx-trash'></span>&nbsp; Request Hapus Lagi</button> ";
                    }
                }
                $col[3] .= $contentDisable;
            } else {

                $contentButton =  " <button class='btn btn-sm btn-danger removes' data-id='" . base64_encode($d["id_barang"]) . "' data-bs-toggle='modal' data-bs-target='#remove_modal'><span class='tf-icons bx bx-trash'></span>&nbsp; Hapus</button>";
                $col[3] .= $contentButton;
            }
            $col[]  = "<input type='checkbox' class='rowBarcode' value='" . $d["id_barang"] . "'>";

            array_push($row, $col);
            $no++;
        }

        $data_json = [

            'draw' => $this->input->post('draw'),
            'data' => $row,
            'recordsTotal' => $hitungBarang,
            'recordsFiltered' => $filterBarang,
        ];

        echo json_encode($data_json);
    }

    public function tambah()
    {
        $field = [
            [
                'field' => 'kode_barang',
                'label' => 'Kode Barang',
                'rules' => 'required'
            ],
            [
                'field' => 'nama_barang',
                'label' => 'Nama Barang',
                // 'rules' => 'required|trim|regex_match[/^(?!0*[.,]?0+$)\d*[.,]?\d+$/m]',
                // 'rules' => 'required|trim|regex_match[/^[a-zA-Z0-9\s]+$/]',
                'rules' => 'required|trim|max_length[100]',
            ],
            [
                'field' => 'barcode_barang',
                'label' => 'Barcode barang',
                'rules' => 'trim|max_length[50]|is_unique[tbl_barang.barcode_barang]'
            ],
            [
                'field' => 'slug_barang',
                'label' => 'Slug Barang',
                'rules' => 'required|trim'
            ],
            [
                'field' => 'kategori_id',
                'label' => 'Kategori',
                'rules' => 'required'
            ],
            [
                'field' => 'berat_barang',
                'label' => 'Berat Barang',
                'rules' => 'required'
            ],
            [
                'field' => 'satuan_id',
                'label' => 'Satuan',
                'rules' => 'required'
            ],
            [
                'field' => 'harga_pokok',
                'label' => 'Harga Pokok',
                'rules' => 'required'
            ],
            [
                'field' => 'deskripsi',
                'label' => 'Deskripsi',
                'rules' => 'required'
            ]

        ];

        $this->form_validation->set_rules($field);
        if ($this->form_validation->run() == FALSE) {
            $data['title_menu']     = "Barang";
            $data['title']          = "Barang";
            $data['barang']         = $this->barang->getAllBarang();
            $data['satuan']         = $this->satuan->ambilSemuaSatuan();
            $data['kategori']       = $this->kategori->ambilSemuaKategori();
            $data['kode_barang']    = $this->createKodeBarang();
            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/navbar', $data);
            $this->load->view('barang/tambah', $data);
            $this->load->view('layout/footer', $data);
            $this->load->view('layout/script', $data);
        } else {
            $err        = FALSE;
            $namefile   = NULL;

            $inputHPP = htmlspecialchars($this->input->post('harga_pokok'));
            $removeSeparator = str_replace(',', '', $inputHPP);

            if (!empty($_FILES["gambar"]["name"])) {

                $config['file_name']            = rand();
                $config['upload_path']          = './assets/file_barang';
                $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
                $config['max_size']             = 3024;

                $this->load->library("upload");
                $this->upload->initialize($config);

                if ($this->upload->do_upload("gambar")) {

                    $namefile = $this->upload->data("file_name");
                } else {
                    $err = true;
                    $this->session->set_flashdata("gambar", $this->upload->display_errors());
                    $data['title_menu']     = "Barang";
                    $data['title']          = "Barang";
                    $data['barang']         = $this->barang->getAllBarang();
                    $data['satuan']         = $this->satuan->ambilSemuaSatuan();
                    $data['kategori']       = $this->kategori->ambilSemuaKategori();
                    $data['kode_barang']    = $this->createKodeBarang();
                    $this->load->view('layout/header', $data);
                    $this->load->view('layout/sidebar', $data);
                    $this->load->view('layout/navbar', $data);
                    $this->load->view('barang/tambah', $data);
                    $this->load->view('layout/footer', $data);
                    $this->load->view('layout/script', $data);
                }
            }

            if (!$err) {

                $data = [

                    'kode_barang'       => htmlspecialchars($this->input->post('kode_barang')),
                    'nama_barang'       => htmlspecialchars($this->input->post('nama_barang')),
                    'slug_barang'       => htmlspecialchars($this->input->post('slug_barang')),
                    'barcode_barang'    => ($this->input->post('barcode_barang')) ? $this->generateBarcode(htmlspecialchars($this->input->post('barcode_barang'))) : $this->generateBarcode(),
                    'kategori_id'       => htmlspecialchars($this->input->post('kategori_id')),
                    'berat_barang'      => htmlspecialchars($this->input->post('berat_barang')),
                    'satuan_id'         => htmlspecialchars($this->input->post('satuan_id')),
                    'harga_pokok'       => $removeSeparator,
                    'gambar'            => ($namefile) ? $namefile : null,
                    'is_active'         => 1,
                    'deskripsi'         => htmlspecialchars($this->input->post('deskripsi')),
                    'user_input'        => $this->session->userdata('username'),
                    'created_at'        => date('Y-m-d H:i:s')

                ];

                $transBarang = $this->barang->tambah_data($data);
                if ($transBarang == false) {
                    $this->session->set_flashdata('message_error', 'Penambahan Data Tidak eligible');
                    redirect('barang');
                } else {
                    $this->session->set_flashdata('message', 'Data berhasil di simpan');
                    redirect('barang');
                }
            }
        }
    }

    private function createKodeBarang()
    {
        $query_max_pendaftaran = $this->barang->getKodeBarang();
        $kode = "";
        if ($query_max_pendaftaran->num_rows() > 0) {
            //cek kode jika telah tersedia    
            foreach ($query_max_pendaftaran->result_array() as $k) {
                $tmp = ((int)$k['kode_barang']) + 1;
                $kode = sprintf("%06s", $tmp);
            }
        } else {
            $kode = "000001";
        }

        $batas = str_pad($kode, 6, "0", STR_PAD_LEFT);
        // $prefix = "BRG-000001";
        $prefix = "BRG-";

        $kodebaru = $prefix . $batas;
        return $kodebaru;
    }

    public function tampilan_edit($id)
    {
        $data['title_menu'] = "Barang";
        $data['title'] = "Barang";
        $data['satuan'] = $this->satuan->ambilSemuaSatuan();
        $data['kategori'] = $this->kategori->ambilSemuaKategori();
        $data['barang'] = $this->barang->getFindById(base64_decode($id));

        if ($data['barang']) {

            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/navbar', $data);
            $this->load->view('barang/edit', $data);
            $this->load->view('layout/footer', $data);
            $this->load->view('layout/script', $data);
        } else {

            $this->session->set_flashdata("message_error", "Data tidak ditemukan");
            redirect("barang");
        }
    }

    public function detail()
    {
        $id_barang = $this->input->get('id_barang');
        $getDetailBarang = $this->barang->ambilDataBarangIdJoin($id_barang);
        $data['data_barang'] = $getDetailBarang;
        $view = $this->load->view("barang/lihat", $data, true);
        $result = [
            'err_code' => 0,
            'view' => $view,
        ];

        echo json_encode($result);
    }

    public function edit()
    {
        $id = $this->input->post('id_barang');

        $field = [
            [
                'field' => 'nama_barang',
                'label' => 'Nama Barang',
                // 'rules' => 'required|trim|regex_match[/^(?!0*[.,]?0+$)\d*[.,]?\d+$/m]',
                // 'rules' => 'required|trim|regex_match[/^[a-zA-Z0-9\s]+$/]',
                'rules' => 'required|trim|max_length[100]',
            ],
            [
                'field' => 'kategori_id',
                'label' => 'Kategori',
                'rules' => 'required'
            ],
            [
                'field' => 'satuan_id',
                'label' => 'Satuan',
                'rules' => 'required'
            ],
            [
                'field' => 'harga_pokok',
                'label' => 'Harga Pokok',
                'rules' => 'required'
            ],

        ];

        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == FALSE) {

            $data['title_menu'] = "Barang";
            $data['title']      = "Barang";
            $data['satuan']     = $this->satuan->ambilSemuaSatuan();
            $data['kategori']   = $this->kategori->ambilSemuaKategori();
            $data['barang']     = $this->barang->getFindById($id);


            $this->load->view('layout/header', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/navbar', $data);
            $this->load->view('barang/edit', $data);
            $this->load->view('layout/footer', $data);
            $this->load->view('layout/script', $data);
        } else {

            $err        = FALSE;
            $namefile   = htmlspecialchars($this->input->post("gambar_old"));

            $inputHPP   = htmlspecialchars($this->input->post('harga_pokok'));
            $removeSeparator = str_replace(',', '', $inputHPP);

            if (!empty($_FILES["gambar"]["name"])) {

                $config['file_name']            = rand();
                $config['upload_path']          = './assets/file_barang';
                $config['allowed_types']        = 'jpg|jpeg|png|JPG|JPEG|PNG';
                $config['max_size']             = 3024;

                $this->load->library("upload");
                $this->upload->initialize($config);

                if ($this->upload->do_upload("gambar")) {

                    if ($namefile) {

                        if (file_exists("./assets/file_barang/" . $namefile)) {

                            unlink("./assets/file_barang/" . $namefile);
                        }
                    }

                    $namefile = $this->upload->data("file_name");
                } else {

                    $err = true;

                    $this->session->set_flashdata("gambar", $this->upload->display_errors());

                    $data['title_menu'] = "Barang";
                    $data['title']      = "Barang";
                    $data['satuan']     = $this->satuan->ambilSemuaSatuan();
                    $data['kategori']   = $this->kategori->ambilSemuaKategori();
                    $data['barang']     = $this->barang->getFindById($id);


                    $this->load->view('layout/header', $data);
                    $this->load->view('layout/sidebar', $data);
                    $this->load->view('layout/navbar', $data);
                    $this->load->view('barang/edit', $data);
                    $this->load->view('layout/footer', $data);
                    $this->load->view('layout/script', $data);
                }
            }

            if (!$err) {

                $data = [
                    'nama_barang'   => htmlspecialchars($this->input->post('nama_barang')),
                    'slug_barang'   => htmlspecialchars($this->input->post('slug_barang')),
                    'kategori_id'   => htmlspecialchars($this->input->post('kategori_id')),
                    'berat_barang'  => htmlspecialchars($this->input->post('berat_barang')),
                    'satuan_id'     => htmlspecialchars($this->input->post('satuan_id')),
                    'harga_pokok'   => $removeSeparator,
                    'gambar'        => ($namefile) ? $namefile : null,
                    'deskripsi'     => htmlspecialchars($this->input->post('deskripsi')),
                    'user_update'   => $this->session->userdata('username'),
                    'updated_at'    => date('Y-m-d H:i:s')
                ];

                $resultEdit = $this->barang->edit_data($id, $data);
                if ($resultEdit == true) {
                    $this->session->set_flashdata('message', 'Data berhasil di simpan');
                    redirect('barang');
                } else {
                    $this->session->set_flashdata('message_error', 'Edit Barang Tidak Eligible');
                    redirect('barang');
                }
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
                $type_request = DELETE_REQUEST_BARANG;
                $requestDelete = request_delete($id, $keterangan, $type_request);
                if ($requestDelete['err_code'] == 0) {
                    $err_code = 0;
                    $message = $requestDelete['message'];
                } else {
                    $err_code++;
                    $message = "Request Delete Gagal";
                }
            }
        } else {
            $this->barang->hapus_data(base64_decode($id));
            $err_code = 0;
            $message = "Hapus Barang Berhasil";
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
                $type_request = DELETE_REQUEST_BARANG;
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

    private function generateBarcode($custom_code = "")
    {
        $data = [];
        $code = ($custom_code) ? $custom_code : $this->generateCode(12);
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $code), array())->draw();
        imagepng($imageResource, 'assets/barcodes/' . $code . '.png');

        $data['barcode'] = 'assets/barcodes/' . $code . '.png';
        return $code;
    }

    public function create_barcode($kode_barang)
    {
        $data = [];
        $code = $this->generateCode(12);
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $imageResource = Zend_Barcode::factory('code128', 'image', array('text' => $code), array())->draw();
        imagepng($imageResource, 'assets/barcodes/' . $code . '.png');
        $data['barcode'] = 'assets/barcodes/' . $code . '.png';

        $this->barang->update_barcode($code, $kode_barang);

        $this->session->set_flashdata('success', 'Barcode berhasil dibuat');
        redirect('barang/index');
    }

    public function generate_gambar_barcode($barcode)
    {
        $data = [];
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $imageResource = Zend_Barcode::draw('code128', 'image', array('text' => $barcode), array());
        imagepng($imageResource, 'assets/barcodes/' . $barcode . '.png');
        $data['barcode'] = 'assets/barcodes/' . $barcode . '.png';
        return true;
    }

    private function generateCode($limit)
    {
        $code = '';
        for ($i = 0; $i < $limit; $i++) {
            $code .= mt_rand(0, 9);
        }
        return $code;
    }


    public function tampilan_edit_barcode($id)
    {
        $res = $this->barang->getFindById(base64_decode($id));

        if ($res) {

            $data_json["status"]    = "berhasil";
            $data_json["response"]  = $res;
        } else {

            $data_json["status"]    = "gagal";
            $data_json["response"]  = "Data tidak ada";
        }

        echo json_encode($data_json);
    }

    public function edit_barcode()
    {
        $field = [
            [
                "field" => "id_barang",
                "label" => "barang id",
                "rules" => "required|trim"
            ],

            [
                'field' => 'barcode_barang',
                'label' => 'Barcode Barang',
                'rules' => 'required|trim|max_length[100]',
            ],

            [
                'field' => 'barcode_barang_lama',
                'label' => 'Barcode barang lama',
                'rules' => 'required|trim|max_length[100]',
            ]

        ];


        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == TRUE) {

            $id = htmlspecialchars($this->input->post('id_barang'));

            $data = [
                'barcode_barang'        => htmlspecialchars($this->input->post('barcode_barang')),
                'user_update'           => $this->session->userdata('username'),
                'updated_at'            => date('Y-m-d H:i:s')
            ];

            if ($this->barang->editBarcode($id, $data)) {

                if (htmlspecialchars($this->input->post("barcode_barang_lama") == htmlspecialchars($this->input->post("barcode_barang")))) {

                    $old_barcode = htmlspecialchars($this->input->post("barcode_barang_lama"));

                    if ($old_barcode) {

                        if (file_exists('./assets/barcodes/' . $old_barcode)) {

                            unlink('./assets/barcodes/' . $old_barcode . '.png');
                        }
                    }
                }

                $this->generateBarcode(htmlspecialchars($this->input->post("barcode_barang")));

                $data_json['status']    = "berhasil";
                $data_json['response']  = "data berhasil di ubah";
            } else {

                $data_json['status']    = "gagal";
                $data_json['response']  = "data berhasil di ubah";
            }
        } else {
            $data_json['status']                = "error";
            $data_json['err_barcode_barang_edit']    = form_error('barcode_barang');
        }
        echo json_encode($data_json);
    }

    public function doImportBarang()
    {
        $fileBarang = $_FILES['file_barang'];

        if ($fileBarang['name'] == "" || $fileBarang['error'] == 4 || ($fileBarang['size'] == 0 && $fileBarang['error'] == 0)) {
            $this->session->set_flashdata('message_error', 'Tidak Ada File yang diupload');
            redirect('barang/list');
        }


        if (!empty($fileBarang)) {
            if ($fileBarang['size'] > 5000000) {
                // 5 Mb maximum Mb -> Byte
                $this->session->set_flashdata('message_error', 'Maximum File 5 Mb');
                redirect('barang/list');
            }
            $this->load->library('Excel_library', array('data' => $_FILES, 'func' => 'import', 'jenis' => 'barang'));
        } else {
            $this->session->set_flashdata('message_error', 'Tidak Ada File yang diupload');
            redirect('barang/list');
        }

        if (!empty($_FILES['file_barang']['name'])) {

            $data = [
                'path' => realpath($_FILES['file_barang']['tmp_name']),
                'file' => $_FILES['file_barang']['name'],
                'wk_input'  => date('Y-m-d H:i:s')
            ];

            $res = $this->logfile->insertLog($data);

            if ($res) {

                $this->load->library('Excel_library', array('data' => $_FILES, 'func' => 'import', 'jenis' => 'barang'));
            } else {
                $this->session->set_flashdata('message_error', 'Gagal dalam menyimpan path file');
                redirect('barang/list', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message_error', 'Tidak Ada File yang diupload');
            redirect('barang/list');
        }
    }


    public function upload_config($path)
    {
        if (!is_dir($path))
            mkdir($path, 0777, TRUE);
        $config['upload_path']         = './' . $path;
        $config['allowed_types']     = 'csv|CSV|xlsx|XLSX|xls|XLS';
        $config['max_filename']         = '255';
        $config['encrypt_name']     = TRUE;
        $config['max_size']         = 4096;
        $this->load->library('upload', $config);
    }

    public function getHistoryPriceBarang()
    {
        $id = $this->input->get('barang_id');

        $data['history_barang'] = $this->barang_history->getBarangHistoryId($id);
        $dataNumRows = $this->barang_history->getBarangHistoryIdNumRows($id);
        $viewBarangHistory = $this->load->view('barang/barang_history', $data, TRUE);

        $result = [
            'total' => $dataNumRows,
            'view' => $viewBarangHistory
        ];

        echo json_encode($result);
    }

    public function temp()
    {
        $data['title_menu'] = "Barang";
        $data['title'] = "Barang";
        $data['barang_temp'] = $this->barang->ambilSemuaBarangTemp()->result_array();


        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/navbar', $data);
        $this->load->view('barang/temp', $data);
        $this->load->view('layout/footer', $data);
        $this->load->view('layout/script', $data);
    }

    public function cetakBarcode()
    {

        if (isset($_GET["token"]) && $_GET["token"]) {

            $token = base64_decode($_GET["token"]);

            $listIdBarang = explode("_", $token);

            unset($listIdBarang[count($listIdBarang) - 1]);

            $result = $this->barang->getBarcodeByListId($listIdBarang);

            $this->load->view("barang/cetak_barcode", [
                "data" => $result
            ]);
        } else {

            $this->session->set_flashdata("message_error", "Pilih data terlebih dahulu");
            redirect("barang/list");
        }
    }
}
