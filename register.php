<?php require_once('includes/header.php') ?>
    <!-- navbar-->
    <?php require_once('includes/nav.php') ?>


    <divc class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="card bg-light mt-5 py-2">
                    
                    <div class="card-title">
                        <h2 class="text-center mt-2">Registration Form</h2>
                        <hr>
                    </div>
                    <div class="card-body">
                        <?php user_validation(); ?>
                        <form method="post">
                            <input type="text" name="UserName" placeholder="Username" class="form-control py-2 mb-2" required>
                            <input type="text" name="FirstName" placeholder="First Name" class="form-control py-2 mb-2" required>
                            <input type="text" name="LastName" placeholder="Last Name" class="form-control py-2 mb-2" required>
                            <input type="email" name="Email" placeholder="E-Mail" class="form-control py-2 mb-2" required>
                            <input type="password" name="pass" placeholder="Password" class="form-control py-2 mb-2" required>
                            <input type="password" name="cpass" placeholder="Confirm password" class="form-control py-2 mb-2" required>
                            <button class="btn btn-success float-end"> Register </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php require_once('includes/footer.php') ?>