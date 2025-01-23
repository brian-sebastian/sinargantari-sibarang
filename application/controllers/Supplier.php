<?php

/**
 * @property Supplier_model $Supplier_model
 * @property form_validation $form_validation
 * @property input $input
 * @property upload $upload
 * @property session $session
 * @property secure $secure
 */
class Supplier extends CI_Controller
{
    private $view;
    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model('Supplier_model');
    }

    public function index()
    {
        $this->view['title_menu'] = "Toko";
        $this->view['title']      = "Supplier";
        $this->view['content']    = "supplier/v_supplier_index";


        $this->load->view('layout/wrapper', $this->view);
    }

    public function ajax_supplier()
    {
        $data = $this->Supplier_model->getAllSupplier();

        $newArr = [];

        $no = 1;
        foreach ($data as $d) {
            $row = [];
            $row[] = $no;
            $row[] = $d['nama_supplier'];
            $row[] = $d['no_telpon_supplier'];
            $row[] = $d['alamat_supplier'];
            $row[] = "
                        <a class='btn btn-warning btn-sm' href='" . base_url('toko/supplier/tampilan_edit/') . base64_encode($d['id_supplier'])  . "'><i class='bx bx-trash me-1'></i> Edit</a>
                        <a class='btn btn-danger btn-sm' href='" . base_url('toko/supplier/hapus/') . base64_encode($d['id_supplier'])  . "' onclick='return confirm('Apakah ingin menghapus" . $d['nama_supplier'] . "')' ><i class='bx bx-trash me-1'></i> Delete</a>
                     ";

            array_push($newArr, $row);
            $no++;
        }


        $hitungTotalSupplier = $this->Supplier_model->hitungTotalSupplier();
        $filterTotalSupplier = $this->Supplier_model->hitungFilterSupplier();

        $data_json = [
            'draw' => $this->input->post('draw'),
            'data' => $newArr,
            'recordsTotal' => $hitungTotalSupplier,
            'recordsFiltered' => $filterTotalSupplier
        ];

        echo json_encode($data_json);
    }

    public function tambah()
    {
        $field = [
            [
                'field' => 'nama_supplier',
                'label' => 'Nama Supplier',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'no_telpon_supplier',
                'label' => 'No Telpon Supplier',
                'rules' => 'required|numeric|max_length[13]|min_length[11]'
            ],
            [
                'field' => 'alamat_supplier',
                'label' => 'Alamat Supplier',
                'rules' => 'required'
            ]
        ];


        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'nama_supplier' => htmlspecialchars($this->input->post('nama_supplier')),
                'no_telpon_supplier' => htmlspecialchars($this->input->post('no_telpon_supplier')),
                'alamat_supplier' => htmlspecialchars($this->input->post('alamat_supplier')),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('username'),
            ];

            if ($this->Supplier_model->tambah_data($data)) {
                $this->session->set_flashdata('berhasil', 'Data Berhasil Di Tambahkan');
                redirect('toko/supplier');
            } else {
                $this->session->set_flashdata('gagal', 'Data Gagal Di Tambahkan');
                redirect('toko/supplier/tambah');
            }
        } else {

            $this->view['title_menu'] = "Toko";
            $this->view['title']      = "Supplier";
            $this->view['content']    = "supplier/v_tambah";


            $this->load->view('layout/wrapper', $this->view);
        }
    }

    public function tampilan_edit($id)
    {
        $this->view['title_menu'] = "Toko";
        $this->view['title']      = "Supplier";
        $this->view['content']    = "supplier/v_edit";
        $this->view['supplier']   = $this->Supplier_model->findById(base64_decode($id));


        $this->load->view('layout/wrapper', $this->view);
    }

    public function edit()
    {
        $field = [
            [
                'field' => 'nama_supplier',
                'label' => 'Nama Supplier',
                'rules' => 'required|trim',
            ],
            [
                'field' => 'no_telpon_supplier',
                'label' => 'No Telpon Supplier',
                'rules' => 'required|numeric|max_length[13]|min_length[11]'
            ],
            [
                'field' => 'alamat_supplier',
                'label' => 'Alamat Supplier',
                'rules' => 'required'
            ]
        ];


        $this->form_validation->set_rules($field);

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'id_supplier' => htmlspecialchars($this->input->post('id_supplier')),
                'nama_supplier' => htmlspecialchars($this->input->post('nama_supplier')),
                'no_telpon_supplier' => htmlspecialchars($this->input->post('no_telpon_supplier')),
                'alamat_supplier' => htmlspecialchars($this->input->post('alamat_supplier')),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->session->userdata('username'),
            ];

            if ($this->Supplier_model->edit_data($data)) {
                $this->session->set_flashdata('berhasil', 'Data Berhasil Di Ubah');
                redirect('toko/supplier');
            } else {
                $this->session->set_flashdata('gagal', 'Data Gagal Di Ubah');
                redirect('toko/supplier/tampilan_edit/' . $data['id_supplier']);
            }
        } else {
            $this->view['title_menu'] = "Toko";
            $this->view['title']      = "Supplier";
            $this->view['content']    = "supplier/v_edit";
            $this->view['supplier']   = $this->Supplier_model->findById(base64_decode($this->input->post('id_supplier')));


            $this->load->view('layout/wrapper', $this->view);
        }
    }

    public function hapus($id)
    {
        $this->Supplier_model->hapus_data(base64_decode($id));
        $this->session->set_flashdata('berhasil', 'Data Berhasil Di Hapus');
        redirect('toko/supplier');
    }
}
