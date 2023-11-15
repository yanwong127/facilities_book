<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	if (isset($_POST['submit'])) {
		$place_name = $_POST['place_name'];
		$place_overview = $_POST['place_overview'];
		$availability = $_POST['availability'];
		$place_id = intval($_GET['place_id']);

		$sql = "UPDATE place SET place_name = :place_name, place_overview = :place_overview, availability = :availability WHERE place_id = :place_id";
		$query = $dbh->prepare($sql);
		$query->bindParam(':place_name', $place_name, PDO::PARAM_STR);
		$query->bindParam(':place_overview', $place_overview, PDO::PARAM_STR);
		$query->bindParam(':availability', $availability, PDO::PARAM_STR);
		$query->bindParam(':place_id', $place_id, PDO::PARAM_INT);
		$query->execute();

		$msg = "Data updated successfully";
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

    <title>College | Admin Edit Place Info</title>

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
                        <h2 class="page-title">Edit Place</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Basic Info</div>
                                    <div class="panel-body">
                                        <?php if ($msg) { ?>
                                            <div class="succWrap">
                                                <strong>SUCCESS</strong>:
                                                <?php echo htmlentities($msg); ?>
                                            </div>
                                        <?php } ?>
                                        <?php
                                        $place_id = intval($_GET['place_id']);
                                        $sql = "SELECT * FROM place WHERE place_id = :place_id";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':place_id', $place_id, PDO::PARAM_INT);
                                        $query->execute();
                                        $result = $query->fetch(PDO::FETCH_ASSOC);
                                        if ($result) {
                                        ?>
                                            <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Place Name<span style="color:red">*</span></label>
                                                    <div class="col-sm-4">
                                                        <input type="text" name="place_name" class="form-control" value="<?php echo htmlentities($result['place_name']) ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Place Overview<span style="color:red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" name="place_overview" rows="3" required><?php echo htmlentities($result['place_overview']) ?></textarea>
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

<?php ?>