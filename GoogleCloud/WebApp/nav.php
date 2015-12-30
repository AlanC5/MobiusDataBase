<?php
$resultName = $DB->prepare("SELECT name, imagefile FROM user WHERE userid = :userid");
$resultName->execute(array(':userid' => $userid));
$resultName->setFetchMode(PDO::FETCH_ASSOC);
$row = $resultName->fetch();
$name = $row["name"];
$imagefile = $row["imagefile"];

//grab archive list from database
$archiveList = $DB->prepare("SELECT archiveid, archivename, icon, description, private FROM archive WHERE userid = :userid");
$archiveList->execute(array(':userid' => $userid));

//Counts the number of rows that archives are equal, should be greater than 0
$count = $archiveList->rowCount();
$archive = array();

//Send Archives (needs to send URL as well)
if ($count > 0) {
	$archiveList->setFetchMode(PDO::FETCH_ASSOC);

	while ($row = $archiveList->fetch()) {
		$archiveidLink = $row["archiveid"];
		$archive[] = array("icon" => $row["icon"], "archiveid" => $archiveidLink,"archivename" => $row["archivename"], "private" => $row["private"]);
	}
}
?>
