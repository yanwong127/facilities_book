<?php
session_start();
error_reporting();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $item_overview = $_POST['item_overview'];
    $availability = $_POST['availability'];
    $item_id = intval($_GET['item_id']);

    $sql = "UPDATE item SET item_name = :item_name, item_overview = :item_overview, availability = :availability WHERE item_id = :item_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':item_name', $item_name, PDO::PARAM_STR);
    $stmt->bindParam(':item_overview', $item_overview, PDO::PARAM_STR);
    $stmt->bindParam(':availability', $availability, PDO::PARAM_STR);
    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $msg = "Data updated successfully";
    } else {
        $msg = "Error updating data";
    }

    // Handle image upload
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['item_image']['tmp_name'];
        $image_name = $_FILES['item_image']['name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_image_name = uniqid() . '.' . $image_extension;
        $image_path = 'img/image/' . $new_image_name;
        $image_path2 = '../user/img/' . $new_image_name;

        if (move_uploaded_file($image_tmp, $image_path )) {
            if (copy($image_path, $image_path2)){
            $sql = "UPDATE item SET item_img = :item_img WHERE item_id = :item_id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':item_img', $new_image_name, PDO::PARAM_STR);
            $query->bindParam(':item_id', $item_id, PDO::PARAM_INT);
            $query->execute();
            $msg .= " Image updated successfully.";
        } else {
            $msg .= " Error uploading image.";
        }
    }
}
}
}
// Retrieve item info
$item_id = intval($_GET['item_id']);
$sql = "SELECT * FROM item WHERE item_id = :item_id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
// Display HTML
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
    <title>College | Admin Edit Item Info</title>
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
        /* Custom Styles */
        .page-title {
            margin-bottom: 20px;
        }
        .panel-heading {
            background-color: #3e454c;
            color: #fff;
        }
        .panel-body {
            background-color: #f7f7f7;
        }
        .form-horizontal .control-label {
            text-align: right;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            width: 70%;
        }
        select.form-control {
            width: 70%;
        }
        .btn-primary {
            margin-top: 20px;
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
                        <h2 class="page-title">Edit Item</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Basic Info</div>
                                    <div class="panel-body">
                                        <?php if (!empty($msg)) { ?>
                                            <div class="succWrap">
                                                <strong>SUCCESS</strong>:
                                                <?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } ?>
                                        <?php if ($result) { ?>
                                            <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Item Name<span style="color:red">*</span></label>
                                                    <div class="col-sm-4">
                                                        <input type="text" name="item_name" class="form-control" value="<?php echo htmlentities($result['item_name']) ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Item Overview<span style="color:red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name="item_overview" rows="3" required><?php echo htmlentities($result['item_overview']) ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Availability<span style="color:red">*</span></label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control" name="availability" required>
                                                            <option value="Still Working" <?php if ($result['availability'] == "Still Working") echo "selected"; ?>>Still Working</option>
                                                            <option value="Not Working" <?php if ($result['availability'] == "Not Working") echo "selected"; ?>>Not Working</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Item Image</label>
                                                    <div class="col-sm-4">
                                                        <input type="file" name="item_image">
                                                        <img src="img/image/<?php echo htmlentities($result['item_img']); ?>" width="300" height="200" style="border:solid 1px #000">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-8 col-sm-offset-2">
                                                        <button class="btn btn-primary" name="submit" type="submit" style="margin-top:4%">Save changes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php } ?>
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