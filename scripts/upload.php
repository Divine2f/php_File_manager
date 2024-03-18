<?php 
if (isset($_POST['addFile'])) {
	
	if (!isset($_GET['dir'])) {
		$files = isset($_FILES["uploads"]) ? count($_FILES["uploads"]["name"]) : 0;
	for ($totalFiles=0; $totalFiles < $files; $totalFiles++) { 

		$uploadFiles = $_FILES['uploads']['name'][$totalFiles];
	$uploadFilesTmp = $_FILES['uploads']['tmp_name'][$totalFiles];
	$uploadFilesType = $_FILES['uploads']['type'][$totalFiles];
	$uploadFilesSize = $_FILES['uploads']['size'][$totalFiles];

	//echo $uploadFilesType;

	if (empty($uploadFiles)) {
		echo '<script>alert("Please select a file to upload")</script>';
		echo '<script>window.open("index.php", "_SELF")</script>';
	} else {
		$uploadFilesDir = "repository";
		if (!file_exists($uploadFilesDir)) {
			mkdir($uploadFilesDir);
		}


	$insertFiles = "INSERT INTO `repository` (`file_name`, `file_type`, `file_size`, `file_directory`, `file_content`, `created_on`) VALUES(:uploadFiles, :uploadFilesType, :uploadFilesSize, :uploadFilesDir, :uploadFilesContent, :uploadFilesDate)";
		$stmt = $conn->prepare($insertFiles);

		$stmt->execute([
			'uploadFiles' => $uploadFiles,
			'uploadFilesType' => $uploadFilesType,
			'uploadFilesSize' => $uploadFilesSize,
			'uploadFilesDir' => $uploadFilesDir,
			'uploadFilesContent' => 0,
			'uploadFilesDate' => date("Y-m-d:h-I:s:A"),
		]);

		

		if (move_uploaded_file($uploadFilesTmp, "$uploadFilesDir/" . $uploadFiles)) {
			echo '<script>alert("Files uploaded successfully")</script>';
		echo '<script>window.open("index.php", "_SELF")</script>';
		}
	}
	} // 
	} else {

		$subFolder = $_GET['dir'];

		$files = isset($_FILES["uploads"]) ? count($_FILES["uploads"]["name"]) : 0;
	for ($totalFiles=0; $totalFiles < $files; $totalFiles++) { 

		$uploadFiles = $_FILES['uploads']['name'][$totalFiles];
	$uploadFilesTmp = $_FILES['uploads']['tmp_name'][$totalFiles];
	$uploadFilesType = $_FILES['uploads']['type'][$totalFiles];
	$uploadFilesSize = $_FILES['uploads']['size'][$totalFiles];

	//echo $uploadFilesType;

	if (empty($uploadFiles)) {
		echo '<script>alert("Please select a file to upload")</script>';
		echo '<script>window.open("index.php?dir='.$subFolder.'", "_SELF")</script>';
	} else {
		$uploadFilesDir = $subFolder;


	$insertFiles = "INSERT INTO `repository` (`file_name`, `file_type`, `file_size`, `file_directory`, `file_content`, `created_on`) VALUES(:uploadFiles, :uploadFilesType, :uploadFilesSize, :uploadFilesDir, :uploadFilesContent, :uploadFilesDate)";
		$stmt = $conn->prepare($insertFiles);

		$stmt->execute([
			'uploadFiles' => $uploadFiles,
			'uploadFilesType' => $uploadFilesType,
			'uploadFilesSize' => $uploadFilesSize,
			'uploadFilesDir' => $uploadFilesDir,
			'uploadFilesContent' => 0,
			'uploadFilesDate' => date("Y-m-d:h-I:s:A"),
		]);

		$updateFiles = "UPDATE repository SET file_content = file_content+1 WHERE file_name = :parentFolder";
		$stmtParent = $conn->prepare($updateFiles);

		$stmtParent->execute([
			'parentFolder' => $_GET['dir']
		]);
		

		if (move_uploaded_file($uploadFilesTmp, "repository/$uploadFilesDir/" . $uploadFiles)) {
			echo '<script>alert("Files uploaded successfully")</script>';
		echo '<script>window.open("index.php?dir='.$subFolder.'", "_SELF")</script>';
		}
	}
	}
	}
	
}
 ?>