<?php

function checkSidebarAccess($role_id)
{
    $ci3 = get_instance();
}


function queryMenuAccess()
{
    $ci3 = get_instance();
    $role_id = $ci3->session->userdata('role_id');

    $queryMenu = $ci3->db->select('*')
        ->from('tbl_menu')
        ->join('tbl_menu_access', 'tbl_menu.id_menu = tbl_menu_access.menu_id')
        ->join('tbl_role', 'tbl_menu_access.role_id = tbl_role.id_role')
        ->where('tbl_menu_access.role_id', $role_id)
        ->where('tbl_menu.is_active', 1)
        ->order_by('tbl_menu_access.menu_id', 'ASC')
        ->get();
    $menu = $queryMenu->result_array();
    return $menu;
}

function querySubmenu($menu_id)
{
    $ci3 = get_instance();
    $role_id = $ci3->session->userdata('role_id');
    $querySubmenu = $ci3->db->select('*')
        ->from('tbl_submenu')
        ->where('menu_id', $menu_id);

    $subMenu = $querySubmenu->result_array();
    return $subMenu;
}

function querySubmenuAccess($menu_id)
{
    $ci3 = get_instance();
    $role_id = $ci3->session->userdata('role_id');
    $querySubmenuAccess = $ci3->db->select('*')
        ->from('tbl_submenu')
        ->join('tbl_menu', 'tbl_submenu.menu_id = tbl_menu.id_menu')
        ->join('tbl_submenu_access', 'tbl_submenu.id_submenu = tbl_submenu_access.submenu_id')
        ->join('tbl_role', 'tbl_submenu_access.role_id = tbl_role.id_role')
        ->where('tbl_submenu_access.role_id', $role_id)
        ->where('tbl_submenu.menu_id', $menu_id)
        ->where('tbl_submenu.is_active', 1)
        ->order_by('tbl_submenu_access.submenu_id', 'ASC')
        ->get();

    $subMenuAccess = $querySubmenuAccess->result_array();
    return $subMenuAccess;
}

function querySubmenuAccessRow($menu_id)
{
    $ci3 = get_instance();
    $role_id = $ci3->session->userdata('role_id');
    $querySubmenuAccess = $ci3->db->select('*')
        ->from('tbl_submenu')
        ->join('tbl_menu', 'tbl_submenu.menu_id = tbl_menu.id_menu')
        ->join('tbl_submenu_access', 'tbl_submenu.id_submenu = tbl_submenu_access.submenu_id')
        ->join('tbl_role', 'tbl_submenu_access.role_id = tbl_role.id_role')
        ->where('tbl_submenu_access.role_id', $role_id)
        ->where('tbl_submenu.menu_id', $menu_id)
        ->where('tbl_submenu.is_active', 1)
        ->order_by('tbl_submenu_access.submenu_id', 'ASC')
        ->get();

    $subMenuAccess = $querySubmenuAccess->row_array();
    return $subMenuAccess;
}

function hasSubmenu($menuId)
{
    $ci3 = get_instance();
    // Query to check if there are submenus for the given menu ID
    $query = $ci3->db->get_where('tbl_submenu', array('menu_id' => $menuId, 'is_active' => 1));
    return $query->num_rows() > 1;
}


function profileImageCheck()
{
    $ci3 = get_instance();
    $user_id = $ci3->session->userdata('id_user');
    $image_profile = 'av3.png';

    $arrRessQHehe = [];
    $arrRessQHehe['image_profile'] = '';

    if (cekIsKaryawan($user_id)) {

        $arrRessQHehe['is_karyawan'] = true;

        $qKaryawan = $ci3->db->select('*')
            ->from('tbl_karyawan_toko')
            ->where('user_id', $user_id)
            ->get();
        $resQkar = $qKaryawan->row_array();
        if ($resQkar || !empty($resQkar)) {
            if ($resQkar['image_profile'] != null || $resQkar['image_profile'] != '') {
                $image_profile = $resQkar['image_profile'];
                $arrRessQHehe['image_profile'] = $image_profile;
            }
        }
    } else {

        $qUser = $ci3->db->select('*')
            ->from('tbl_user')
            ->where('id_user', $user_id)
            ->get();
        $resQkar = $qUser->row_array();
        if ($resQkar || !empty($resQkar)) {
            if ($resQkar['image_profile'] != null || $resQkar['image_profile'] != '') {
                $image_profile = $resQkar['image_profile'];
                $arrRessQHehe['image_profile'] = $image_profile;
            }
        }
    }

    return $arrRessQHehe;
}

function cekIsKaryawan($user_id)
{

    $ci3 = get_instance();

    $ci3->db->where("user_id", $user_id);
    $query = $ci3->db->get("tbl_karyawan_toko");
    return $query->num_rows();
}
