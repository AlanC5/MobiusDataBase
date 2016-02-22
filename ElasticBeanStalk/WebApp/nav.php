<?php
$resultName = $DB->prepare("SELECT name, imagefile FROM user WHERE userId = :userId");
$resultName->execute(array(':userId' => $userId));
$resultName->setFetchMode(PDO::FETCH_ASSOC);
$row = $resultName->fetch();
$name = $row["name"];
$imagefile = $row["imagefile"];

//grab archive list from database
$archiveList = $DB->prepare("SELECT archiveId, archiveName, icon, description, private FROM archive WHERE userId = :userId");
$archiveList->execute(array(':userId' => $userId));

//Counts the number of rows that archives are equal, should be greater than 0
$count = $archiveList->rowCount();
$archive = array();

//Send Archives (needs to send URL as well)
if ($count > 0) {
	$archiveList->setFetchMode(PDO::FETCH_ASSOC);

	while ($row = $archiveList->fetch()) {
		$archiveidLink = $row["archiveId"];
		$archive[] = array("icon" => $row["icon"], "archiveid" => $archiveidLink,"archivename" => $row["archiveName"], "private" => $row["private"]);
	}
}
?>
