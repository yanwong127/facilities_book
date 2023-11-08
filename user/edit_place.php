<?php
include_once('db.php');

if(isset($_POST['edit']) && isset($_GET['id'])){
    if(!empty($_FILES['image']['name']))
    {   
        $ext = explode('.', $_FILES['image']['name']);
        $ext = strtolower(array_pop($ext));
        $file = 'img/'.date('YmdHis').'.'.$ext;
        if(($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png')){ 
            $target_path = $file;
        }else{
            $error_ext = 1;
        }
        if(file_exists($file)){
            $file_exists = 1;
        }
    }elseif(empty($_FILES['image']['name'])){
        
        $file = $_POST['old_img'];
    }
    if(isset($error_ext)){
        echo "<script>alert('Please upload .jpg, .jpeg or .png file only.')</script>"; 
    }elseif(isset($file_exists)){
        echo "<script>alert('Image already exists, please choose another image or change the image name.')</script>"; 
    }elseif(isset($target_path) && !move_uploaded_file($_FILES['image']['tmp_name'], $target_path)){
        echo "<script>alert('Image failed to upload image')</script>";  
    }else{
        $image=$file;
        $sku=$_POST['sku'];
        $quantity=$_POST['quantity'];
        $Price=$_POST['price'];
        
        $Query="UPDATE tb_item SET image='$image',SKU='$sku',Quantity='$quantity',Price='$Price' WHERE id = '".$_GET['id']."'";
        if($result=mysqli_query($conn,$Query)){     
            echo "<script>window.location.href = 'index.php';alert('Record Success to Edit');</script>";     
        }else{          
            echo "<script>alert('Record Fails to Edit')</script>";
        }
    }
}

?>

<?php
$qry = "SELECT * FROM tb_item WHERE id='".$_GET['id']."'";
$sql = mysqli_query($conn, $qry);
$row = mysqli_fetch_array($sql);
?>

<div class="container">
    <form class="form-horizontal" action="edit.php?id=<?=$_GET['id']?>" method="post" enctype="multipart/form-data">
        <div>
            <input type="hidden" name="old_img" value="<?=$row['image']?>">
            <label>Image:</label>
            <div>
                <img src="<?=$row['image']?>" width="123px" height="150px"><br>
                <input type="file" name="image" accept="image/*" onchange="loadFile(event)">
            </div>
        </div>
        <div>
            <label>SKU:</label>
            <div>
                <input type="text" name="sku" placeholder="SKU" value="<?=$row['sku']?>" required>
            </div>
        </div>
        <div>
            <label>Quantity:</label>
            <div>
                <input type="number" name="quantity" placeholder="Quantity" value="<?=$row['quantity']?>" required>
            </div>
        </div>
        <div>
            <label>Price (RM):</label>
            <div>
                <input type="text" name="price" placeholder="RM" value="<?=$row['price']?>" required>
            </div>
        </div>
        <br />
        <div>
            <div>
                <button type="submit" name="edit" value="edit">Submit</button>
                <button type="button" onclick="window.location.href='index.php'">Back</button>
            </div>
        </div>
    </form>
</div>