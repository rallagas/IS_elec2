<?php 
include_once "includes/db_conn.php"; 
include_once "includes/func.inc.php"; 
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Lecture : SQL Integration with PHP</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="font/bootstrap-icons.css">

    <!--
    <style>
        img.res {
            object-fit: cover;
            height: 400px;
            width: 300px;
        }

    </style>
-->
</head>

<body id="bg1">
    <div class="container-fluid my-5">
        <div class="row justify-content-between d-flex">
            <div class="col-lg-9 col-md-12 col-sm-12 px-5 border-end border-1">
                <h1 class="display-1  justify-content-between  fw-bold text-danger">Blunch Cafe</h1>
                <p class="text-secondary fw-bold fs-4">
                    Welcome! Dine and Chill!
                </p>

                <hr>

                <h1 class="display-3 fw-bold text-danger">Our Best Sellers</h1>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                            <div class="container-fluid">
                                <div class="row">
                                    <?php
                        $bestsellers = query($conn,
                                             "SELECT i.item_name
                                                   , i.item_img
                                                   , sum(c.item_qty) item_qty
                                                FROM `cart` c
                                                JOIN `items` i
                                                  ON (c.item_id = i.item_id)
                                               WHERE c.status in ('X')
                                                 AND i.cat_id < 9
                                                 and c.confirm = 'Y'
                                               GROUP BY i.item_name
                                                   , i.item_img
                                               ORDER BY sum(c.item_qty)  DESC
                                               LIMIT 10
                                               ;");
                        foreach($bestsellers as $bk => $bs){ ?>
                                    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                        <div class="card shadow">
                                            <span class="text-light text-bold fs-2 position-absolute top-50 start-50 z-index top">
                                                <?php echo $bs['item_qty'] . " " . pcpcs($bs['item_qty']) ;?> Sold
                                            </span>
                                            <img src="images/<?php echo $bs['item_img'];?>" alt="" class="res card-img-top img-responsive">

                                            <span class="card-title position-absolute top-0 start-50 text-white fw-bold bg-danger text-center p-1"><?php echo $bs['item_name'];?></span>


                                        </div>
                                    </div>

                                    <?php }
                        ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
             
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 order-1">
                <div class="">
                    <div class="card-header">
                        <h3 class="card-title text-danger">Sign In <span class="text-secondary"> to order!</span></h3>
                        <?php
                if(isset($_GET['signup'])){ ?>
                        <div class="alert alert-success">
                            <p class=""> <i class="bi bi-check"></i> Registration Complete.You may now login.</p>
                        </div>
                        <?php }
                if(isset($_GET['error'])){
                        if($_GET['error'] == 'cannotgothere'){
                        ?>
                        <div class="alert alert-danger">
                            <p class=""> <i class="bi bi-exclamation-triangle"></i> Cannot navigate that page unless you login.</p>
                        </div>

                        <?php }
                }
                
                
                ?>
                    </div>
                    <form action="includes/processlogin.php" method="post">
                        <div class="card-body">
                            <input name="p_username" type="text" class="form-control mb-3 border-danger" placeholder="username or email address">
                            <input name="p_password" type="password" class="form-control mb-3 border-danger" placeholder="password">
                        </div>
                        <div class="card-footer pb-n1 ">
                            <button class="btn btn-outline-danger text-dark">Login</button>
                            <span class="text-secondary fw-light"> Don't have an account? </span><a href="createaccount/" class="btn text-danger">Sign Up!</a>
                        </div>
                    </form>
                </div>



            </div>


        </div>
        <div class="footer">


        </div>
    </div>

</body>
<script src="js/bootstrap.min.js"></script>

</html>
