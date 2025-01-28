<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls as ReaderXls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Protection;

/**
 * @property session $session
 * @property valdasi $validasi
 * @property barang $barang
 */
class Excel_library
{

    protected $CI;
    private $spreadsheet;
    private $export;
    private $import;

    public function __construct($data)
    {
        $this->CI = &get_instance();

        $this->spreadsheet = new Spreadsheet();

        // call model
        $this->CI->load->model('Validasi_model', 'validasi');
        $this->CI->load->model('Barang_model', 'barang');
        $this->CI->load->library('barang_library');
        $this->CI->load->library("indonesianumber");


        $this->case($data);
    }

    public function case($data)
    {
        if (is_array($data) && !empty($data)) {

            switch ($data['func']) {
                case 'export':

                    $this->export($data);
                    break;
                case 'import':
                    $this->import($data);
                default:
                    return;
                    break;
            }
        } else {
            return;
        }
    }

    private function import($data)
    {
        $nameFile = $data['data']['file_barang']['name'];
        $tempFile = $data['data']['file_barang']['tmp_name'];
        $typeFile = $data['data']['file_barang']['type'];
        $arr_file = explode('.', $nameFile);
        $extension = end($arr_file);

        $pathinfo = pathinfo($nameFile, PATHINFO_EXTENSION);
        $tmp_name = $tempFile;

        if ('csv' == $extension) {

            $this->import = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ('xls' == $extension) {

            $this->import = new ReaderXls;
        } elseif ('xlsx' == $extension) {

            $this->import = new ReaderXlsx;
        }

        $spreadsheetReader = $this->import->load($tmp_name);


        $result = $spreadsheetReader->getActiveSheet()->toArray();

        switch ($data['jenis']) {
            case "barang":

                if (count($result[0]) < 8 || count($result[0]) > 8) {
                    $this->CI->session->set_flashdata('message_error', 'Kolom tidak sesuai');
                    redirect('barang/list', 'refresh');
                }

                $arr_numeric = [];

                $arr_nama_barang = [];
                $arr_barcode_barang = [];

                $hasDuplicateNamaBarang = false;
                $hasDuplicateBarcodeBarang = false;

                for ($i = 1; $i <= (count($result) - 1); $i++) {

                    if ($result[$i][1]) {

                        array_push($arr_nama_barang, trim($result[$i][1]));
                    }

                    if ($result[$i][7]) {

                        array_push($arr_barcode_barang, trim($result[$i][7]));
                    }
                }

                $arr_nama_barang_counts = array_count_values($arr_nama_barang);

                foreach ($arr_nama_barang_counts as $count) {
                    if ($count > 1) {
                        $hasDuplicateNamaBarang = true;
                        break;
                    }
                }

                $arr_barcode_barang_count = array_count_values($arr_barcode_barang);

                foreach ($arr_barcode_barang_count as $count) {
                    if ($count > 1) {
                        $hasDuplicateBarcodeBarang = true;
                        break;
                    }
                }

                if (!$hasDuplicateBarcodeBarang && !$hasDuplicateNamaBarang) {

                    for ($i = 1; $i <= (count($result) - 1); $i++) {

                        $arr_assoc['nama_barang']            = trim($result[$i][1]);
                        $arr_assoc['kategori_id']            = trim($result[$i][2]);
                        $arr_assoc['harga_pokok']            = trim($result[$i][3]);
                        $arr_assoc['berat_barang']           = trim($result[$i][4]);
                        $arr_assoc['satuan_id']              = trim($result[$i][5]);
                        $arr_assoc['deskripsi']              = trim($result[$i][6]);
                        $arr_assoc['barcode_barang']         = trim($result[$i][7]);
                        // $arr_assoc['barcode_barang']         = trim(($result[$i][7]) ? $this->CI->barang_library->createBarcodeBarang($result[$i][7]) : $this->CI->barang_library->createBarcodeBarang());

                        $arr_assoc['kode_barang']       = $this->CI->barang_library->createKodeBarangLoop($i);
                        $arr_assoc['slug_barang']       = $this->CI->barang_library->slugify($result[$i][1]);
                        $arr_assoc['is_active']         = 1;

                        if ($arr_assoc['nama_barang']) {

                            array_push($arr_numeric, $arr_assoc);
                        }
                    }

                    if (!empty($arr_numeric)) {

                        $this->CI->validasi->do_truncate('tbl_barang_temp');

                        $data_error = [];
                        $data_valid = [];

                        foreach ($arr_numeric as $a) {

                            $err = 0;

                            /**
                             * !TODO : VALIDASI NAMA BARANG
                             */
                            if (!empty($a['nama_barang'])) {

                                if ($this->CI->validasi->check_duplicate('tbl_barang', 'nama_barang', $a['nama_barang'])) {
                                    $a['nama_barang'] = $a['nama_barang'] . ' <span class="text-danger">Telah terdaftar</span>';
                                    $err++;
                                }

                                // if ($this->CI->validasi->check_regex_name($a['nama_barang']) == false) {
                                //     $a['nama_barang'] = $a['nama_barang'] . ' <span class="text-danger">Terdapat Karakter Yang Tidak Diizinkan</span>';
                                //     $err++;
                                // }
                            } else {
                                $a['nama_barang'] = $a['nama_barang'] . ' <span class="text-danger">Tidak Boleh Kosong</span>';
                                $err++;
                            }

                            /**
                             * !TODO : VALIDASI KATEGORI BARANG
                             */
                            if (!empty($a['kategori_id'])) {
                                $kategori = $this->CI->validasi->check_kategori_exist($a['kategori_id']);
                                if ($kategori) {
                                    $a['kategori_id'] = $kategori;
                                } else {
                                    $a['kategori_id'] = $a['kategori_id'] . " <span class='text-danger'>Tidak tersedia, silahkan daftarkan pada menu kategori</span>";
                                    $err++;
                                }
                            } else {
                                $a['kategori_id'] = '<span class="text-danger">Kategori Tidak Boleh Kosong</span>';
                                $err++;
                            }

                            /**
                             * !TODO : VALIDASI SATUAN BARANG
                             */
                            if (!empty($a['satuan_id'])) {
                                $satuan_barang = $this->CI->validasi->check_satuan_exist($a['satuan_id']);

                                if ($satuan_barang) {
                                    $a['satuan_id'] = $satuan_barang;
                                } else {
                                    $a['satuan_id'] = $a['satuan_id'] . " <span class='text-danger'>Tidak tersedia, silahkan daftarkan pada menu satuan barang</span>";
                                    $err++;
                                }
                            } else {
                                $a['satuan_id'] = '<span class="text-danger">Satuan Barang Tidak Boleh Kosong</span>';
                                $err++;
                            }

                            // Harga Pokok Tidak Boleh Minus (-)
                            if (!empty($a['harga_pokok'])) {
                                $adaMinus = strpos($a['harga_pokok'], '-');
                                if ($adaMinus) {

                                    $a['harga_pokok'] = $a['harga_pokok'] . " <span class='text-danger'>Harga Pokok Tidak Boleh Minus</span>";
                                    $err++;
                                } else {

                                    if (strpos($a['harga_pokok'], ',')) {
                                        $a['harga_pokok'] = str_replace(',', '', $a['harga_pokok']);
                                    } else if (strpos($a['harga_pokok'], '.')) {
                                        $a['harga_pokok'] = str_replace('.', '', $a['harga_pokok']);
                                    }
                                }
                            }

                            if (!empty($a["barcode_barang"])) {

                                $barcode_barang = $this->CI->validasi->check_barcode_duplicate($a["barcode_barang"]);

                                if ($barcode_barang) {

                                    $a["barcode_barang"] = $a['barcode_barang'] . " <span class='text-danger'>Sudah terpakai</span>";
                                    $err++;
                                }
                            }


                            if ($err) {

                                $a['harga_pokok'] = trim($a['harga_pokok']);
                                $a['nama_barang'] = trim($a['nama_barang']);
                                array_push($data_error, $a);
                            } else {

                                if ($a["barcode_barang"]) {

                                    $this->CI->barang_library->createBarcodeBarang($a["barcode_barang"]);
                                } else {

                                    $a["barcode_barang"] = $this->CI->barang_library->createBarcodeBarang();
                                }

                                $a['harga_pokok'] = trim($a['harga_pokok']);
                                $a['nama_barang'] = trim($a['nama_barang']);
                                array_push($data_valid, array_merge($a, ['user_input'   => ($this->CI->session->userdata('nama_user')) ? $this->CI->session->userdata('nama_user') : 'Unknown', 'created_at'   => date('Y-m-d H:i:s')]));
                            }
                        }

                        if ($data_error) {
                            $this->CI->validasi->insert_data($data_error, 'error');
                        }

                        if ($data_valid) {
                            $this->CI->validasi->insert_data($data_valid, 'valid');
                        }

                        $this->CI->session->set_flashdata('message', count($data_valid) . ' berhasil & ' . count($data_error) . ' Gagal');
                        redirect('barang/index', 'refresh');
                    } else {

                        $this->CI->session->set_flashdata('message_error', 'Data anda kosong!');
                        redirect('barang/index', 'refresh');
                    }
                } else {

                    $this->CI->session->set_flashdata('message_error', 'Terdapat nama atau barcode yang sama');
                    redirect('barang/index', 'refresh');
                }

                break;

            case "barang_toko":

                $enkripsi_toko = $this->CI->secure->encrypt_url($data["toko_id"]);

                if (count($result[0]) < 4) {

                    $this->CI->session->set_flashdata('message_error', 'Kolom tidak sesuai');
                    ($this->CI->session->userdata("toko_id")) ? redirect("barang/barang_toko") : redirect("barang/barang_toko?toko='$enkripsi_toko'");
                }

                $arr_numeric = [];

                for ($i = 1; $i <= (count($result) - 1); $i++) {

                    $arr_assoc['barang_id']     = trim($result[$i][1]);
                    $arr_assoc['harga_jual']    = trim($result[$i][2]);
                    $arr_assoc['stok_toko']     = trim($result[$i][3]);
                    $arr_assoc['is_active']     = 1;
                    $arr_assoc['user_input']    = $this->CI->session->userdata('username');
                    $arr_assoc['created_at']    = date('Y-m-d H:i:s');

                    array_push($arr_numeric, $arr_assoc);
                }

                if (!empty($arr_numeric)) {

                    $array_temp = [];
                    $array_valid = [];


                    $ambilSemuaBarang = $this->CI->barang->ambilSemuaBarang();


                    foreach ($arr_numeric as $barang_toko) {

                        $err = 0;

                        $barang_toko["toko_id"] = $data["toko_id"];

                        $hargaJualKurang = 0;

                        foreach ($ambilSemuaBarang as $brg) {

                            if ($brg['nama_barang'] == $barang_toko['barang_id']) {
                                if ($barang_toko['harga_jual'] <  $brg['harga_pokok'])
                                    $hargaJualKurang++;
                            }
                        }



                        if (!empty($barang_toko['harga_jual'])) {

                            // $adaMinusHargaJual = strpos($barang_toko['harga_jual'], '-');
                            $adaMinusHargaJual = $barang_toko['harga_jual'];
                            if ($adaMinusHargaJual == 0 && $hargaJualKurang) {
                                $barang_toko['harga_jual'] = $barang_toko['harga_jual'] . ' <span class="text-danger">Harga Jual Tidak Boleh Minus Dan Harga Jual Kurang Dari Harga Pokok</span>';
                                $err++;
                            } else if ($adaMinusHargaJual == 0) {

                                $barang_toko['harga_jual'] = $barang_toko['harga_jual'] . ' <span class="text-danger">Harga Jual Tidak Boleh Minus</span>';
                                $err++;
                            } else if ($hargaJualKurang) {
                                $barang_toko['harga_jual'] = $barang_toko['harga_jual'] . ' <span class="text-danger">Harga Jual Tidak Boleh Kurang Dari Harga Pokok</span>';
                                $err++;
                            }
                        }

                        if (!empty($barang_toko['barang_id'])) {

                            $result_check_barang = $this->CI->validasi->check_barang('tbl_barang', 'nama_barang', $barang_toko['barang_id']);

                            if ($result_check_barang) {

                                $barang_toko['barang_id'] = $result_check_barang;
                            } else {

                                $barang_toko['barang_id'] = $barang_toko['barang_id'] . ' <span class="text-danger">belum tersedia</span>';
                                $err++;
                            }
                        } else {
                            $barang_toko['barang_id'] = '<span class="text-danger">Barang harus di isi</span>';
                            $err++;
                        }

                        $adaMinusStok = $barang_toko['stok_toko'];
                        if ($adaMinusStok == 0) {
                            $barang_toko['stok_toko'] = $barang_toko['stok_toko'] . ' <span class="text-danger">Stok Toko Tidak Boleh Minus</span>';
                            $err++;
                        }


                        // if (!empty($barang_toko['toko_id'])) {

                        //     $result_check_toko = $this->CI->validasi->check_toko('tbl_toko', 'nama_toko', $barang_toko['toko_id']);

                        //     if ($result_check_toko) {

                        //         $barang_toko['toko_id'] = $result_check_toko;
                        //     } else {

                        //         $barang_toko['toko_id'] = $barang_toko['toko_id'] . ' <span class="text-danger">belum tersedia</span>';
                        //         $err++;
                        //     }
                        // } else {

                        //     $barang_toko['toko_id'] = '<span class="text-danger">Toko harus di isi</span>';
                        //     $err++;
                        // }

                        if (empty($barang_toko['harga_jual'])) {

                            $barang_toko['harga_jual'] = '<span class="text-danger">Harga harus di isi</span>';
                            $err++;
                        }

                        if (empty($barang_toko['stok_toko'])) {

                            $barang_toko['stok_toko'] = '<span class="text-danger">Stok harus di isi</span>';
                            $err++;
                        }

                        if ($err) {

                            array_push($array_temp, $barang_toko);
                        } else {

                            $barang_toko['harga_jual'] = trim($barang_toko['harga_jual']);

                            if (strpos($barang_toko['harga_jual'], ',')) {
                                $barang_toko['harga_jual'] = str_replace(',', '', $barang_toko['harga_jual']);
                            } else if (strpos($barang_toko['harga_jual'], '.')) {
                                $barang_toko['harga_jual'] = str_replace('.', '', $barang_toko['harga_jual']);
                            }

                            array_push($array_valid, $barang_toko);
                        }
                    }

                    $this->CI->validasi->do_delete('tbl_harga_temp', array("toko_id"   => $data["toko_id"]));

                    if ($array_temp) {

                        $this->CI->validasi->insert_data($array_temp, 'error_barang_toko');
                    }

                    if ($array_valid) {

                        $this->CI->validasi->insert_data($array_valid, 'valid_barang_toko');
                    }

                    $this->CI->session->set_flashdata('message', count($array_valid) . ' Berhasil Ditambahkan & ' . count($array_temp) . ' Gagal Ditambahkan');
                    ($this->CI->session->userdata("toko_id")) ? redirect("barang/barang_toko") : redirect("barang/barang_toko?toko='$enkripsi_toko'");
                } else {

                    $this->CI->session->set_flashdata('message_error', 'Data anda kosong!');
                    ($this->CI->session->userdata("toko_id")) ? redirect("barang/barang_toko") : redirect("barang/barang_toko?toko='$enkripsi_toko'");
                }

                break;
            case "karyawan_toko":

                if ($this->CI->session->userdata("toko_id")) {

                    if (count($result[0]) != 7) {
                        $this->CI->session->set_flashdata('gagal', 'Kolom tidak sesuai');
                        redirect('toko/karyawan', 'refresh');
                    }
                } else {

                    if (count($result[0]) != 8) {
                        $this->CI->session->set_flashdata('gagal', 'Kolom tidak sesuai');
                        redirect('toko/karyawan', 'refresh');
                    }
                }

                $arr_numeric = [];

                if (!$this->CI->session->userdata("toko_id")) {

                    for ($i = 1; $i <= (count($result) - 1); $i++) {

                        $arr_assoc['username']               = $result[$i][1];
                        $arr_assoc['role']                   = $result[$i][2];
                        $arr_assoc['nama_toko']              = $result[$i][3];
                        $arr_assoc['nama_karyawan']          = $result[$i][4];
                        $arr_assoc['hp_karyawan']            = $result[$i][5];
                        $arr_assoc['alamat_karyawan']        = $result[$i][6];
                        $arr_assoc['bagian']                 = $result[$i][7];

                        if ($arr_assoc['username']) {

                            array_push($arr_numeric, $arr_assoc);
                        }
                    }
                } else {

                    for ($i = 1; $i <= (count($result) - 1); $i++) {

                        $arr_assoc['username']               = $result[$i][1];
                        $arr_assoc['role']                   = $result[$i][2];
                        $arr_assoc['nama_karyawan']          = $result[$i][3];
                        $arr_assoc['hp_karyawan']            = $result[$i][4];
                        $arr_assoc['alamat_karyawan']        = $result[$i][5];
                        $arr_assoc['bagian']                 = $result[$i][6];

                        if ($arr_assoc['username']) {

                            array_push($arr_numeric, $arr_assoc);
                        }
                    }
                }

                if (!empty($arr_numeric)) {

                    $this->CI->validasi->do_delete('tbl_karyawan_toko_temp', array("is_sess_toko"   => ($this->CI->session->userdata("toko_id")) ? $this->CI->session->userdata("toko_id") : NULL));

                    $data_error = [];
                    $data_valid = [];

                    foreach ($arr_numeric as $a) {

                        $err = 0;
                        /**
                         * !TODO : VALIDASI USERNAME KARYAWAN
                         */
                        if (!empty($a['username'])) {

                            if ($this->CI->validasi->check_duplicate('tbl_user', 'username', $a['username'])) {
                                $a['username'] = $a['username'] . ' <span class="text-danger">telah terdaftar</span>';
                                $err++;
                            }
                        } else {
                            $a['username'] = $a['username'] . ' <span class="text-danger">tidak Boleh Kosong</span>';
                            $err++;
                        }

                        /**
                         * !TODO : VALIDASI ROLE KARYAWAN
                         */
                        if (!empty($a['role'])) {
                            $role = $this->CI->validasi->check_exist("tbl_role", "role", $a['role']);
                            if ($role) {

                                if ($role['role'] == 'Developer') {
                                    $a['role_id'] = $a['role'] . " <span class='text-danger'>kamu tidak memiliki akses mengimport data ini</span>";
                                    unset($a['role']);
                                    $err++;
                                } else {
                                    unset($a['role']);
                                    $a['role_id'] = $role["id_role"];
                                }
                            } else {

                                $a['role_id'] = $a['role'] . " <span class='text-danger'>tidak tersedia, silahkan daftarkan pada menu role</span>";
                                unset($a['role']);
                                $err++;
                            }
                        } else {

                            $a['role_id'] = 'Role Tidak Boleh Kosong';
                            unset($a['role']);
                            $err++;
                        }

                        /**
                         * !TODO : VALIDASI TOKO KARYAWAN
                         */
                        if (!$this->CI->session->userdata("toko_id")) {

                            if (!empty($a['nama_toko'])) {
                                $toko = $this->CI->validasi->check_exist("tbl_toko", "nama_toko", $a['nama_toko']);
                                if ($toko) {

                                    unset($a['nama_toko']);
                                    $a['toko_id'] = $toko["id_toko"];
                                } else {

                                    $a['toko_id'] = $a['nama_toko'] . " <span class='text-danger'>tidak tersedia, silahkan daftarkan pada menu toko</span>";
                                    unset($a['nama_toko']);
                                    $err++;
                                }
                            } else {

                                $a['toko_id'] = '<span class="text-danger">Nama toko tidak boleh kosong</span>';
                                unset($a['nama_toko']);
                                $err++;
                            }
                        } else {

                            unset($a['nama_toko']);
                            $a['toko_id']   = $this->CI->session->userdata("toko_id");
                        }

                        /**
                         * !TODO : VALIDASI NAMA KARYAWAN
                         */
                        if (empty($a['nama_karyawan'])) {

                            $a['nama_karyawan'] = '<span class="text-danger">nama karyawan Tidak Boleh Kosong</span>';
                            $err++;
                        }

                        /**
                         * !TODO : VALIDASI HP KARYAWAN
                         */
                        if (!empty($a['hp_karyawan'])) {

                            if (is_numeric($a['hp_karyawan'])) {

                                if ((strlen(trim($a["hp_karyawan"])) > 9) && (strlen(trim($a["hp_karyawan"])) < 14)) {

                                    $a["hp_karyawan"]   = $this->CI->indonesianumber->changeFormatIndonesiaNumberPhone($a["hp_karyawan"]);
                                } else {

                                    $a["hp_karyawan"] = $a['hp_karyawan'] . " <span class='text-danger'>minimal 10 digit dan maksimal 13 digit</span>";
                                    $err++;
                                }
                            } else {

                                $a["hp_karyawan"] = $a['hp_karyawan'] . " <span class='text-danger'>Harus numeric</span>";
                                $err++;
                            }
                        }

                        /**
                         * !TODO : VALIDASI BAGIAN KARYAWAN
                         */
                        if (!empty($a['bagian'])) {

                            $arr_bagian = ["tetap", "kontrak", "harian"];

                            if (in_array(strtolower($a["bagian"]), $arr_bagian)) {

                                $a["bagian"] = strtolower($a["bagian"]);
                            } else {

                                $a["bagian"] = $a['bagian'] . " <span class='text-danger'>tersebut tidak terdaftar";
                                $err++;
                            }
                        }

                        if ($err) {

                            $a["is_sess_toko"]  = ($this->CI->session->userdata("toko_id")) ? $this->CI->session->userdata("toko_id") : NULL;
                            array_push($data_error, $a);
                        } else {

                            array_push($data_valid, $a);
                        }
                    }

                    if ($data_error) {
                        $this->CI->validasi->insert_data($data_error, 'error_karyawan_toko');
                    }

                    if ($data_valid) {
                        $this->CI->validasi->insert_data($data_valid, 'valid_karyawan_toko');
                    }

                    $this->CI->session->set_flashdata('berhasil', count($data_valid) . ' berhasil dengan' . ' Password Default : 12345678 ' . ' & ' . count($data_error) . ' Gagal ');
                    redirect('toko/karyawan', 'refresh');
                } else {

                    $this->CI->session->set_flashdata('gagal', 'Data anda kosong!');
                    redirect('toko/karyawan', 'refresh');
                }

            case "barang_gudang_baru":

                if (count($result[0]) < 4 || count($result[0]) > 4) {

                    $this->CI->session->set_flashdata('gagal', 'Kolom tidak sesuai');
                    redirect('gudang/import_gudang/data_baru', 'refresh');
                }

                $new_arrs = [];

                foreach ($result as $key => $r) {

                    if ($key) {

                        $row = [];

                        $row["nama_barang"] = $r[0];
                        $row["nama_gudang"] = $r[1];
                        $row["jumlah_barang"] = $r[2];
                        $row["nama_toko_luar"] = $r[3];

                        array_push($new_arrs, $row);
                    }
                }

                $this->CI->db->truncate('tbl_import_gudang_baru');

                foreach ($new_arrs as $key => $new_arr) {

                    $status = "";

                    if ($new_arr["nama_barang"]) {

                        $this->CI->db->where("LOWER(nama_barang)", strtolower($new_arr["nama_barang"]));
                        $query = $this->CI->db->get("tbl_barang");

                        if (!$query->num_rows()) {

                            $status .= "<span class='text-danger'>Nama Barang Tidak Terdaftar</span><br>";
                        }
                    } else {

                        $status .= "<span class='text-danger'>Nama Barang Harus Di Isi</span><br>";
                    }

                    if ($new_arr["nama_gudang"]) {

                        $this->CI->db->where("LOWER(nama_toko)", strtolower($new_arr["nama_gudang"]));
                        $this->CI->db->where("jenis", "GUDANG");
                        $query = $this->CI->db->get("tbl_toko");

                        if (!$query->num_rows()) {

                            $status .= "<span class='text-danger'>Nama Gudang Tidak Terdaftar</span><br>";
                        }
                    } else {

                        $status .= "<span class='text-danger'>Nama Gudang Harus Di Isi</span><br>";
                    }

                    if ($new_arr["jumlah_barang"]) {

                        if (!is_numeric($new_arr["jumlah_barang"])) {

                            $status .= "<span class='text-danger'>Jumlah Harus Berupa Angka</span><br>";
                        }
                    } else {

                        $status .= "<span class='text-danger'>Jumlah Harus Di Isi</span><br>";
                    }

                    if (!$status) {

                        $status = "valid";
                    }

                    $new_arr["status"] = $status;
                    $new_arr["user_input"] = $this->CI->session->userdata("username");
                    $new_arr["created_at"] = date("Y-m-d H:i:s");

                    $this->CI->db->insert("tbl_import_gudang_baru", $new_arr);
                }

                $this->CI->session->set_flashdata('berhasil', 'Data Berhasil Di Import');
                redirect('gudang/import_gudang/data_baru', 'refresh');

                break;
        }
    }

    private function export($data)
    {

        $activeWorksheet = $this->spreadsheet->getActiveSheet();

        $activeWorksheet->getColumnDimension('A')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('B')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('C')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('D')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('E')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('F')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('G')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('H')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('I')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('J')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('K')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('L')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('M')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('N')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('O')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('P')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('Q')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('R')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('S')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('T')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('U')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('V')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('W')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('X')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('Y')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('Z')->setAutoSize(true);

        $activeWorksheet->getColumnDimension('AA')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AB')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AC')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AD')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AE')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AF')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AG')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AH')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AI')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AJ')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AK')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AL')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AM')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AN')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AO')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AP')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AQ')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AR')->setAutoSize(true);
        $activeWorksheet->getColumnDimension('AS')->setAutoSize(true);

        $styleArray = [

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'FFE5E4' // Kode warna RGB (misalnya, kuning)
                ]
            ]
        ];

        $styleArray1 = [
            'font' => [
                'bold' => true,
                'size' => 13
            ],
        ];

        $styleArray2 = [
            'borders' => [
                'diagonalDirection' => Borders::DIAGONAL_BOTH,
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        switch ($data["jenis"]) {
            case "laporan_transaksi":

                $activeWorksheet->mergeCells("A1:D1");
                $activeWorksheet->mergeCells("A2:D2");

                $range_tanggal = "";

                if ($data["range"]) {

                    if (strlen($data["range"]) > 10) {

                        $range_tanggal = str_replace("to", "sampai", $data["range"]);
                    } else {

                        $range_tanggal = $data["range"];
                    }
                }

                $activeWorksheet->setCellValue("A1", "Laporan Transaksi");
                $activeWorksheet->setCellValue("A2", ($range_tanggal) ? "Tanggal : " . $range_tanggal : "");
                $activeWorksheet->setCellValue("A3", "No");
                $activeWorksheet->setCellValue("B3", "Kode transaksi");
                $activeWorksheet->setCellValue("C3", "Kode order");
                $activeWorksheet->setCellvalue("D3", "Nama toko");
                $activeWorksheet->setCellValue("E3", "Nama customer");
                $activeWorksheet->setCellvalue("F3", "Tipe order");
                $activeWorksheet->setCellvalue("G3", "Total keranjang");
                $activeWorksheet->setCellvalue("H3", "Total diskon");
                $activeWorksheet->setCellvalue("I3", "Total setelah diskon");
                $activeWorksheet->setCellvalue("J3", "Total biaya kirim");
                $activeWorksheet->setCellvalue("K3", "Total tagihan");
                $activeWorksheet->setCellvalue("L3", "Terbayar");
                $activeWorksheet->setCellvalue("M3", "Kembalian");
                $activeWorksheet->setCellvalue("N3", "Tipe transaksi");
                $activeWorksheet->setCellvalue("O3", "Tanggal transaksi");
                $activeWorksheet->setCellvalue("P3", "User");

                $activeWorksheet->getStyle("A3:P3")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A3:P3")->applyFromArray($styleArray1);

                $no         = 1;
                $first_cell = 4;
                $cell       = 4;

                $tagihan_cart                   = 0;
                $total_diskon                   = 0;
                $tagihan_after_diskon           = 0;
                $total_biaya_kirim              = 0;
                $total_tagihan                  = 0;
                $terbayar                       = 0;
                $kembalian                      = 0;

                foreach ($data["data_transaksi"] as $dt) {

                    $activeWorksheet->setCellValue("A$cell", $no);
                    $activeWorksheet->setCellValue("B$cell", $dt["kode_transaksi"]);
                    $activeWorksheet->setCellValue("C$cell", $dt["kode_order"]);
                    $activeWorksheet->setCellvalue("D$cell", $dt["nama_toko"]);
                    $activeWorksheet->setCellValue("E$cell", $dt["nama_cust"]);
                    $activeWorksheet->setCellvalue("F$cell", $dt["tipe_order"]);
                    $activeWorksheet->setCellvalue("G$cell", ($dt["tagihan_cart"]) ? $dt["tagihan_cart"] : 0);
                    $activeWorksheet->setCellvalue("H$cell", ($dt["total_diskon"]) ? $dt["tagihan_cart"] : 0);
                    $activeWorksheet->setCellvalue("I$cell", ($dt["tagihan_after_diskon"]) ? $dt["tagihan_after_diskon"] : 0);
                    $activeWorksheet->setCellvalue("J$cell", ($dt["total_biaya_kirim"]) ? $dt["total_biaya_kirim"] : 0);
                    $activeWorksheet->setCellvalue("K$cell", ($dt["total_tagihan"]) ? $dt["total_tagihan"] : 0);
                    $activeWorksheet->setCellvalue("L$cell", ($dt["terbayar"]) ? $dt["terbayar"] : 0);
                    $activeWorksheet->setCellvalue("M$cell", ($dt["kembalian"]) ? $dt["kembalian"] : 0);
                    $activeWorksheet->setCellvalue("N$cell", strtolower($dt["tipe_transaksi"]));
                    $activeWorksheet->setCellvalue("O$cell", $dt["created_at"]);
                    $activeWorksheet->setCellvalue("P$cell", $dt["user_input"]);

                    $tagihan_cart                   +=  ($dt["tagihan_cart"]) ? $dt["tagihan_cart"] : 0;
                    $total_diskon                   +=  ($dt["total_diskon"]) ? $dt["total_diskon"] : 0;
                    $tagihan_after_diskon           +=  ($dt["tagihan_after_diskon"]) ? $dt["tagihan_after_diskon"] : 0;
                    $total_biaya_kirim              +=  ($dt["total_biaya_kirim"]) ? $dt["total_biaya_kirim"] : 0;
                    $total_tagihan                  +=  ($dt["total_tagihan"]) ? $dt["total_tagihan"] : 0;
                    $terbayar                       +=  ($dt["terbayar"]) ? $dt["terbayar"] : 0;
                    $kembalian                      +=  ($dt["kembalian"]) ? $dt["kembalian"] : 0;

                    $no++;
                    $cell++;
                }

                $activeWorksheet->getStyle("G$first_cell:G$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("H$first_cell:H$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("I$first_cell:I$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("J$first_cell:J$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("K$first_cell:K$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("L$first_cell:L$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("M$first_cell:M$cell")->getNumberFormat()->setFormatCode('#,##0');

                // $cell++;

                $activeWorksheet->mergeCells("A$cell:F$cell");
                $activeWorksheet->setCellValue("A$cell", "Total");
                $activeWorksheet->setCellValue("G$cell", $tagihan_cart);
                $activeWorksheet->setCellValue("H$cell", $total_diskon);
                $activeWorksheet->setCellvalue("I$cell", $tagihan_after_diskon);
                $activeWorksheet->setCellValue("J$cell", $total_biaya_kirim);
                $activeWorksheet->setCellvalue("K$cell", $total_tagihan);
                $activeWorksheet->setCellvalue("L$cell", $terbayar);
                $activeWorksheet->setCellvalue("M$cell", $kembalian);

                $activeWorksheet->getStyle("G$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("H$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("I$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("J$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("K$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("L$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("M$cell")->getNumberFormat()->setFormatCode('#,##0');

                $activeWorksheet->getStyle("A$cell:P$cell")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A$cell:P$cell")->applyFromArray($styleArray1);

                break;

            case "laporan_transaksi_cacat":

                $activeWorksheet->mergeCells("A1:D1");
                $activeWorksheet->mergeCells("A2:D2");

                $range_tanggal = "";

                if ($data["range"]) {

                    if (strlen($data["range"]) > 10) {

                        $range_tanggal = str_replace("to", "sampai", $data["range"]);
                    } else {

                        $range_tanggal = $data["range"];
                    }
                }

                $activeWorksheet->setCellValue("A1", "Laporan Transaksi");
                $activeWorksheet->setCellValue("A2", ($range_tanggal) ? "Tanggal : " . $range_tanggal : "");
                $activeWorksheet->setCellValue("A3", "No");
                $activeWorksheet->setCellValue("B3", "Kode transaksi");
                $activeWorksheet->setCellValue("C3", "Kode order");
                $activeWorksheet->setCellvalue("D3", "Nama toko");
                $activeWorksheet->setCellValue("E3", "Nama customer");
                $activeWorksheet->setCellvalue("F3", "Tipe order");
                $activeWorksheet->setCellvalue("G3", "Total keranjang");
                $activeWorksheet->setCellvalue("H3", "Total diskon");
                $activeWorksheet->setCellvalue("I3", "Total setelah diskon");
                $activeWorksheet->setCellvalue("J3", "Total biaya kirim");
                $activeWorksheet->setCellvalue("K3", "Total tagihan");
                $activeWorksheet->setCellvalue("L3", "Terbayar");
                $activeWorksheet->setCellvalue("M3", "Kembalian");
                $activeWorksheet->setCellvalue("N3", "Tipe transaksi");
                $activeWorksheet->setCellvalue("O3", "Tanggal transaksi");

                $activeWorksheet->getStyle("A3:O3")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A3:O3")->applyFromArray($styleArray1);

                $no         = 1;
                $first_cell = 4;
                $cell       = 4;

                $tagihan_cart                   = 0;
                $total_diskon                   = 0;
                $tagihan_after_diskon           = 0;
                $total_biaya_kirim              = 0;
                $total_tagihan                  = 0;
                $terbayar                       = 0;
                $kembalian                      = 0;

                foreach ($data["data_transaksi"] as $dt) {

                    $activeWorksheet->setCellValue("A$cell", $no);
                    $activeWorksheet->setCellValue("B$cell", $dt["kode_transaksi"]);
                    $activeWorksheet->setCellValue("C$cell", $dt["kode_order"]);
                    $activeWorksheet->setCellvalue("D$cell", $dt["nama_toko"]);
                    $activeWorksheet->setCellValue("E$cell", $dt["nama_cust"]);
                    $activeWorksheet->setCellvalue("F$cell", $dt["tipe_order"]);
                    $activeWorksheet->setCellvalue("G$cell", ($dt["tagihan_cart"]) ? $dt["tagihan_cart"] : 0);
                    $activeWorksheet->setCellvalue("H$cell", ($dt["total_diskon"]) ? $dt["tagihan_cart"] : 0);
                    $activeWorksheet->setCellvalue("I$cell", ($dt["tagihan_after_diskon"]) ? $dt["tagihan_after_diskon"] : 0);
                    $activeWorksheet->setCellvalue("J$cell", ($dt["total_biaya_kirim"]) ? $dt["total_biaya_kirim"] : 0);
                    $activeWorksheet->setCellvalue("K$cell", ($dt["total_tagihan"]) ? $dt["total_tagihan"] : 0);
                    $activeWorksheet->setCellvalue("L$cell", ($dt["terbayar"]) ? $dt["terbayar"] : 0);
                    $activeWorksheet->setCellvalue("M$cell", ($dt["kembalian"]) ? $dt["kembalian"] : 0);
                    $activeWorksheet->setCellvalue("N$cell", strtolower($dt["tipe_transaksi"]));
                    $activeWorksheet->setCellvalue("O$cell", $dt["created_at"]);

                    $tagihan_cart                   +=  ($dt["tagihan_cart"]) ? $dt["tagihan_cart"] : 0;
                    $total_diskon                   +=  ($dt["total_diskon"]) ? $dt["total_diskon"] : 0;
                    $tagihan_after_diskon           +=  ($dt["tagihan_after_diskon"]) ? $dt["tagihan_after_diskon"] : 0;
                    $total_biaya_kirim              +=  ($dt["total_biaya_kirim"]) ? $dt["total_biaya_kirim"] : 0;
                    $total_tagihan                  +=  ($dt["total_tagihan"]) ? $dt["total_tagihan"] : 0;
                    $terbayar                       +=  ($dt["terbayar"]) ? $dt["terbayar"] : 0;
                    $kembalian                      +=  ($dt["kembalian"]) ? $dt["kembalian"] : 0;

                    $no++;
                    $cell++;
                }

                $activeWorksheet->getStyle("G$first_cell:G$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("H$first_cell:H$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("I$first_cell:I$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("J$first_cell:J$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("K$first_cell:K$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("L$first_cell:L$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("M$first_cell:M$cell")->getNumberFormat()->setFormatCode('#,##0');

                // $cell++;

                $activeWorksheet->mergeCells("A$cell:F$cell");
                $activeWorksheet->setCellValue("A$cell", "Total");
                $activeWorksheet->setCellValue("G$cell", $tagihan_cart);
                $activeWorksheet->setCellValue("H$cell", $total_diskon);
                $activeWorksheet->setCellvalue("I$cell", $tagihan_after_diskon);
                $activeWorksheet->setCellValue("J$cell", $total_biaya_kirim);
                $activeWorksheet->setCellvalue("K$cell", $total_tagihan);
                $activeWorksheet->setCellvalue("L$cell", $terbayar);
                $activeWorksheet->setCellvalue("M$cell", $kembalian);

                $activeWorksheet->getStyle("G$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("H$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("I$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("J$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("K$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("L$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("M$cell")->getNumberFormat()->setFormatCode('#,##0');

                $activeWorksheet->getStyle("A$cell:O$cell")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A$cell:O$cell")->applyFromArray($styleArray1);

                break;

            case "laporan_penjualan":
               

                $activeWorksheet->mergeCells("B1:K1");
                $activeWorksheet->mergeCells("B2:K2");

                if ($data['rangeTanggal']) {
                    if (strlen($data['rangeTanggal']) > 10) {
                        $tanggal = str_replace('to', 's/d', $data['rangeTanggal']);
                        list($start_date, $end_date) = explode('s/d', $tanggal);
                        $formatDateStart = date('Y-m-d', strtotime($start_date));
                        $formatDateEnd = date('Y-m-d', strtotime($end_date));
                        $rangeTanggal = $formatDateStart . ' s/d ' . $formatDateEnd;
                    } else {
                        $rangeTanggal = $data['rangeTanggal'];
                    }
                }

                $activeWorksheet->setCellValue("B1", "LAPORAN PENJUALAN");
                $activeWorksheet->getStyle("B1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->getStyle("B1")->applyFromArray($styleArray1);

                $activeWorksheet->setCellValue("B2", ($rangeTanggal) ? 'Tanggal : ' . $rangeTanggal : '');
                $activeWorksheet->getStyle("B2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->getStyle("B2")->applyFromArray($styleArray1);

                $activeWorksheet->setCellValue("B5", "No");
                $activeWorksheet->setCellValue("C5", "Toko");
                $activeWorksheet->setCellValue("D5", "Kode order");
                $activeWorksheet->setCellValue("E5", "Kode transaksi");
                $activeWorksheet->setCellValue("F5", "Nama barang");
                if($data['sess_admin_toko']){
                    $activeWorksheet->setCellValue("G5", "Harga satuan pokok");
                }
                $activeWorksheet->setCellValue("H5", "Harga satuan jual");
                $activeWorksheet->setCellValue("I5", "Qty");
                if($data['sess_admin_toko']){
                    $activeWorksheet->setCellValue("J5", "Total harga pokok");
                }
                $activeWorksheet->setCellValue("K5", "Total harga jual");
                $activeWorksheet->setCellValue("L5", "Total diskon");
                $activeWorksheet->setCellValue("M5", "Total keuntungan");
                $activeWorksheet->setCellValue("N5", "Tanggal beli");


                $activeWorksheet->getStyle("B5:N5")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("B5:N5")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("B5:N5")->applyFromArray($styleArray2);

                $urutan = 1;
                $cell = 6;
                $first_cell = 6;

                $totalSemuaKeuntungan = 0;

                foreach ($data['data_penjualan'] as $d) {

                    $activeWorksheet->setCellValue("B$cell", $urutan);
                    $activeWorksheet->setCellValue("C$cell", $d["nama_toko"]);
                    $activeWorksheet->setCellValue("D$cell", $d["kode_order"]);
                    $activeWorksheet->setCellValue("E$cell", $d["kode_transaksi"]);
                    $activeWorksheet->setCellValue("F$cell", $d["nama_barang"]);
                    if($data['sess_admin_toko']){
                        $activeWorksheet->setCellValue("G$cell", $d["harga_satuan_pokok"]);
                    }
                    $activeWorksheet->setCellValue("H$cell", $d["harga_satuan_jual"]);
                    $activeWorksheet->setCellValue("I$cell", $d["qty"]);
                    if($data['sess_admin_toko']){
                        $activeWorksheet->setCellValue("J$cell", $d["total_harga_pokok"]);
                    }
                    $activeWorksheet->setCellValue("K$cell", $d["total_harga_jual"]);
                    $activeWorksheet->setCellValue("L$cell", $d["total_diskon"]);
                    $activeWorksheet->setCellValue("M$cell", $d["total_keuntungan"]);
                    $activeWorksheet->setCellValue("N$cell", $d["tanggal_beli"]);

                    $activeWorksheet->getStyle("B$cell:N$cell")->applyFromArray($styleArray2);

                    $totalSemuaKeuntungan  += $d['total_keuntungan'];

                    $urutan++;
                    $cell++;
                }

                if($data['sess_admin_toko']){
                    $activeWorksheet->getStyle("G$first_cell:G$cell")->getNumberFormat()->setFormatCode('#,##0');
                }
                $activeWorksheet->getStyle("H$first_cell:H$cell")->getNumberFormat()->setFormatCode('#,##0');
                if($data['sess_admin_toko']){
                    $activeWorksheet->getStyle("J$first_cell:J$cell")->getNumberFormat()->setFormatCode('#,##0');
                }
                $activeWorksheet->getStyle("K$first_cell:K$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("L$first_cell:L$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("M$first_cell:M$cell")->getNumberFormat()->setFormatCode('#,##0');

                $activeWorksheet->mergeCells("B$cell:L$cell");
                $activeWorksheet->setCellValue("B$cell", "Total");
                $activeWorksheet->getStyle("B$cell")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->setCellValue("M$cell", $totalSemuaKeuntungan);

                $activeWorksheet->getStyle("M$cell")->getNumberFormat()->setFormatCode('#,##0');

                $activeWorksheet->getStyle("B$cell:N$cell")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("B$cell:N$cell")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("B$cell:N$cell")->applyFromArray($styleArray2);

                break;
            case "laporan_penjualan_cacat":

                $activeWorksheet->mergeCells("B1:K1");
                $activeWorksheet->mergeCells("B2:K2");

                if ($data['rangeTanggal']) {
                    if (strlen($data['rangeTanggal']) > 10) {
                        $tanggal = str_replace('to', 's/d', $data['rangeTanggal']);
                        list($start_date, $end_date) = explode('s/d', $tanggal);
                        $formatDateStart = date('Y-m-d', strtotime($start_date));
                        $formatDateEnd = date('Y-m-d', strtotime($end_date));
                        $rangeTanggal = $formatDateStart . ' s/d ' . $formatDateEnd;
                    } else {
                        $rangeTanggal = $data['rangeTanggal'];
                    }
                }

                $activeWorksheet->setCellValue("B1", "LAPORAN PENJUALAN CACAT");
                $activeWorksheet->getStyle("B1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->getStyle("B1")->applyFromArray($styleArray1);

                $activeWorksheet->setCellValue("B2", ($rangeTanggal) ? 'Tanggal : ' . $rangeTanggal : '');
                $activeWorksheet->getStyle("B2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->getStyle("B2")->applyFromArray($styleArray1);

                $activeWorksheet->setCellValue("B5", "No");
                $activeWorksheet->setCellValue("C5", "Toko");
                $activeWorksheet->setCellValue("D5", "Kode order");
                $activeWorksheet->setCellValue("E5", "Kode transaksi");
                $activeWorksheet->setCellValue("F5", "Nama barang");
                $activeWorksheet->setCellValue("G5", "Harga satuan pokok");
                $activeWorksheet->setCellValue("H5", "Harga satuan jual");
                $activeWorksheet->setCellValue("I5", "Qty");
                $activeWorksheet->setCellValue("J5", "Total harga pokok");
                $activeWorksheet->setCellValue("K5", "Total harga jual");
                $activeWorksheet->setCellValue("L5", "Total diskon");
                $activeWorksheet->setCellValue("M5", "Total keuntungan");
                $activeWorksheet->setCellValue("N5", "Tanggal beli");


                $activeWorksheet->getStyle("B5:N5")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("B5:N5")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("B5:N5")->applyFromArray($styleArray2);

                $urutan = 1;
                $cell = 6;
                $first_cell = 6;

                $totalSemuaKeuntungan = 0;

                foreach ($data['data_penjualan_cacat'] as $d) {

                    $activeWorksheet->setCellValue("B$cell", $urutan);
                    $activeWorksheet->setCellValue("C$cell", $d["nama_toko"]);
                    $activeWorksheet->setCellValue("D$cell", $d["kode_order"]);
                    $activeWorksheet->setCellValue("E$cell", $d["kode_transaksi"]);
                    $activeWorksheet->setCellValue("F$cell", $d["nama_barang"]);
                    $activeWorksheet->setCellValue("G$cell", $d["harga_satuan_pokok"]);
                    $activeWorksheet->setCellValue("H$cell", $d["harga_satuan_jual"]);
                    $activeWorksheet->setCellValue("I$cell", $d["qty"]);
                    $activeWorksheet->setCellValue("J$cell", $d["total_harga_pokok"]);
                    $activeWorksheet->setCellValue("K$cell", $d["total_harga_jual"]);
                    $activeWorksheet->setCellValue("L$cell", $d["total_diskon"]);
                    $activeWorksheet->setCellValue("M$cell", $d["total_keuntungan"]);
                    $activeWorksheet->setCellValue("N$cell", $d["tanggal_beli"]);

                    $activeWorksheet->getStyle("B$cell:N$cell")->applyFromArray($styleArray2);

                    $totalSemuaKeuntungan  += $d['total_keuntungan'];

                    $urutan++;
                    $cell++;
                }

                $activeWorksheet->getStyle("G$first_cell:G$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("H$first_cell:H$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("J$first_cell:J$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("K$first_cell:K$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("L$first_cell:L$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("M$first_cell:M$cell")->getNumberFormat()->setFormatCode('#,##0');

                $activeWorksheet->mergeCells("B$cell:L$cell");
                $activeWorksheet->setCellValue("B$cell", "Total");
                $activeWorksheet->getStyle("B$cell")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->setCellValue("M$cell", $totalSemuaKeuntungan);

                $activeWorksheet->getStyle("M$cell")->getNumberFormat()->setFormatCode('#,##0');

                $activeWorksheet->getStyle("B$cell:N$cell")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("B$cell:N$cell")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("B$cell:N$cell")->applyFromArray($styleArray2);

                break;

            case "laporan_pendapatan":

                $activeWorksheet->mergeCells("A1:L1");
                $activeWorksheet->mergeCells("A2:L2");
                $activeWorksheet->mergeCells("A3:L3");
                $activeWorksheet->mergeCells("A4:L4");

                if ($data['rangeTanggal']) {
                    if (strlen($data['rangeTanggal']) > 10) {
                        $tanggal = str_replace('to', 's/d', $data['rangeTanggal']);
                        list($start_date, $end_date) = explode('s/d', $tanggal);
                        $formatDateStart = date('Y-m-d', strtotime($start_date));
                        $formatDateEnd = date('Y-m-d', strtotime($end_date));
                        $rangeTanggal = $formatDateStart . ' s/d ' . $formatDateEnd;
                    } else {
                        $rangeTanggal = $data['rangeTanggal'];
                    }
                }

                if ($data['toko'] != '') {
                    $toko = $data['toko'];
                } else {
                    $toko = 'Semua Toko Tampil';
                }


                $activeWorksheet->setCellValue("A1", "LAPORAN PENDAPATAN");
                $activeWorksheet->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->getStyle("A1")->applyFromArray($styleArray1);

                $activeWorksheet->setCellValue("A2", "Tanggal : " . $rangeTanggal);
                $activeWorksheet->getStyle("A2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->getStyle("A2")->applyFromArray($styleArray1);

                $activeWorksheet->setCellValue("A3", "Toko : " . $toko);
                $activeWorksheet->getStyle("A3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $activeWorksheet->getStyle("A3")->applyFromArray($styleArray1);


                $activeWorksheet->setCellValue("A6", "No");
                $activeWorksheet->setCellValue("B6", "Barang");
                $activeWorksheet->setCellValue("C6", "Harga Pokok");
                $activeWorksheet->setCellValue("D6", "Stok Sekarang");
                $activeWorksheet->setCellValue("E6", "Harga Jual");
                $activeWorksheet->setCellValue("F6", "Qty");
                $activeWorksheet->setCellValue("G6", "Diskon");
                $activeWorksheet->setCellValue("H6", "Total");
                $activeWorksheet->setCellValue("I6", "Total Satuan Keuntungan");
                $activeWorksheet->setCellValue("J6", "Total Keuntungan");
                $activeWorksheet->setCellValue("K6", "Omzet");
                $activeWorksheet->setCellValue("L6", "Omzet Bersih");

                $activeWorksheet->getStyle("A6:L6")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A6:L6")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("A6:L6")->applyFromArray($styleArray2);

                $urutan = 1;
                $cell = 7;
                $first_cell = 7;


                $totalHargaPokok = 0;
                $totalStokToko = 0;
                $totalHargaJualPendapatan = 0;
                $totalQty = 0;
                $totalDiskon = 0;
                $totalPendapatan = 0;
                $totalSatuanKeuntunganPendapatan = 0;
                $totalKeuntunganPendapatan = 0;
                $totalOmzet = 0;
                $totalOmzetBerihPendapatan = 0;




                foreach ($data['data_pendapatan'] as $d) {

                    $jsonString = $d['harga_potongan'];
                    preg_match('/\[(.*?)\]/', $jsonString, $matches);
                    $arrayData = json_decode($matches[0], true);
                    $hargaPotongan = isset($arrayData[0]['harga_potongan']) ? $arrayData[0]['harga_potongan'] : '';

                    $total =  ($hargaPotongan == '') ? (int)$d['harga_jual'] + (int)$d['qty'] + 0 : (int)$d['harga_jual'] + (int)$d['qty'] + $hargaPotongan;
                    $totalSatuanKeuntungan = ($hargaPotongan == '') ? ((int)$d['harga_jual'] - 0) - (int)$d['harga_pokok'] : ((int)$d['harga_jual'] - $hargaPotongan) - (int)$d['harga_pokok'];

                    $totalKeuntungan = (int)$d['qty'] * $totalSatuanKeuntungan;
                    $omzet = (int)$d['harga_jual'] * (int)$d['qty'];
                    $omzetBersih = ($hargaPotongan == '') ? $omzet - 0 - (int)$d['stok_toko'] : $omzet - $hargaPotongan - (int)$d['stok_toko'];


                    $activeWorksheet->setCellValue("A$cell", $urutan);
                    $activeWorksheet->setCellValue("B$cell", $d["nama_barang"]);
                    $activeWorksheet->setCellValue("C$cell", $d["harga_pokok"]);
                    $activeWorksheet->setCellValue("D$cell", $d["stok_toko"]);
                    $activeWorksheet->setCellValue("E$cell", $d["harga_jual"]);
                    $activeWorksheet->setCellValue("F$cell", $d["qty"]);
                    $activeWorksheet->setCellValue("G$cell", ($hargaPotongan == '') ? 0 : $hargaPotongan);
                    $activeWorksheet->setCellValue("H$cell", $total);
                    $activeWorksheet->setCellValue("I$cell", $totalSatuanKeuntungan);
                    $activeWorksheet->setCellValue("J$cell", $totalKeuntungan);
                    $activeWorksheet->setCellValue("K$cell", $omzet);
                    $activeWorksheet->setCellValue("L$cell", $omzetBersih);


                    $activeWorksheet->getStyle("A$cell:L$cell")->applyFromArray($styleArray2);

                    $totalHargaPokok                    += ($d['harga_pokok']) ? $d['harga_pokok'] : 0;
                    $totalStokToko                      += ($d['stok_toko']) ? $d['stok_toko'] : 0;
                    $totalHargaJualPendapatan           += ($d['harga_jual']) ? $d['harga_jual'] : 0;
                    $totalQty                           += ($d['qty']) ? $d['qty'] : 0;
                    $totalDiskon                        += ($hargaPotongan) ? $hargaPotongan : 0;
                    $totalPendapatan                    += ($total) ? $total : 0;
                    $totalSatuanKeuntunganPendapatan    += ($totalSatuanKeuntungan) ? $totalSatuanKeuntungan : 0;
                    $totalKeuntunganPendapatan          += ($totalKeuntungan) ? $totalKeuntungan : 0;
                    $totalOmzet                         += ($omzet) ? $omzet : 0;
                    $totalOmzetBerihPendapatan          += ($omzetBersih) ? $omzetBersih : 0;

                    $urutan++;
                    $cell++;
                }

                $activeWorksheet->setCellValue("A4", "Omzet : Rp. " . $totalOmzet);
                $activeWorksheet->getStyle("A4:A4")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("A4")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $activeWorksheet->getStyle("A4")->applyFromArray($styleArray1);

                $activeWorksheet->getStyle("C$first_cell:C$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("D$first_cell:D$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("E$first_cell:E$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("F$first_cell:F$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("G$first_cell:G$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("H$first_cell:H$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("I$first_cell:I$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("J$first_cell:J$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("K$first_cell:K$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("L$first_cell:L$cell")->getNumberFormat()->setFormatCode('#,##0');

                $activeWorksheet->mergeCells("A$cell:B$cell");
                $activeWorksheet->setCellValue("A$cell", "Grand Total");
                $activeWorksheet->getStyle("A$cell")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $activeWorksheet->setCellValue("C$cell", $totalHargaPokok);
                $activeWorksheet->setCellValue("D$cell", $totalStokToko);
                $activeWorksheet->setCellValue("E$cell", $totalHargaJualPendapatan);
                $activeWorksheet->setCellValue("F$cell", $totalQty);
                $activeWorksheet->setCellValue("G$cell", $totalDiskon);
                $activeWorksheet->setCellValue("H$cell", $totalPendapatan);
                $activeWorksheet->setCellValue("I$cell", $totalSatuanKeuntunganPendapatan);
                $activeWorksheet->setCellValue("J$cell", $totalKeuntunganPendapatan);
                $activeWorksheet->setCellValue("K$cell", $totalOmzet);
                $activeWorksheet->setCellValue("L$cell", $totalOmzetBerihPendapatan);


                $activeWorksheet->getStyle("C$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("D$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("E$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("F$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("G$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("H$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("I$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("J$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("K$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->getStyle("L$cell")->getNumberFormat()->setFormatCode('#,##0');


                $activeWorksheet->getStyle("A$cell:L$cell")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A$cell:L$cell")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("A$cell:L$cell")->applyFromArray($styleArray2);

                break;

            case "laporan_stok":

                $activeWorksheet->mergeCells("A1:C1");
                $activeWorksheet->mergeCells("A2:C2");
                $activeWorksheet->mergeCells("A3:C3");
                $activeWorksheet->mergeCells("A4:C4");

                $activeWorksheet->setCellValue("A1", "Laporan Stok");
                $activeWorksheet->setCellValue("A2", "TOKO : " . $data["toko"]);
                $activeWorksheet->setCellValue("A3", "BARANG : " . $data["barang"]);
                $activeWorksheet->setCellValue("A4", "TANGGAL : " . $data["tanggal"]);
                $activeWorksheet->setCellValue("A5", "No");
                // $activeWorksheet->setCellValue("B5", "Toko");
                $activeWorksheet->setCellValue("B5", "Kode barang");
                $activeWorksheet->setCellvalue("C5", "Nama barang");
                $activeWorksheet->setCellvalue("D5", "Jumlah stok tersedia");
                $activeWorksheet->setCellvalue("E5", "Jumlah stok toko");
                $activeWorksheet->setCellvalue("F5", "Jumlah stok gudang");
                $activeWorksheet->setCellvalue("G5", "Harga Jual");
                $activeWorksheet->setCellValue("H5", "Jumlah masuk");
                $activeWorksheet->setCellvalue("I5", "Jumlah keluar");

                $activeWorksheet->getStyle("A5:I5")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A5:I5")->applyFromArray($styleArray1);

                $no         = 1;
                $first_cell = 6;
                $cell       = 6;

                $totalJmlMasuk  = 0;
                $totalJmlKeluar = 0;
                $totalJmlStok = 0;
                $totalJmlStokToko = 0;
                $totalJmlStokGudang = 0;
                $totalHargaJual = 0;

                foreach ($data["data_stok"] as $dt) {
                    $jml_stok = $dt['jml_stok'] + $dt['stok_gudang'];

                    $activeWorksheet->setCellValue("A$cell", $no);
                    // $activeWorksheet->setCellValue("B$cell", $dt["nama_toko"]);
                    $activeWorksheet->setCellValue("B$cell", $dt["kode_barang"]);
                    $activeWorksheet->setCellvalue("C$cell", $dt["nama_barang"]);
                    $activeWorksheet->setCellValue("D$cell", $jml_stok);
                    $activeWorksheet->setCellValue("E$cell", (!empty($dt["stok_toko"])) ? $dt["stok_toko"] : 0);
                    $activeWorksheet->setCellValue("F$cell", (!empty($dt["stok_gudang"])) ? $dt["stok_gudang"] : 0);
                    $activeWorksheet->setCellvalue("G$cell", $dt["harga_jual"]);
                    $activeWorksheet->setCellValue("H$cell", $dt["jml_masuk"]);
                    $activeWorksheet->setCellvalue("I$cell", $dt["jml_keluar"]);

                    $totalHargaJual += $dt["harga_jual"];
                    // $totalJmlStok += $dt["jml_stok"];
                    $totalJmlStok += $jml_stok;
                    $totalJmlStokToko += $dt["stok_toko"];
                    $totalJmlStokGudang += $dt["stok_gudang"];
                    $totalJmlMasuk += $dt["jml_masuk"];
                    $totalJmlKeluar += $dt["jml_keluar"];

                    $no++;
                    $cell++;
                }

            
                $activeWorksheet->mergeCells("A$cell:C$cell");
                $activeWorksheet->setCellValue("A$cell", "Total");
                $activeWorksheet->getStyle("A$cell")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $activeWorksheet->setCellValue("D$cell", $totalJmlStok);
                $activeWorksheet->setCellValue("E$cell", $totalJmlStokToko);
                $activeWorksheet->setCellValue("F$cell", $totalJmlStokGudang);
                $activeWorksheet->setCellValue("G$cell",  $totalHargaJual);
                $activeWorksheet->getStyle("G$cell")->getNumberFormat()->setFormatCode('#,##0');
                $activeWorksheet->setCellValue("H$cell", $totalJmlMasuk);
                $activeWorksheet->setCellValue("I$cell", $totalJmlKeluar);

                $activeWorksheet->getStyle("A$cell:I$cell")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A$cell:I$cell")->applyFromArray($styleArray1);

                break;

            case "barang_toko":

                $activeWorksheet->mergeCells("A1:D1");
                $activeWorksheet->setCellValue("A1", "LIST BARANG TOKO " . strtoupper($data["data_barang_toko"][0]["nama_toko"]));
                $activeWorksheet->mergeCells("A2:D2");
                $activeWorksheet->setCellValue("A2", "Tanggal Cetak : " . date("d-m-Y"));
                $activeWorksheet->setCellValue("A3", "NO");
                $activeWorksheet->setCellValue("B3", "KODE BARANG");
                $activeWorksheet->setCellValue("C3", "NAMA BARANG");
                $activeWorksheet->setCellValue("D3", "KATEGORI");
                $activeWorksheet->setCellValue("E3", "STOK TERSEDIA");
                $activeWorksheet->setCellValue("F3", "STOK GUDANG");
                $activeWorksheet->setCellValue("G3", "STOK TOKO");
                $activeWorksheet->setCellValue("H3", "KODE BARCODE");
                $activeWorksheet->setCellValue("I3", "GAMBAR BARCODE");

                // $barcodeFont = 'Free 3 of 9'; 

                $cell = 4;
                $no = 1;
                foreach ($data["data_barang_toko"] as $d) {

                    $barcodesss = $d["barcode_barang"];
                    
                    $stok_tersedia = $d['stok_toko'] + $d['stok_gudang'];

                    // $formattedBarcode = "*$barcodesss*";

                    $path_barcode = FCPATH . "assets/barcodes/" . $barcodesss . ".png";

                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Barcode');
                    $drawing->setDescription('Barcode for ' . $barcodesss);
                    $drawing->setPath($path_barcode); // Path gambar barcode
                    $drawing->setCoordinates("I$cell");
                    $drawing->setHeight(60); // Atur tinggi gambar
                    $drawing->setWorksheet($activeWorksheet);

                    $activeWorksheet->getRowDimension($cell)->setRowHeight(60); // Tinggi baris 70

                    $activeWorksheet->setCellValue("A$cell", $no);
                    $activeWorksheet->setCellValue("B$cell", $d["kode_barang"]);
                    $activeWorksheet->setCellValue("C$cell", $d["nama_barang"]);
                    $activeWorksheet->setCellValue("D$cell", $d["nama_kategori"]);
                    $activeWorksheet->setCellValue("E$cell", $stok_tersedia);
                    $activeWorksheet->setCellValue("F$cell", (!empty($d['stok_gudang'])) ? $d['stok_gudang'] : 0);
                    $activeWorksheet->setCellValue("G$cell", $d["stok_toko"]);
                    $activeWorksheet->setCellValue("H$cell", "'" . $d["barcode_barang"]);
                    // $activeWorksheet->setCellValue("G$cell", $formattedBarcode);
                    $activeWorksheet->setCellValue("J$cell", $d["id_harga"]);

                    // $activeWorksheet->getStyle("H$cell")->getFont()
                    //     ->setName($barcodeFont) // Nama font
                    //     ->setSize(20);         // Ukuran font

                    $cell++;
                    $no++;
                }

                $readonlyColumn = "J";
                $activeWorksheet->getStyle($readonlyColumn . '4:' . $readonlyColumn . $cell)->getProtection()->setLocked(true);
                $activeWorksheet->getProtection()->setSheet(true);

                $activeWorksheet->getStyle("A1:I1")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("A2:I2")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("A3:I3")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A3:I3")->applyFromArray($styleArray1);
                $activeWorksheet->getColumnDimension($readonlyColumn)->setCollapsed(true);
                $activeWorksheet->getColumnDimension($readonlyColumn)->setVisible(false);

                break;
            
            case "barang_gudang":

                $activeWorksheet->mergeCells("A1:D1");
                $activeWorksheet->setCellValue("A1", "LIST BARANG TOKO " . strtoupper($data["data_barang_toko"][0]["nama_toko"]));
                $activeWorksheet->mergeCells("A2:D2");
                $activeWorksheet->setCellValue("A2", "Tanggal Cetak : " . date("d-m-Y"));
                $activeWorksheet->setCellValue("A3", "NO");
                $activeWorksheet->setCellValue("B3", "KODE BARANG");
                $activeWorksheet->setCellValue("C3", "NAMA BARANG");
                $activeWorksheet->setCellValue("D3", "KATEGORI");
                $activeWorksheet->setCellValue("E3", "STOK GUDANG");
                $activeWorksheet->setCellValue("F3", "KODE BARCODE");
                $activeWorksheet->setCellValue("G3", "GAMBAR BARCODE");
    
                // $barcodeFont = 'Free 3 of 9'; 
    
                $cell = 4;
                $no = 1;
                foreach ($data["data_barang_toko"] as $d) {
    
                    $barcodesss = $d["barcode_barang"];
                        
                    $stok_tersedia = $d['stok_toko'] + $d['stok_gudang'];
    
                    // $formattedBarcode = "*$barcodesss*";
    
                    $path_barcode = FCPATH . "assets/barcodes/" . $barcodesss . ".png";
    
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Barcode');
                    $drawing->setDescription('Barcode for ' . $barcodesss);
                    $drawing->setPath($path_barcode); // Path gambar barcode
                    $drawing->setCoordinates("G$cell");
                    $drawing->setHeight(60); // Atur tinggi gambar
                    $drawing->setWorksheet($activeWorksheet);
    
                    $activeWorksheet->getRowDimension($cell)->setRowHeight(60); // Tinggi baris 70
    
                    $activeWorksheet->setCellValue("A$cell", $no);
                    $activeWorksheet->setCellValue("B$cell", $d["kode_barang"]);
                    $activeWorksheet->setCellValue("C$cell", $d["nama_barang"]);
                    $activeWorksheet->setCellValue("D$cell", $d["nama_kategori"]);
                    $activeWorksheet->setCellValue("E$cell", (!empty($d['stok_gudang'])) ? $d['stok_gudang'] : 0);
                    $activeWorksheet->setCellValue("F$cell", "'" . $d["barcode_barang"]);
                    // $activeWorksheet->setCellValue("G$cell", $formattedBarcode);
                    // $activeWorksheet->setCellValue("H$cell", $d["id_harga"]);
    
                    // $activeWorksheet->getStyle("H$cell")->getFont()
                    //     ->setName($barcodeFont) // Nama font
                    //     ->setSize(20);         // Ukuran font
    
                    $cell++;
                    $no++;
                }
    
                $readonlyColumn = "J";
                $activeWorksheet->getStyle($readonlyColumn . '4:' . $readonlyColumn . $cell)->getProtection()->setLocked(true);
                $activeWorksheet->getProtection()->setSheet(true);
    
                $activeWorksheet->getStyle("A1:G1")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("A2:G2")->applyFromArray($styleArray1);
                $activeWorksheet->getStyle("A3:G3")->applyFromArray($styleArray);
                $activeWorksheet->getStyle("A3:G3")->applyFromArray($styleArray1);
                $activeWorksheet->getColumnDimension($readonlyColumn)->setCollapsed(true);
                $activeWorksheet->getColumnDimension($readonlyColumn)->setVisible(false);
    
            break;
        }

        $this->export = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . rawurlencode($data['filename']) . '"');

        $this->export->save('php://output');
        exit;
    }
}
