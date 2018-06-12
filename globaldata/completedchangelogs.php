<?php
ini_set('max_execution_time', 99999);

$completedlogs = $conn1->prepare("SELECT * FROM slotting.changelog_custaudit WHERE changelog_completedate IS NOT NULL order by changelog_completedate desc");
$completedlogs->execute();
$completedlogsarray = $completedlogs->fetchAll(pdo::FETCH_ASSOC);
