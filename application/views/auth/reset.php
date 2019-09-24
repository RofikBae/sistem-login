<body class="bg-gradient-primary">

    <div class="container jus">
        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="col-lg-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Reset password</h1>
                                </div>
                                <form class="user" action="<?= base_url('auth/resetpassword'); ?>" method="post">
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                                        <?= form_error('password', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="repeatPassword" name="repeatPassword" placeholder="Repeat password">
                                        <?= form_error('repeatPassword', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>

                                    <button type="submit" href="<?= base_url('auth/resetpassword') ?>" class="btn btn-primary btn-user btn-block">
                                        Reset Password
                                    </button>
                                </form>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>