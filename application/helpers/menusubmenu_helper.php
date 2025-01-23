<?php
function getSubmenuByMenuId($menu_id)
{
    $ci3 = get_instance();
    $ci3->db->where('is_active', 1);
    $ci3->db->where('menu_id', $menu_id);
    $query = $ci3->db->get('tbl_submenu');
    return $query->result_array();
}
