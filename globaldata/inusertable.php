<?php


$userset = $conn1->prepare("SELECT customeraudit_users_ID from slotting.customeraudit_users WHERE customeraudit_users_ID = '$sessionuser'");
$userset->execute();
$usersetarray = $userset->fetchAll(pdo::FETCH_ASSOC);

