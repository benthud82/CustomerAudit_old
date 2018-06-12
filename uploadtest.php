<!DOCTYPE html>
<html>
    <?php
    include 'sessioninclude.php';
    ?>

    <body>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
            <input type="text" name="custid" id="custid" class="form-control" value="<?php echo 'THR13' ?>"/>  
            <input type="text" name="custtype" id="custtype" class="form-control" value="<?php echo 'SALESPLAN' ?>"/>  
        </form>

    </body>


</html>