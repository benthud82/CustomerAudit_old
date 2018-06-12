
<?php
ini_set('max_execution_time', 99999);

include_once '../connection/connection_details.php';

//$var_userid = $_POST['userid'];
$var_userid = strtoupper($_POST['userid']);

//find group
$mygroup = $conn1->prepare("SELECT customeraudit_users_GROUP
                            FROM slotting.customeraudit_users
                            WHERE UPPER(customeraudit_users_ID = '$var_userid')");
$mygroup->execute();
$mygrouparray = $mygroup->fetchAll(pdo::FETCH_ASSOC);
$mygroupdata = $mygrouparray[0]['customeraudit_users_GROUP'];

$var_clickedid = $_POST['clickedid'];

switch ($var_clickedid) {
    case 'help_sp_me':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_salesplan ON SALESPLAN = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'SALESPLAN'
                                and UPPER(auditcomplete_user) = '$var_userid'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    case 'help_bt_me':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_billto ON BILLTONUM = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'BILLTO'
                                and UPPER(auditcomplete_user) = '$var_userid'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    case 'help_st_me':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_shipto ON SHIPTONUM = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'SHIPTO'
                                and UPPER(auditcomplete_user) = '$var_userid'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    case 'help_sp_group':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_salesplan ON SALESPLAN = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'SALESPLAN'
                                and UPPER(auditcomplete_USERGROUP) =  '$mygroupdata'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    case 'help_bt_group':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_billto ON BILLTONUM = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'BILLTO'
                                and UPPER(auditcomplete_USERGROUP) =  '$mygroupdata'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    case 'help_st_group':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_shipto ON SHIPTONUM = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'SHIPTO'
                                and UPPER(auditcomplete_USERGROUP) =  '$mygroupdata'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    case 'help_sp_all':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_salesplan ON SALESPLAN = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'SALESPLAN'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    case 'help_bt_all':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_billto ON BILLTONUM = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'BILLTO'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    case 'help_st_all':
        $var_sqlfilter = "SELECT 
                            UPPER(auditcomplete_user),
                            auditcomplete_date,
                            UPPER(auditcomplete_custid),
                            auditcomplete_SCOREMNT,
                            cast(SCOREMONTH_EXCLDS * 100 as UNSIGNED) as CURSCORE,
                            ((SCOREMONTH_EXCLDS * 100) - auditcomplete_SCOREMNT) as SCOREDIFF
                        FROM
                            slotting.auditcomplete
                                LEFT JOIN
                            slotting.scorecard_display_shipto ON SHIPTONUM = UPPER(auditcomplete_custid)
                        WHERE
                            auditcomplete_custtype = 'SHIPTO'
                                and auditcomplete_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                and SCOREMONTH_EXCLDS is not NULL;";
        break;
    default:
        break;
}


$imapact = $conn1->prepare("$var_sqlfilter");
$imapact->execute();
$imapactarray = $imapact->fetchAll(pdo::FETCH_ASSOC);
?>


<!--start of div table-->
<div class="" id="divtablecontainer">
    <div  class='col-sm-12 col-md-12 col-lg-12 print-1wide'  style="float: none;">

        <div class='widget-content widget-table'  style="position: relative;">
            <div class='divtable'>
                <div class='divtableheader'>
                    <div class='divtabletitle width16_66' style="cursor: default">TSM</div>
                    <div class='divtabletitle width16_66' style="cursor: default">Audit Date</div>
                    <div class='divtabletitle width16_66' style="cursor: default">Customer ID</div>
                    <div class='divtabletitle width16_66' style="cursor: default">Score at Audit</div>
                    <div class='divtabletitle width16_66' style="cursor: default">Score Current</div>
                    <div class='divtabletitle width16_66' style="cursor: default">Score Diff.</div>
                </div>
                <?php foreach ($imapactarray as $key => $value) { ?>
                    <div class='divtablerow itemdetailexpand'>
                        <div class='divtabledata width16_66'> <?php echo $imapactarray[$key]['UPPER(auditcomplete_user)']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo $imapactarray[$key]['auditcomplete_date']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo strtoupper($imapactarray[$key]['UPPER(auditcomplete_custid)']); ?> </div>
                        <div class='divtabledata width16_66'> <?php echo $imapactarray[$key]['auditcomplete_SCOREMNT']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo $imapactarray[$key]['CURSCORE']; ?> </div>
                        <div class='divtabledata width16_66'> <?php echo intval($imapactarray[$key]['SCOREDIFF']); ?> </div>
                    </div>

                <?php } ?>
            </div>
        </div>

    </div>    
</div>    

