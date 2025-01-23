<h5 class="modal-title" id="role_akses_modal">Akses Role <?= $role['role'] ?></h5>
<form action="" method="post" id="form_menusubmenu">
    <div class="table-responsive">
        <table class="table table-bordered table-nowrap mb-0">
            <thead>
                <tr>
                    <th scope="col" class="text-center">Menu</th>
                    <th scope="col" class="text-center">Sub-Menu</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menu as $key => $valueMenu) : ?>
                    <?php
                    $menuId = $valueMenu['id_menu'];
                    $isChecked = false;
                    ?>
                    <?php foreach ($access_menu_active as $menu_active) : ?>
                        <?php
                        if ($menu_active['menu_id'] == $menuId) {
                            $isChecked = true;
                            break;
                        }
                        ?>

                    <?php endforeach ?>

                    <tr>
                        <td class="text-nowrap" scope="row">

                            <div class="form-check">
                                <input class="form-check-input" name="menu[]" id="menu" type="checkbox" value="<?= $valueMenu['id_menu']; ?>" <?= $isChecked ? 'checked' : '' ?>>
                                <label class="form-check-label" for="menu">
                                    <?= $valueMenu['menu']; ?>
                                </label>
                            </div>
                        </td>


                        <td>
                            <?php
                            $getSubmenu = getSubmenuByMenuId($valueMenu['id_menu']);
                            ?>
                            <?php foreach ($getSubmenu as $key => $valueSubMenu) : ?>
                                <?php
                                $subMenuId = $valueSubMenu['id_submenu'];
                                $isCheckedSubMenu = false;
                                ?>
                                <?php foreach ($access_submenu_active as $submenu_active) : ?>
                                    <?php
                                    if ($submenu_active['submenu_id'] == $subMenuId) {
                                        $isCheckedSubMenu = true;
                                        break;
                                    }
                                    ?>
                                <?php endforeach ?>
                                <div class="form-check">
                                    <input class="form-check-input" name="submenu[]" id="submenu" type="checkbox" data-menu-id=<?= $valueSubMenu['menu_id'] ?> value="<?= $valueSubMenu['id_submenu']; ?>" <?= $isCheckedSubMenu ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="submenu">
                                        <?= $valueSubMenu['title']; ?>
                                    </label>
                                </div>


                            <?php endforeach ?>
                        </td>
                    </tr>

                <?php endforeach ?>
            </tbody>
        </table>
        <input type="hidden" readonly class="form-control" id="role_id" name="role_id" value="<?= $role['id_role'] ?>">
        <input type="hidden" readonly class="form-control" id="role" name="role" value="<?= $role['role'] ?>">
        <button type="button" id="save_access" class="btn btn-md btn-primary mt-2">Simpan</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('.menu').on('click', function() {
            const menu_id = $(this).data('menu');
            const id_role = $(this).data('role');

            $.ajax({
                url: "<?= base_url('role_akses/akses_menu'); ?>",
                type: 'post',
                data: {
                    id_menu: menu_id,
                    id_role: id_role
                },
                success: function(data) {
                    const obj = JSON.parse(data)
                    window.location.reload()
                }
            });
        });


        $('.submenu').on('click', function() {
            const id_role = $(this).data('id_role');
            const id_submenu = $(this).data('id_submenu');
            $.ajax({
                url: "<?= base_url('role_akses/akses_submenu'); ?>",
                type: 'post',
                data: {
                    id_role: id_role,
                    id_submenu: id_submenu
                },
                success: function(data) {
                    const obj = JSON.parse(data)
                    window.location.reload()
                }

            });
        });


        $('body').on('click', '#save_access', function() {

            let BASE_URL = "<?= base_url(); ?>";
            $.ajax({
                type: "POST",
                url: BASE_URL + "role_akses/saveAccess",
                data: $('#form_menusubmenu').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.err_code == 0) {
                        $('#aksesrole_Modal').modal('hide')
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Data Berhasil di Aktifkan',
                            icon: 'success',
                            timer: 1500,
                        }).then(function() {
                            window.location.reload();
                        });
                    } else {
                        $('#aksesrole_Modal').modal('hide')
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Data Gagal di Aktifkan',
                            timer: 1500,
                        }).then(function() {
                            window.location.reload();
                        });
                    }
                }
            });
        });
    })
</script>