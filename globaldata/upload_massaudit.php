<?php

include 'sessioninclude.php';
include_once 'connection/connection_details.php';

$var_userid = $_SESSION['MYUSER'];
$var_custid = ($_POST['upload_custid']);
$sheetid = null;

require_once '../../simplexlsx.class.php';


//hardcoded for example.  Will need to do the parsing by dynamically loading the file based on POST from auditupload.php
if ($xlsx = SimpleXLSX::parse('../uploads_massaudit/UHS BEHAVORIAL HEALTH (UHB15) Q1 2018.xlsx')) {
    //what are the available sheets
    $sheetsarray = $xlsx->sheetNames();
    
    //loop through sheets to find index value of "item detail" sheet
    foreach ($sheetsarray as $key => $sheetname) {
        if (strtoupper($sheetname) == 'ITEM DETAIL') {
            $sheetkey = $key;
            break;
        }
    }
    
    //Find the key of each relevant column heading.  Need to put in error tracking so can tell if sheet is missing or column is missing.
    
} else {
    echo SimpleXLSX::parse_error();
}





$today = date('Y-m-d');
$target_dir = "uploads_massaudit/";
$target_basename = basename($_FILES["file"]["name"]);
$target_file = $target_dir . $target_basename;
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

$columns = 'upload_custid, upload_custtype, upload_filename, upload_filetype, upload_date, upload_tsm';


$sql = "INSERT INTO slotting.custaudit_uploads ($columns) VALUES ('$var_custid', '$var_custtype', '$target_basename', '$imageFileType', '$today', '$var_userid');";
$query = $conn1->prepare($sql);
$query->execute();
