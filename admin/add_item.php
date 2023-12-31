<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['submit'])) {
        $item_name = $_POST['item_name'];
        $item_overview = $_POST['item_overview'];
        $availability = $_POST['availability'];
        $item_img = $_FILES["item_img"]["name"];
        $temp_img = $_FILES["item_img"]["tmp_name"];
        $quantity = $_POST['quantity']; // New field

        $extension = pathinfo($item_img, PATHINFO_EXTENSION);
        $destination1 = 'img/' . $item_img;  // First folder
        $destination2 = '../user/img/' . $item_img;  // Second folder

        if (!in_array($extension, ['jpg', 'png', 'jpeg'])) {
            echo "Your file extension must be .jpg, .png, or .jpeg";
        } elseif ($_FILES['item_img']['size'] > 100000000) {
            echo "File too large!";
        } else {
            if (move_uploaded_file($temp_img, $destination1)) {
                // Move to the second folder
                if (file_exists($destination1)) {
                    if (copy($destination1, $destination2)) {
                        $sql = "INSERT INTO item(item_name, item_overview, item_img, availability, quantity) VALUES (:item_name, :item_overview, :item_img, :availability, :quantity)";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':item_name', $item_name, PDO::PARAM_STR);
                        $query->bindParam(':item_overview', $item_overview, PDO::PARAM_STR);
                        $query->bindParam(':item_img', $item_img, PDO::PARAM_STR);
                        $query->bindParam(':availability', $availability, PDO::PARAM_STR);
                        $query->bindParam(':quantity', $quantity, PDO::PARAM_INT); // Bind quantity

                        if ($query->execute()) {
                            $msg = "Facilities posted successfully";
                        } else {
                            $error = "Something went wrong. Please try again";
                        }
                    } else {
                        echo "Failed to copy file to the second folder.";
                    }
                } else {
                    echo "Source file does not exist in the first folder.";
                }
            } else {
                echo "Failed to upload file.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>College | Admin Post Item</title>

    <!-- Font awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Sandstone Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap Datatables -->
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <!-- Bootstrap social button library -->
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <!-- Bootstrap select -->
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <!-- Bootstrap file input -->
    <link rel="stylesheet" href="css/fileinput.min.css">
    <!-- Awesome Bootstrap checkbox -->
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <!-- Admin Stye -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }

        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/leftbar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">

                        <h2 class="page-title">Post An Item</h2>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Basic Info</div>
                                    <?php if ($error) { ?>
                                        <div class="alert alert-danger"><strong>Error: </strong>
                                            <?php echo htmlentities($error); ?>
                                        </div>
                                    <?php } else if ($msg) { ?>
                                        <div class="alert alert-success"><strong>Success: </strong>
                                            <?php echo htmlentities($msg); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item Name<span
                                                        style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="item_name" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Item Overview<span
                                                        style="color:red">*</span></label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" name="item_overview" rows="3"
                                                        required></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Availability<span
                                                        style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <select class="form-control" name="availability" required>
                                                        <option value="">Select</option>
                                                        <option value="Still Working">Still Working</option>
                                                        <option value="Not Working">Not Working</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Quantity<span
                                                        style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="number" name="quantity" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                    <h4><b>Upload Images</b></h4>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Image 1<span
                                                        style="color:red">*</span></label>
                                                <div class="col-sm-4">
                                                    <input type="file" name="item_img" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <button class="btn btn-default" type="reset">Cancel</button>
                                                    <button class="btn btn-primary" name="submit" type="submit">Save
                                                        changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
</body>

</html>