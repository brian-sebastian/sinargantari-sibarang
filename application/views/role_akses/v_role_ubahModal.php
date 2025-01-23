<div class="row">
    <div class="col mb-3">
        <label for="type" class="form-label">Nama Role</label>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf-token" style="display: none">
        <input type="hidden" name="id_role" value="<?= $data_role['id_role']; ?>" id="id_role">
        <input type="text" id="role" name="role" value="<?= $data_role['role']; ?>" class="form-control" />
        <small class="text-danger" id="err_roleU"></small>
    </div>
</div>
