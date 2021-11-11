<?php require_once('includes/header.php') ?>

    <!-- navbar-->
    <?php require_once('includes/nav.php') ?>


    <divc class="container">
        <div class="row">
            <div class="col">
                <div class="card bg-light mt-5 py-2">
                    <h3 class="text-center">
                        <?php
                            if(logged_in())
                            {
                                echo 'You have succesfully logged in';
                            }
                            else
                            {
                                redirect('login.php');
                            }
                        ?>    
                    </h3>
                </div>
            </div>
        </div>
    </div>
<?php require_once('includes/footer.php') ?>