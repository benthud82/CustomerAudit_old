
<?php
include_once '../connection/connection_details.php';
session_write_close();


$shipaccsql = $conn1->prepare("SELECT 
                                                            COUNT(*) as skucount, SUM(ma_action) as added
                                                        FROM
                                                            slotting.massalgorithm_actions WHERE ma_algorithm = 'SHIPACC';");
$shipaccsql->execute();
$shipaccsqlarray = $shipaccsql->fetchAll(pdo::FETCH_ASSOC);
?>

<div class="col-xl-4 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
    <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $shipaccsqlarray[0]['skucount']; ?></div> 
    <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Total Actions</div>
</div>

<div class="col-xl-4 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
    <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php
        if (is_null($shipaccsqlarray[0]['added'])) {
            echo '0';
        } else {
            echo $shipaccsqlarray[0]['added'];
        }
        ?></div> 
    <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">Items Corrected</div>
</div>

<div class="col-xl-4 col-sm-4 col-xs-12 text-center" style="padding-bottom: 5px;">
    <div class="col-sm-12 h3" style="padding-bottom: 5px;"> <?php echo $shipaccsqlarray[0]['skucount'] - $shipaccsqlarray[0]['added']; ?></div> 
    <div class="col-sm-12 text-muted h5" style="padding-bottom: 10px;">No Action Needed</div>
</div>