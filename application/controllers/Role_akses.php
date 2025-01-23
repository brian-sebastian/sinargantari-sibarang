<?php

/**
 * @property User_model $user
 * @property Menu_model $menu
 * @property Submenu_model $submenu
 * @property Access_model $access
 * @property user $user
 * @property role $role
 * @property menu $menu
 * @property db $db
 * @property form_validation $form_validation
 * @property input $input
 * @property upload $upload
 * @property session $session
 */

class Role_akses extends CI_Controller
{

    private $view;

    public function __construct()
    {
        parent::__construct();
        cek_login();
        $this->load->model("User_model", "user");
        $this->load->model("Role_model", "role");
        $this->load->model("Menu_model", "menu");
        $this->load->model("Submenu_model", "submenu");
        $this->load->model("Access_model", "access");
    }

    public function index()
    {

        $this->view["title_menu"]   = "Management Akses";
        $this->view["title"]        = "Role";
        $this->view["content"]      = "role_akses/v_role_index";
        $this->view["data_user"]    = $this->user->ambilSemuaUserNotInKaryawantoko();
        $this->view["data_manu"]    = $this->menu->get_data_menu()->result_array();
        $this->view["data_role"]    = $this->role->tampil_data()->result_array();

        $this->load->view("layout/wrapper", $this->view);
    }

    public function tambah()
    {
        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "role",
                "label" => "Nama Role",
                "rules" => "required|trim|max_length[20]",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
        ];
        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["role"]       = htmlspecialchars($this->input->post('role'));
            $data_post["is_active"]  = 1;

