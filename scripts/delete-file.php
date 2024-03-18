<?php 
include '../includes/config.php';
$error = "";
if (isset($_POST['id'])) {
	$selectors = $_POST['id'];
	foreach ($selectors as $selector) {
		$selectFile = "SELECT * FROM repository WHERE file_id = :fileID";
		$stmt = $conn->prepare($selectFile);
		$stmt->execute([
			"fileID" => $selector
		]);
		$row = $stmt->fetch();
		$deleteFileName = $row["file_name"];

		$getDir = file_get_contents("../scripts/subfolder.php");

		if ($getDir == "repository") {
			unlink("../repository/$deleteFileName");
		} else {
			unlink("../repository/$getDir/$deleteFileName");
			$updateDelete = "UPDATE repository SET file_content = file_content-1 WHERE file_name = :parentFolder";
			$stmtDelete = $conn->prepare($updateDelete);
			$stmtDelete->execute([
			'parentFolder' => $getDir
			]);
		}
		

		$deleteFile = "DELETE FROM repository WHERE file_id = :fileID";
		$stmt1 = $conn->prepare($deleteFile);
		$stmt1->execute([
			"fileID" => $selector
		]);

		if ($stmt1) {
			$error = "no-error";
		}


	}

	
}

$result = ['error' => $error];
echo json_encode($result);
 ?>