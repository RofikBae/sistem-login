<div class="col-lg-4">
    <?= $this->session->flashdata('message'); ?>
    <h5> <?= $role['role']; ?> </h5>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Menu</th>
                <th scope="col md-1">Access</th>
            </tr>
        </thead>
        <tbody>
            <?php $n = 1; ?>
            <?php foreach ($menu as $m) : ?>
                <tr>
                    <th scope="col"><?= $n; ?></th>
                    <td><?= $m['menu']; ?></td>
                    <td>
                        <div class="form-check ">
                            <input class="form-check-input" type="checkbox" <?= check_access($role['id'], $m['id']); ?> data-role="<?= $role['id']; ?>" data-menu="<?= $m['id']; ?>">
                        </div>
                    </td>
                </tr>
                <?php $n++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
<!-- Button trigger modal -->










</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->