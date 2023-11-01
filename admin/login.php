<?php 

include_once('db.php');

if(isset($_POST['submit'])){
    if(!empty($_POST['admin_name']) && !empty($_POST['admin_pass'])){
        $adminname = $_POST['admin_name'];
        $adminpass = md5($_POST['admin_pass']);
        $_SESSION['true']='true';
        $Query="SELECT * FROM `admin` WHERE admin_name='".$adminname."' AND admin_pass='".$adminpass."'";
        $result=mysqli_query($conn,$Query);
        $rows=mysqli_num_rows($result);
        $row=mysqli_fetch_array($result);
        if($rows==1){
          $_SESSION['admin_name']=$adminname;
          $_SESSION['admin_pass']=$adminpass;
          $_SESSION["id"]=$row[0];
          echo "<script>window.location.href ='index.php';
                //alert('Login Successfull.');</script>";
        }else{      
          echo "<script>alert('Login Fail ! Please try again');</script>";
        }
      }else{
        echo "<script>alert('Please Insert Username or Password');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    
    <form action="login.php" method='post'>
        <label for="admin_name">Admin Name:</label>
        <input type="text" name="admin_name" required>

        <label for="admin_name">Password:</label>
        <input type="password" name="admin_pass" required>

        <input type="submit" name="submit" value="login">
    </form>

</body>
</html>
