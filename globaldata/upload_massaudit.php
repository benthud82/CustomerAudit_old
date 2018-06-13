<?php

include '../sessioninclude.php';
include_once '../connection/connection_details.php';
$var_userid = strtoupper($_SESSION['MYUSER']);
$usersql = $conn1->prepare("SELECT  customeraudit_users_GROUP FROM slotting.customeraudit_users WHERE upper(customeraudit_users_ID) =  '$var_userid'");
$usersql->execute();
$userarray = $usersql->fetchAll(pdo::FETCH_ASSOC);
$usergroup = $userarray[0]['customeraudit_users_GROUP'];
$now = date("Y-m-d H:i:s");


$var_custtype = ($_POST['grouptype']);
$var_custid = ($_POST['groupid']);
$sheetkey = null;  //initialize as null for error checking
$sheetcolumns = array('SKU', 'STATUS');  //columns needed to insert into customeraction_asgntasks table
$data = array();
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

    if (is_null($sheetkey)) { //error handling for no sheet named item detail.
        echo 'There is not a worksheet named: Item Detail';
        exit;
    }

    list( $num_cols, $num_rows ) = $xlsx->dimension($sheetkey);

    //Find the key of each relevant column heading.  Need to put in error tracking so can tell if sheet is missing or column is missing.
    $headerrows = 0; //intialize rowcount to determine which row has column headings
    $colindex_SKU = $colindex_STATUS = null; //set the colindex for columns to null.  Once set, loop will be exited.
    foreach ($xlsx->rows($sheetkey) as $r) {
        $headerrows += 1;

        foreach ($r as $colkey => $colname) { //loop through columns on row and determine if SKU or STATUS
            if (is_null($colindex_SKU)) { //is SKU col already set?
                if (strtoupper($colname) == 'SKU') {
                    $colindex_SKU = $colkey;
                }
            }
            if (is_null($colindex_STATUS)) { //is STATUS col already set?
                if (strtoupper($colname) == 'STATUS') {
                    $colindex_STATUS = $colkey;
                }
            }
            if (!is_null($colindex_SKU) && !is_null($colindex_STATUS)) {
                break 2; //break out of loop once all key columns are determined
            }
        }
    }


    //Build data array from start row to last row using key columns and posted data
    foreach ($xlsx->rows($sheetkey) as $r) {
        //deduct one from header row

        if ($headerrows > 0) {
            $headerrows -= 1;
            continue;
        }
        //create data array
//        $comment = mysqli_real_escape_string($conn1, $r[$colindex_STATUS]);
        $data[] = "(0,'$var_userid', '$var_userid', '$now', '$usergroup', $r[$colindex_SKU], '$var_custtype', '$var_custid', '$r[$colindex_STATUS]', 'COMPLETE')";
    }

    //insert into customeraction_asgntasks table
    $values = implode(',', $data);
    $columns_assign = 'idcustomeraction_asgntasks, customeraction_asgntasks_ASGNTSM, customeraction_asgntasks_TOTSM, customeraction_asgntasks_DATE, customeraction_asgntasks_GROUP, customeraction_asgntasks_ITEM, customeraction_asgntasks_CUSTGROUP, customeraction_asgntask_GROUPID, customeraction_asgntasks_COMMENT, customeraction_asgntasks_STATUS ';
    if (!empty($values)) {
        $sql = "INSERT INTO slotting.customeraction_asgntasks ($columns_assign) VALUES $values";
        $query = $conn1->prepare($sql);
        $query->execute();
    }

    //update the auditcomplete with the just updatedassigned tasks table
    //will have to pull in fill rate info by customer id grouping
    
    
    
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
