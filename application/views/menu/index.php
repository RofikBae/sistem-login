<div class="col-lg-4">
    <?= $this->session->flashdata('message'); ?>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addMenuModal">Add Menu</button>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Menu</th>
                <th scope="col md-1">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $n = 1; ?>
            <?php foreach ($menu as $m) : ?>
                <tr>
                    <th scope="col"><?= $n; ?></th>
                    <td><?= $m['menu']; ?></td>
                    <td>
                        <a href=""> <span class="badge badge-warning">Edit</span></a>
                        <a href=""> <span class="badge badge-danger">Delete</span></a>
                    </td>
                </tr>
                <?php $n++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMenuModalLabel">Add new menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('menu/addMenu'); ?>" method="post">
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu name..">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>









</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->