<?php require_once('includes/header.php') ?>
    <!-- navbar-->
    <?php require_once('includes/nav.php') ?>


    <divc class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card bg-light mt-5 py-2">
                    <div class="card-title">
                        <?php display_message(); ?>
                            
                        <?php login_validation(); ?>
                        <h2 class="text-center mt-2">Login Form</h2>
                        <hr>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="email" name="Uemail" placeholder="E-Mail" class="form-control py-2 mb-2">
                            <input type="password" name="Upass" placeholder="Password" class="form-control py-2 mb-2">
                            <button class="btn btn-dark float-end">Login</button>
                        
                    </div>
                    <div class="card-footer">
                        <input type="checkbox" name="remember"><span>Remember me</span>
                        <a href="recover.php" class="float-end">Forget password</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('includes/footer.php') ?>