<!-- navbar-->
<nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container">
            <a href="index.php" class="navbar navbar-brand">Login&Registraiton </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="nav-link">Home</a>
                </li>
                
                  
                <?php
                    if(isset($_SESSION['Email'])|| isset($_COOKIE['email']))
                    {

                ?>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link">Loguot</a>
                    </li>
                <?php
                    }
                    else
                    {

                ?>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="register.php" class="nav-link">Register</a>
                    </li>    
                <?php
                    }
                ?>
            </ul>
        </div>
    </nav>