            if ($this->role->insert_data($data_post)) {

                $data_json["status"] = "berhasil";
                $this->session->set_flashdata("berhasil", "Data Role berhasil ditambahkan");
            } else {

                $data_json["status"] = "gagal";
                $this->session->set_flashdata("gagal", "Data Role gagal ditambahkan");
            }
        } else {

            $data_json["status"]      = "error";
            $data_json["err_role"]    = form_error("role");
        }

        echo json_encode($data_json);
    }


    public function ubah_rolemodel($id)
    {
        $where = ['id_role' => base64_decode($id)];
        $this->view["data_role"]    = $this->role->edit_data($where)->row_array();

        $this->load->view("role_akses/v_role_ubahModal", $this->view);
    }

    public function update_role()
    {
        $form_validation = $this->form_validation;

        $field = [
            [
                "field" => "role",
                "label" => "Nama Role",
                "rules" => "required|trim|max_length[20]",
                "errors"    => ["required" => '%s tidak boleh kosong!'],
            ],
        ];

        $form_validation->set_rules($field);

        if ($form_validation->run() === true) {

            $data_post["id_role"]    = htmlspecialchars($this->input->post("id_role"));
            $data_post["role"]       = htmlspecialchars($this->input->post('role'));

            if ($this->role->update_data($data_post)) {

                $data_json["status"] = "berhasil";
                $this->session->set_flashdata("berhasil", "Data Role berhasil diubah");
            } else {

                $data_json["status"] = "gagal";
                $this->session->set_flashdata("gagal", "Data Role gagal diubah");
            }
        } else {

            $data_json["status"]      = "error";
            $data_json["err_roleU"]   = form_error("role");
        }

        echo json_encode($data_json);
    }

    public function delete($id_role)
    {
        if ($this->role->hapusData($id_role)) {

            $this->session->set_flashdata("berhasil", "Data Role berhasil dihapus");
        } else {

            $this->session->set_flashdata("gagal", "Data Role gagal dihapus");
        }

        redirect("role_akses", "refresh");
    }

    public function saveAccess()
    {
        $err_code = 0;
        $message = '';
        $selectedMenus = $this->input->post('menu');
        $selectedSubmenus = $this->input->post('submenu');
        $role_id = htmlspecialchars($this->input->post('role_id'));

        $roleAccessMenuData = [];
        foreach ($selectedMenus as $menuId) {
            $roleAccessMenuData[] = array(
                'role_id' => $role_id,
                'menu_id' => $menuId
            );
        }


        $roleAccessSubmenuData = [];
        foreach ($selectedSubmenus as $submenuId) {
            $roleAccessSubmenuData[] = array(
                'role_id' => $role_id,
                'submenu_id' => $submenuId
            );
        }

        // $insertMenu = $this->access->insertMenuAccess($roleAccessMenuData);
        // $insertSubMenu = $this->access->insertSubMenuAccess($roleAccessSubmenuData);
        $insertMenu = $this->access->giveAccessUserMenu($role_id, $roleAccessMenuData);
        $insertSubMenu = $this->access->giveAccessUserSubMenu($role_id, $roleAccessSubmenuData);


        if ($insertMenu == true) {
            $err_code = 0;
            $message = "Data Menu Berhasil Diaktifkan";
        } else {
            $err_code++;
            $message = "Data Menu Gagal Diaktifkan";
        }

        if ($insertSubMenu == true) {
            $err_code = 0;
            $message = "Data Sub Menu Berhasil Diaktifkan";
        } else {
            $err_code++;
            $message = "Data Sub Menu Gagal Diaktifkan";
        }
        $resultData = [
            'err_code' => $err_code,
            'message' => $message,
        ];
        echo json_encode($resultData);
    }


    public function akses_rolemodel($id)
    {
        $id_decode = base64_decode($id);
        $where = ['id_role' => $id_decode];
        $data["user"]    = $this->user->ambilSemuaUserNotInKaryawantoko();
        $data["role"]    = $this->role->edit_data($where)->row_array();
        $data['menu'] = $this->menu->get_data_menu()->result_array();

        $data['access_menu_active'] = $this->access->getAccessMenuActive($id_decode);
        $data['access_submenu_active'] = $this->access->getAccessSubMenuActive($id_decode);

        $this->load->view("role_akses/v_role_aksesModal", $data);
    }












    public function changeaccess_menu()
    {
        $id_menu = $this->input->post('id_menu');
        $id_role = $this->input->post('id_role');

        $data = [
            'role_id' => $id_role,
            'menu_id' => $id_menu
        ];

        $result = $this->db->get_where('tbl_privilege_menu', $data)->row_array();

        if (!$result) {

            if ($this->role->insert_data_privilege_menu($data)) {
                $this->session->set_flashdata("berhasil", "Menu berhasil diaktifkan");
            } else {
                $this->session->set_flashdata("gagal", "Menu gagal diaktifkan");
            }
        } else {
            $id_privilege = $result['id_privilege'];

            $data_delete = [
                'privilege_id' => $id_privilege,
                'submenu_id'   => 0
            ];

            if ($this->role->delete_data_privilege_submenu($data_delete)) {

                if ($this->role->delete_data_privilege_menu($data)) {

                    $this->session->set_flashdata("berhasil", "Menu berhasil dinonaktifkan");
                } else {
                    $this->session->set_flashdata("gagal", "Menu gagal dinonaktifkan");
                }
            } else {
                $this->session->set_flashdata("gagal", "Semua Submenu gagal dinonaktifkan");
            }
        }

        $array = array(
            'id_menu' => $id_menu
        );

        echo json_encode($array);
    }

    public function changeaccess_submenu()
    {
        $id_role    = $this->input->post('id_role');
        $id_submenu = $this->input->post('id_submenu');

        $data_submenu = array(
            'role_id'       => $id_role,
            'submenu_id'    => $id_submenu
        );

        $result = $this->role->getPrivilegeSubMenuByIdSubMenuDanIdRole($data_submenu);

        if (!$result) {

            $submenu = $this->db->get_where('tbl_submenu', ['id_submenu' => $id_submenu])->row_array();

            $id_menu = $submenu['menu_id'];

            $data_menu = array(
                'role_id' => $id_role,
                'menu_id' => $id_menu
            );

            $menu = $this->role->getMenuByIdMenuDanIdRole($data_menu);

            $id_privilege = $menu['id_privilege'];

            $data_insert = array(
                'privilege_id'  => $id_privilege,
                'submenu_id'    => $id_submenu
            );

            if ($this->role->insert_data_privilege_submenu($data_insert)) {

                $status_aktif = "berhasil aktif";
                $this->session->set_flashdata("berhasil", "Submenu berhasil diaktifkan");
            } else {
                $status_aktif = "gagal aktif";
                $this->session->set_flashdata("gagal", "Submenu gagal diaktifkan");
            }

            $status = $status_aktif;
        } else {

            $privilege_id   = $result['privilege_id'];
            $submenu_id     = $result['submenu_id'];

            $data_delete = array(
                'privilege_id'  => $privilege_id,
                'submenu_id'    => $id_submenu
            );

            if ($this->role->delete_data_privilege_submenu($data_delete)) {

                $status_nonaktif = "berhasil non-aktif";
                $this->session->set_flashdata("berhasil", "Submenu berhasil dinonaktifkan");
            } else {
                $status_nonaktif = "gagal non-aktif";
                $this->session->set_flashdata("gagal", "Submenu gagal dinonaktifkan");
            }

            $status =  $status_nonaktif;
        }

        $array = array(
            'status'    => $status,
            'id_submenu' => $id_submenu
        );

        echo json_encode($array);
    }
}
