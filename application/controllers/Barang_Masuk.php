<?php

/**
 * @property Barang_model $Barang_model
 * @property Harga_model $Harga_model
 * @property Barang_masuk_model $Barang_masuk_model
 * @property Toko_model $Toko_model
 * @property Supplier_model $Supplier_model
 * @property form_validation $form_validation
 * @property input $input
 * @property upload $upload
 * @property session $session
 * @property encryption $encryption
 * @property secure $secure
 */
class Barang_Masuk extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Barang_masuk_model');
        $this->load->model('Toko_model');
        $this->load->model('Barang_model');
        $this->load->model('Harga_model');
        $this->load->model('Supplier_model');
    }
    public function index()
    {
        $role_id = $this->session->userdata('role_id');
        $supplier_tampil = (in_array($role_id, [1,2,6]));
        
       
        $this->view['title_menu']   = "Barang";
        $this->view['title']        = "Barang Masuk";
        $this->view['content']      = "barang_masuk/v_barang_masuk_index";
        $this->view['supplier_tampil']  = $supplier_tampil;
        $this->view['barang_masuk'] = $this->Barang_masuk_model->getAllBarangMasuk();
        $this->view['toko']         = $this->Toko_model->ambilSemuaToko();

        $this->load->view('layout/wrapper', $this->view);
    }

    public function ajax_barang_masuk()
    {
        $role_id = $this->session->userdata('role_id');
        $supplier_tampilan = (in_array($role_id, [1,2,6]));
      

        $toko_id = $this->input->post('toko_id');
        $enkripsi_toko_id = $this->secure->encrypt_url($toko_id);
  
        $newArr = [];
        $data = $this->Barang_masuk_model->getAllBarangMasuk();

        

        $hitungTotalBarangMasuk = $this->Barang_masuk_model->ambilHitungTotalBarangMasuk();
        $filterTotalBarangMasuk = $this->Barang_masuk_model->ambilFilterTotalBarangMasuk();
        $no = 1;
       
        foreach ($data as $d) {
           
            if ($d['tipe'] == 'toko_luar') {
                $val = 'Toko Luar';
            } else if ($d['tipe'] == 'antar_toko') {
                $val = 'Antar Toko';
            } else {
                $val = 'Gudang';
            }

            $row = [];
            $row[] = $no;
            $row[] = $d['nama_toko_beli'];
            $row[] = $d['nama_toko'];
            $row[] = $d['nama_barang'];
            $row[] = $d['jml_masuk'];
            $row[] = "<img src='" . base_url('assets/file_barang_masuk/') . $d['bukti_beli'] . "' width='50' height='50'>";
            $row[] = $val;
            if($supplier_tampilan){
                $row[] = $d['nama_supplier'];
                $row[] = $d['no_telpon_supplier'];
            }
            $row[] = date('d M Y', strtotime($d['tanggal_barang_masuk']));
            $row[] = "<a class='btn btn-danger btn-sm' href='" . base_url('barang/masuk/hapus/') . base64_encode($d['id_barang_masuk'])  . "?toko=$enkripsi_toko_id" . "' onclick='return confirm('Apakah ingin menghapus" . $d['nama_toko'] . "')' ><i class='bx bx-trash me-1'></i> Delete</a>";

            array_push($newArr, $row);
            $no++;
        }
        
      

        $data_json = [
            'draw' => $this->input->post('draw'),
            'data' => $newArr,
            'recordsTotal' => $hitungTotalBarangMasuk,
            'recordsFiltered' => $filterTotalBarangMasuk,
        ];

        echo json_encode($data_json);
    }

    public function tambah()
    {
        // var_dump($this->session->userdata('toko_id'));
        // die;

        $getTokoId = $this->input->get('toko_id');
        $getDecryptTokoId = base64_decode($getTokoId);
        $getTokoIdSess = $this->session->userdata('toko_id');

        if (empty($getTokoId) && empty($getTokoIdSess)) {
            redirect('barang/masuk');
        } else {
            $currentTokoId = '';
            if ($getTokoIdSess) {
                $currentTokoId = $getTokoIdSess;
            } else {
                $currentTokoId = $getDecryptTokoId;
            }

            $field = [
                [
                    'field' => 'tipe',
                    'label' => 'Tipe',
                    'rules' => 'required|trim',
                ]
            ];



            $this->form_validation->set_rules($field);

            if ($this->form_validation->run() == TRUE) {

                $typeInput = htmlspecialchars($this->input->post('tipe'));

                if ($typeInput == 'antar_toko') {
                    $resultValidation = $this->validateTypeAntarToko();
                    $hargaIdToko = htmlspecialchars($this->input->post('harga_id_toko'));
                    $jumlahStokMasuk = htmlspecialchars($this->input->post('jml_masuk'));
                    $stokToko = $this->Harga_model->getBarangHargaById($hargaIdToko);
                    if ($resultValidation['err_code'] == 0) {

                        if ($jumlahStokMasuk <= $stokToko['stok_toko']) {
                            // $validationImage = $this->validate_bukti_file($_FILES['bukti_file'], $typeInput);
                            $data = [
                                'dari_toko_by_id_harga' => $hargaIdToko,
                                'stok_toko_dari_by_id_harga' => $stokToko['stok_toko'],
                                'nama_toko_beli' => null,
                                'nomor_supplier' => null,
                                'tanggal_barang_masuk' => htmlspecialchars($this->input->post('tanggal_barang_masuk')),
                                'harga_id' => htmlspecialchars($this->input->post('harga_id_toko')),
                                'ke_toko' => htmlspecialchars($this->input->post('ke_toko')),
                                'jml_masuk' => $jumlahStokMasuk,
                                // 'bukti_beli' => $validationImage['data'],
                                'bukti_beli' => '',
                                'tipe' => $typeInput,
                                'user_input' => $this->session->userdata('username'),
                                'created_at' => date('Y-m-d H:i:s')
                            ];

                            $tambahData =  getReport(TYPE_REPORT_BARANG_MASUK, $data);
                            if ($tambahData) {
                                $this->session->set_flashdata('berhasil', 'Data Berhasil Di Tambahkan Dan Stok Berhasil Di Update');
                                redirect('barang/masuk');
                            } else {
                                $this->session->set_flashdata('gagal', 'Data Gagal Di Tambahkan Dan Stok Gagal Di Update');
                                redirect('barang/masuk/tambah');
                            }
                        } else {
                            $this->session->set_flashdata('gagal', 'jumlah barang masuk terlalu besar dari stok toko');
                            redirect('barang/masuk/tambah?toko_id=' . base64_encode($currentTokoId));
                        }
                    } else {
                        $this->session->set_flashdata('gagal', $resultValidation['message']);
                        redirect('barang/masuk/tambah?toko_id=' . base64_encode($currentTokoId));
                    }
                }

                if ($typeInput == 'gudang') {
                    $this->validateTypeGudang();
                }

                if ($typeInput == 'gudangsupplier') {
                    $resultValidation = $this->validateTypeGudangSupplier();
                    if ($resultValidation['err_code'] == 0) {
                        $supplier = htmlspecialchars($this->input->post('supplier'));
                        $gudangsupplierlist = htmlspecialchars($this->input->post('gudangsupplierlist'));
                        $harga_id_barang_gudang_supplier = htmlspecialchars($this->input->post('harga_id_barang_gudang_supplier'));
                        $tanggal_barang_masuk_gudangsupp = htmlspecialchars($this->input->post('tanggal_barang_masuk_gudangsupp'));
                        $jml_masuk_gudangsupp = htmlspecialchars($this->input->post('jml_masuk_gudangsupp'));
                        // $validationImage = $this->validate_bukti_file($_FILES['bukti_file_gudangsupp'], $typeInput);

                        if ($validationImage['err_code'] == 0) {
                            $data = [
                                'supplier' => $supplier,
                                'gudang' => $gudangsupplierlist,
                                'harga_barang' => $harga_id_barang_gudang_supplier,
                                'tanggal_barang' => $tanggal_barang_masuk_gudangsupp,
                                'jml_masuk' => $jml_masuk_gudangsupp,
                                // 'bukti_beli' => $validationImage['filename'],
                                'bukti_beli' => '',
                                'tipe' => $typeInput,
                                'user_input' => $this->session->userdata('username'),
                                'created_at' => date('Y-m-d H:i:s')
                            ];

                            $tambahData =  $this->doAddGudangSupplier($data);
                            // $tambahData =  getReport(TYPE_REPORT_BARANG_MASUK, $data);
                            if ($tambahData['err_code'] == 0) {
                                $this->session->set_flashdata('berhasil', 'Data Berhasil Di Tambahkan Dan Stok Berhasil Di Update');
                                redirect('barang/masuk/tambah?toko_id=' . base64_encode($currentTokoId));
                            } else {
                                $this->session->set_flashdata('gagal', 'Data Gagal Di Tambahkan Dan Stok Gagal Di Update');
                                redirect('barang/masuk/tambah?toko_id=' . base64_encode($currentTokoId));
                            }
                        } else {
                            $this->session->set_flashdata('gagal', $validationImage['message']);
                            redirect('barang/masuk/tambah?toko_id=' . base64_encode($currentTokoId));
                        }
                    } else {
                        $this->session->set_flashdata('gagal', $resultValidation['message']);
                        redirect('barang/masuk/tambah?toko_id=' . base64_encode($currentTokoId));
                    }
                }


                if ($typeInput == 'toko_luar') {
                    $resultValidation = $this->validateTypeTokoLuar();

                    if ($resultValidation['err_code'] == 0) {
                        $namaTokoBeli = htmlspecialchars($this->input->post('dari_toko_tokoluar'));
                        $hargaIdTokoLuar = htmlspecialchars($this->input->post('harga_id_toko_toko_luar'));

                        $jumlahStokMasuk = htmlspecialchars($this->input->post('jml_masuk_tokoluar'));
                        $stokToko = $this->Harga_model->getBarangHargaById($hargaIdTokoLuar);

                        // $validationImage = $this->validate_bukti_file($_FILES['bukti_file_tokoluar'], $typeInput);
                        
                        $data = [
                            'dari_toko_by_id_harga' => $hargaIdTokoLuar,
                            'stok_toko_dari_by_id_harga' => $stokToko['stok_toko'],
                            'nama_toko_beli' => $namaTokoBeli,
                            'nomor_supplier' => null,
                            'tanggal_barang_masuk' => htmlspecialchars($this->input->post('tanggal_barang_masuk_tokoluar')),
                            'harga_id' => $hargaIdTokoLuar,
                            'ke_toko' => htmlspecialchars($this->input->post('ke_toko_tokoluar_id')),
                            'jml_masuk' => $jumlahStokMasuk,
                            // 'bukti_beli' => $validationImage['filename'],
                            'bukti_beli' => '',
                            'tipe' => $typeInput,
                            'user_input' => $this->session->userdata('username'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        $tambahData =  getReport(TYPE_REPORT_BARANG_MASUK, $data);
                        if ($tambahData) {
                            $this->session->set_flashdata('berhasil', 'Data Berhasil Di Tambahkan Dan Stok Berhasil Di Update');
                            redirect('barang/masuk');
                        } else {
                            $this->session->set_flashdata('gagal', 'Data Gagal Di Tambahkan Dan Stok Gagal Di Update');
                            redirect('barang/masuk/tambah');
                        }
                    } else {
                        $this->session->set_flashdata('gagal', $resultValidation['message']);
                        redirect('barang/masuk/tambah?toko_id=' . base64_encode($currentTokoId));
                    }
                }
            } else {
                $this->view['title_menu']   = "Barang";
                $this->view['title']        = "Barang Masuk";
                $this->view['content']      = "barang_masuk/v_tambah";
                $this->view['toko']        = $this->Barang_masuk_model->getAllTokoByHarga();
                $this->view['gagal']        = $this->upload->display_errors();
                $this->view['supplier']   = $this->Supplier_model->getAllSupplier();
                $this->view['ketoko']        = $this->Toko_model->ambilTokoKecualiIdToko($currentTokoId);
                $this->view['toko_name'] = $this->Toko_model->findByIdToko($currentTokoId);
                $this->view['gudangtoko'] = $this->Toko_model->getGudangToko();
                $this->view['toko_semua'] = $this->Toko_model->ambilSemuaToko();
                $this->view['barangtoko_current'] = $this->Harga_model->ambilBarangBerdasarkanToko($currentTokoId);

                $this->load->view('layout/header', $this->view);
                $this->load->view('layout/sidebar', $this->view);
                $this->load->view('layout/navbar', $this->view);
                $this->load->view('barang_masuk/v_tambah', $this->view);
                $this->load->view('layout/footer', $this->view);
                $this->load->view('layout/script', $this->view);
            }
        }
    }



    private function validateTypeAntarToko()
    {
        $err_code = 0;
        $message = '';

        $this->form_validation->set_rules('dari_toko', 'Dari Toko', 'required|trim');
        $this->form_validation->set_rules('harga_id_toko', 'Barang Toko', 'required|trim');
        $this->form_validation->set_rules('ke_toko', 'Ke Toko', 'required|trim');
        // $this->form_validation->set_rules('bukti_file', 'Bukti Masuk', 'callback_validate_bukti_file_callbacks');


        if ($this->form_validation->run() == FALSE) {
            $err_code++;
            $message = validation_errors();
        } else {
            $err_code = 0;
            $message = 'Validasi Berhasil';
        }

        $dataResult = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        return $dataResult;
    }

    private function validateTypeGudang()
    {
    }

    private function validateTypeGudangSupplier()
    {
        $err_code = 0;
        $message = '';

        $this->form_validation->set_rules('supplier', 'Supplier', 'required|trim');
        $this->form_validation->set_rules('gudangsupplierlist', 'Gudang', 'required|trim');
        $this->form_validation->set_rules('harga_id_barang_gudang_supplier', 'Barang Gudang', 'required|trim');
        $this->form_validation->set_rules('tanggal_barang_masuk_gudangsupp', 'Tanggal Barang', 'required|trim');
        $this->form_validation->set_rules('jml_masuk_gudangsupp', 'Jumlah Barang', 'required|trim');


        if ($this->form_validation->run() == FALSE) {
            $err_code++;
            $message = validation_errors();
        } else {
            $err_code = 0;
            $message = 'Validasi Berhasil';
        }

        $dataResult = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        return $dataResult;
    }

    private function validateTypeTokoLuar()
    {
        $err_code = 0;
        $message = '';

        $this->form_validation->set_rules('dari_toko_tokoluar', 'Dari Toko', 'required|trim');
        $this->form_validation->set_rules('ke_toko_tokoluar', 'Ke Toko', 'required|trim');
        $this->form_validation->set_rules('harga_id_toko_toko_luar', 'Barang Toko', 'required|trim');
        $this->form_validation->set_rules('tanggal_barang_masuk_tokoluar', 'Tanggal Barang', 'required|trim');
        $this->form_validation->set_rules('jml_masuk_tokoluar', 'Jumlah Barang', 'required|trim');


        if ($this->form_validation->run() == FALSE) {
            $err_code++;
            $message = validation_errors();
        } else {
            $err_code = 0;
            $message = 'Validasi Berhasil';
        }

        $dataResult = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        return $dataResult;
    }

    public function validate_bukti_file_callbacks($file)
    {
        if (empty($file['name'])) {
            $this->form_validation->set_message('validate_bukti_file', 'Bukti File Harus diisi');
            return false;
        } else {

            $config['file_name']            = rand();
            $config['upload_path']          = '/assets/file_barang_masuk';
            $config['allowed_types']        = 'pdf|gif|jpg|jpeg|png|PNG|JPG|JPEG|GIF';
            $config['max_size']             = 2048;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('bukti_file')) {
                return true;
            } else {
                $this->form_validation->set_message('validate_bukti_file', $this->upload->display_errors());
                return false;
            }
        }
    }

    private function validate_bukti_file($file, $tipe)
    {
        $err_code = 0;
        $message = '';
        $data = '';
        $fileName = '';
        
        // Bisa di aktifkan
        // if (empty($file['name'])) {
        //     $err_code++;
        //     $message = "Bukti harus diisi";
        // } else {
        //     $this->load->library('encryption');
        //     $key = bin2hex(random_bytes(32));
        //     $encrypted_name = $this->encryption->encrypt($file['name']);


        //     // $config['file_name']            = $file['name'];
        //     $config['upload_path']          = './assets/file_barang_masuk';
        //     $config['allowed_types']        = 'pdf|gif|jpg|jpeg|png|JPEG|JPG|PNG';
        //     $config['max_size']             = 2048;
        //     $config['remove_spaces'] = TRUE;
        //     $config['encrypt_name'] = TRUE;

        //     $this->load->library('upload');
        //     $this->upload->initialize($config);

        //     if ($tipe == 'antar_toko') {
        //         if (!$this->upload->do_upload('bukti_file')) {
        //             $err_code++;
        //             $message = "Gagal upload file: " . $this->upload->display_errors();
        //         } else {

        //             $upload_data = $this->upload->data();
        //             $fileName = $upload_data['file_name'];
        //             $file_path = $config['upload_path'] . $upload_data['file_name'];
        //         }
        //     }

        //     if ($tipe == 'gudangsupplier') {
        //         if (!$this->upload->do_upload('bukti_file_gudangsupp')) {
        //             $err_code++;
        //             $message = "Gagal upload file: " . $this->upload->display_errors();
        //         } else {

        //             $upload_data = $this->upload->data();
        //             $fileName = $upload_data['file_name'];
        //             $file_path = $config['upload_path'] . $upload_data['file_name'];
        //         }
        //     }

        //     if ($tipe == 'toko_luar') {
        //         if (!$this->upload->do_upload('bukti_file_tokoluar')) {
        //             $err_code++;
        //             $message = "Gagal upload file: " . $this->upload->display_errors();
        //         } else {

        //             $upload_data = $this->upload->data();
        //             $fileName = $upload_data['file_name'];
        //             $file_path = $config['upload_path'] . $upload_data['file_name'];
        //         }
        //     }
        // }
        
        // Optional jika tidak di gunakan bisa di commant
        if($fileName){
            $file = $fileName;
        }else{
            $file = '';
        }
        $dataRes = [
            'err_code' => $err_code,
            'message' => $message,
            'data' => $data,
            'filename' => $file,
        ];

        return $dataRes;
    }


    public function doAddBarangMasuk()
    {
    }

    public function doAddGudangSupplier($data)
    {
        $err_code = 0;
        $message = "";
        $nama_sales = $data['supplier'];
        $gudang = $data['gudang']; //id toko
        $harga_id = $data['harga_barang'];
        $tanggal_barang_masuk = $data['tanggal_barang'];
        $jml_masuk = $data['jml_masuk'];
        $bukti_beli = $data['bukti_beli'];
        $tipe = $data['tipe'];
        $user_input = $data['user_input'];
        $created_at = $data['created_at'];

        $harga = $this->Harga_model->getBarangHargaById($data['harga_barang']);
        $stockExisting = $harga['stok_toko'];
        $stockInput = $data['jml_masuk'];

        $sumStock = intval($stockExisting) + intval($stockInput);

        $datasave = [
            'nama_sales' => $nama_sales,
            'gudang' => $gudang,
            'harga_id' => $harga_id,
            'tanggal_barang_masuk' => $tanggal_barang_masuk,
            'jml_masuk' => $jml_masuk,
            'bukti_beli' => $bukti_beli,
            'tipe' => $tipe,
            'user_input' => $user_input,
            'created_at' => $created_at,
            'stock_existing' => $stockExisting,
            'stock_input' => $stockInput,
            'stock_sum' => $sumStock,
        ];

        $doAdd = $this->Barang_masuk_model->tambahDataGudangSupplier($datasave);
        if ($doAdd == true) {
            $err_code = 0;
            $message = 'Berhasil diupdate';
        }
        if ($doAdd == false) {
            $err_code++;
            $message = 'Gagal diupdate';
        }

        $returnData = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        return $returnData;
    }

    public function getDataBarangHargaAjax()
    {
        $currentTokoId = $this->input->post('toko_id');
        $result = $this->Harga_model->ambilBarangBerdasarkanToko($currentTokoId);
        echo json_encode($result);
    }


    public function hapus($id)
    {
        $barang_masuk = $this->Barang_masuk_model->getBarangMasukFindId(base64_decode($id));
        $bukti_file = $barang_masuk['bukti_beli'];
        if ($bukti_file) {
            unlink(FCPATH . 'assets/file_barang_masuk/' . $bukti_file);
        }

        $this->Barang_masuk_model->hapus_data(base64_decode($id));
        $this->session->set_flashdata('berhasil', 'Data Berhasil Di Hapus');
        redirect('barang/masuk');
    }

    public function json_barang_by_toko($toko_id)
    {
        $barang = $this->Harga_model->findBarangByToko($toko_id);

        echo json_encode($barang);
    }
    public function json_supplier()
    {
        $barang = $this->Supplier_model->getAllSupplier();

        echo json_encode($barang);
    }
}
