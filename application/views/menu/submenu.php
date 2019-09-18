<div class="col">


    <?= $this->session->flashdata('message'); ?>
    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addMenuModal">Add Submenu</button>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Menu</th>
                <th scope="col">URL</th>
                <th scope="col">Icon</th>
                <th scope="col">Active</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $n = 1; ?>
            <?php foreach ($submenu as $sm) : ?>
                <tr>
                    <th scope="col"><?= $n; ?></th>
                    <td><?= $sm['title']; ?></td>
                    <td><?= $sm['menu']; ?></td>
                    <td><?= $sm['url']; ?></td>
                    <td><?= $sm['icon']; ?></td>
                    <td><?= $sm['is_active']; ?></td>
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
                <h5 class="modal-title" id="addMenuModalLabel">Add new submenu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('menu/addSubmenu'); ?>" method="post">
                <div class="modal-body">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Title..">
                    </div>
                    <div class="input-group mb-2">
                        <select class="custom-select" id="menu_id" name="menu_id">
                            <option selected>Select menu parent</option>
                            <?php foreach ($menu as $m) : ?>
                                <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="url" name="url" placeholder="Url..">
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon..">
                    </div>
                    <div class="input-group mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active">
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
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