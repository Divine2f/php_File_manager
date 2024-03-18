<?php 
include("../includes/config.php");
$error = "";
$msg ="";

if (isset($_POST)) {
$newFile = $_POST['fileFolder'];
$fileCat = $_POST['fileCat'];



if ($fileCat == "Create New Folder") {
		$getDir = file_get_contents("../scripts/subfolder.php");
		if ($getDir == "repository") {
			$uploadFileDir = "repository";
			mkdir("../$uploadFileDir/$newFile");
		} else {
			$uploadFileDir = $getDir;
			mkdir("../repository/$uploadFileDir/$newFile");
			$updateCreate = "UPDATE repository SET file_content = file_content+1 WHERE file_name = :parentFolder";
			$stmtCreate = $conn->prepare($updateCreate);
			$stmtCreate->execute([
			'parentFolder' => $getDir
			]);
		}


	$insertFile = "INSERT INTO `repository` (`file_name`, `file_type`, `file_size`, `file_directory`, `file_content`, `created_on`) VALUES(:uploadFile, :uploadFileType, :uploadFileSize, :uploadFileDir, :uploadFileContent, :uploadFileDate)";
		$stmt = $conn->prepare($insertFile);

		$stmt->execute([
			'uploadFile' => $newFile,
			'uploadFileType' => "folder",
			'uploadFileSize' => 0,
			'uploadFileDir' => $uploadFileDir,
			'uploadFileContent' => 0,
			'uploadFileDate' => date("Y-m-d:h-I:s:A")
		]);

		if ($stmt) {
			$error = "";
			$msg ="Folder created successfully";
		}


} else if ($fileCat == "Create New File") {
		$getDir = file_get_contents("../scripts/subfolder.php");
		if ($getDir == "repository") {
			$uploadFileDir = "repository";
			$createFile = fopen("../repository/".$newFile, "w");
		} else {
			$uploadFileDir = $getDir;
			$createFile = fopen("../repository/".$uploadFileDir."/".$newFile, "w");
			$updateCreate = "UPDATE repository SET file_content = file_content+1 WHERE file_name = :parentFolder";
			$stmtCreate = $conn->prepare($updateCreate);
			$stmtCreate->execute([
			'parentFolder' => $getDir
			]);
		}


		
		$newFileType = explode('.', $newFile)[1];

		$insertFile = "INSERT INTO `repository` (`file_name`, `file_type`, `file_size`, `file_directory`, `file_content`, `created_on`) VALUES(:uploadFile, :uploadFileType, :uploadFileSize, :uploadFileDir, :uploadFileContent, :uploadFileDate)";
		$stmt = $conn->prepare($insertFile);

		$stmt->execute([
			'uploadFile' => $newFile,
			'uploadFileType' => $newFileType,
			'uploadFileSize' => 0,
			'uploadFileDir' => $uploadFileDir,
			'uploadFileContent' => 0,
			'uploadFileDate' => date("Y-m-d:h-I:s:A")
		]);


		if ($stmt) {
			$error = "";
			$msg ="File created successfully";
		}


}
}

$result = ['error' => $error, 'msg' => $msg];
echo json_encode($result);
 ?>