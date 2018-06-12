<?php

ini_set('max_execution_time', 99999);

if (!function_exists('array_column')) {

    function array_column(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if (!isset($value[$columnKey])) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!isset($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }

}

include_once '../connection/connection_details.php';
//include '../../globalincludes/usa_asys_session.php';
session_write_close();
include_once '../functions/customer_audit_functions.php';

if (isset($_GET['salesplan'])) {
    $var_salesplan = ($_GET['salesplan']);
    $salesplanfilter = " and S.SALESPLAN = '$var_salesplan' ";
    $itemcodefilter = ' ';
} else if (isset($_GET['billto'])) {
    $var_salesplan = ($_GET['billto']);
    $salesplanfilter = " and R.BILLTONUM = '$var_salesplan' ";
} else if (isset($_GET['shipto'])) {
    $var_salesplan = ($_GET['shipto']);
    $salesplanfilter = " and R.SHIPTONUM = '$var_salesplan' ";
} elseif (isset($_GET['customid'])) {
    $var_salesplan = ($_GET['customid']);


    $sql_cfr_incl = "SELECT 
                                    group_SHIPTO
                                FROM
                                    slotting.scorecard_groupingdetail
                                WHERE
                                    group_MASTERID = $var_salesplan;";
    $query_cfr_incl = $conn1->prepare($sql_cfr_incl);
    $query_cfr_incl->execute();
    $array_cfr_incl = $query_cfr_incl->fetchAll(pdo::FETCH_COLUMN);
    $array_cfr_incl_data = array();
    $counter = 0;
    $imp_cfr_include = "(" . implode(",", $array_cfr_incl) . ")";


    $salesplanfilter = " and R.SHIPTONUM in $imp_cfr_include ";
}







if (isset($_GET['itemcode'])) {
    $var_itemcode = ($_GET['itemcode']);
    $itemcodefilter = " and ITEMCODE = $var_itemcode ";
    $salesplanfilter = ' ';
} else {
    $itemcodefilter = ' ';
}

if (isset($_GET['startdate'])) {
    $startdate = $_GET['startdate'];
} else {
    $startdate = date('Y-m-d', strtotime('-90 days'));
}
if (isset($_GET['enddate'])) {
    $enddate = $_GET['enddate'];
} else {
    $enddate = date('Y-m-d');
}

$datediff = abs(strtotime($startdate) - strtotime($enddate));
$days = intval(floor($datediff / (60 * 60 * 24)));





$custreturnsdata = $conn1->prepare("SELECT 
                                        ' ',  
                                        ITEMCODE,
                                        sum(case
                                            when R.RETURNCODE = 'WISP' then 1
                                            else 0
                                        end) as WISP,
                                        sum(case
                                            when R.RETURNCODE = 'WQSP' then 1
                                            else 0
                                        end) as WQSP,
                                        sum(case
                                            when R.RETURNCODE = 'IBNS' then 1
                                            else 0
                                        end) as IBNS,
                                        sum(case
                                            when R.RETURNCODE = 'CRID' then 1
                                            else 0
                                        end) as CRID,
                                        sum(case
                                            when R.RETURNCODE = 'TDNR' then 1
                                            else 0
                                        end) as TDNR,
                                        sum(case
                                            when R.RETURNCODE = 'EXPR' then 1
                                            else 0
                                        end) as EXPR,
                                        sum(case
                                            when R.RETURNCODE = 'SDAT' then 1
                                            else 0
                                        end) as SDAT,
                                        sum(case
                                            when R.RETURNCODE = 'TEMP' then 1
                                            else 0
                                        end) as TEMP,
                                        sum(case
                                            when R.RETURNCODE = 'LITR' then 1
                                            else 0
                                        end) as LITR,
                                        sum(case
                                            when R.RETURNCODE = 'WIOD' then 1
                                            else 0
                                        end) as WIOD,
                                        sum(case
                                            when R.RETURNCODE = 'IBNO' then 1
                                            else 0
                                        end) as IBNO,
                                        sum(case
                                            when R.RETURNCODE = 'CNCL' then 1
                                            else 0
                                        end) as CNCL,
                                        sum(case
                                            when R.RETURNCODE = 'NRSP' then 1
                                            else 0
                                        end) as NRSP,
                                        sum(case
                                            when R.RETURNCODE = 'WQTY' then 1
                                            else 0
                                        end) as WQTY,
                                        sum(case
                                            when R.RETURNCODE = 'TPRX' then 1
                                            else 0
                                        end) as TPRX,
                                    count(*) as TOTALRETURNS,
                                    ' ' as RECENTAUDIT
                                    FROM
                                        slotting.custreturns R
                                            JOIN
                                        slotting.salesplan S ON R.BILLTONUM = S.BILLTO
                                            and R.SHIPTONUM = S.SHIPTO
                                            JOIN
                                        slotting.custreturnmetrics M ON R.RETURNCODE = M.RETURNCODE
                                    WHERE 
                                      ORD_RETURNDATE BETWEEN DATE_SUB(NOW(), INTERVAL $days DAY) AND NOW() $salesplanfilter $itemcodefilter
                                    GROUP BY ITEMCODE
                                    ORDER BY count(*) desc");
$custreturnsdata->execute();
$custreturnsarray = $custreturnsdata->fetchAll(pdo::FETCH_ASSOC);


//Deterime if item has been recently audited
//Pull all open and closed audits for past 30 days
$recentaudit = $conn1->prepare("SELECT 
                                    *
                                FROM
                                    slotting.customeraction_asgntasks
                                        LEFT JOIN
                                    slotting.customeraction_comptasks ON customeraction_comptasks_ASGNTSM = customeraction_asgntasks_ASGNTSM
                                        and customeraction_asgntasks_DATE = customeraction_comptasks_ASGNDATE
                                WHERE
                                    customeraction_asgntasks_DATE >= DATE_SUB(NOW(), INTERVAL 30 DAY) ;");
$recentaudit->execute();
$recentauditarray = $recentaudit->fetchAll(pdo::FETCH_ASSOC);


$output = array(
    "aaData" => array()
);
$row = array();

foreach ($custreturnsarray as $key => $value) {
    $perfect_match_key = array_search(intval($custreturnsarray[$key]['ITEMCODE']), array_column($recentauditarray, 'customeraction_asgntasks_ITEM'));
    if ($perfect_match_key !== FALSE) {
        $custreturnsarray[$key]['RECENTAUDIT'] = date('m/d/Y', strtotime($recentauditarray[$perfect_match_key]['customeraction_asgntasks_DATE']));
    }
    $row[] = array_values($custreturnsarray[$key]);
}


$output['aaData'] = $row;
echo json_encode($output);
