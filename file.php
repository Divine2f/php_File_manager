<?php
include("includes/config.php");
include("scripts/upload.php");

if (!isset($_GET['dir'])) {
  $subFolder = "ds-none";
} else {
  $subFolder = "";
}

$getFiles = "SELECT * FROM repository";
$stmt = $conn->query($getFiles);
$countFiles = $stmt->rowCount();
//echo $countFiles;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SWIFT FILE MANAGER</title> 
    <link rel="stylesheet" href="static/css/swift-ui.min.css" />
    <link rel="stylesheet" href="static/css/style.css" />
    <link rel="stylesheet" href="static/css/font-awesome.min.css" />
    <link rel="stylesheet" href="static/icons/myicons.css" />
  </head>
  <body>

  <div class="sw-container">
    <div class="page-box sw-wd-80 sw-mlr-auto sw-mt-5 sw-rsq-2 sw-bxsh bg-white sw-pd-4">
      <h2>SWIFT FILE MANAGER</h2>
      <!-- Upload Buttons -->
      <div class="upload-btns sw-mt-5 sw-grid-row">
        <div class="sw-grid-half">
         <form method="POST" enctype="multipart/form-data">
          <input type="file" class="sw-btn bg-dodgerblue" id="add-file" name="uploads[]" multiple="multiple" />
          <button type="submit" class="sw-btn sw-rsq-2 bg-coral st-white" name="addFile">ADD FILES</button>
         </form>
        </div>
        <div class="sw-grid-half">
          <form class="createFile" method="POST">
            <div class="sw-input-button-append">
            <input type="text" class="file-folder sw-input" placeholder="Enter file or folder name" name="file-folder" />
            <button class="create sw-button-append addon-border bg-dodgerblue br-dodgerblue st-white">Create New Folder</button>
           
        <div class="sw-dropdown-click">
        <a href="javascript:void(0)" class="picker sw-btn sw-dropdown-toggler-y bg-dodgerblue st-white"></a>
        <div class="sw-dropdown-content pick sw-mt-4">
        <a href="javascript:void(0)" class="sw-dropdown-list">Create New Folder</a>
        <a href="javascript:void(0)" class="sw-dropdown-list">Create New File</a>
        </div>
        </div>
            
            

          </div>
          </form>
          
        </div>
      </div>

      <!--/ Upload Buttons -->

      <!-- BC & Search Filter -->
      <div class="file-meta sw-mt-2 sw-grid-row">
        <div class="sw-grid-half">
          <ul class="sw-breadcrumb-i font-xm sw-rsq-2 bg-trans">
            <li class="sw-breadcrumb-list"><a href="<?=fileManagerDir;?>" class="st-coral"><i class="fa fa-home"></i>Home</a></li>
            <?php
           
            if (!isset($_GET['dir'])) {
              echo '';
            } else {
              echo '
                  <li class="sw-breadcrumb-list"><a href="#">Pages</a></li>
          <li class="sw-breadcrumb-list"><a href="#">Page</a></li>
          <li class="sw-breadcrumb-list active st-dodgerblue"><i class="st-goldenrod fa fa-folder-open"></i>&nbsp;Page 1</li>
                ';
            }
            
            
            ?>
          
          
          </ul>
        </div>
        <div class="sw-grid-half">
          <div class="sw-input-button-append rsq-2">
            <input type="text" class="sw-input sw-rsq-2" placeholder="Search..." />
            <button class="bg-dodgerblue br-dodgerblue st-white sw-button-append addon-border"><i class="now-ui-icons ui-1_zoom-bold"></i></button>
          </div>
        </div>
      </div>
      <!--/ BC & Search Filter -->

      <!-- Notification -->
      <?php 
      if ($countFiles == 0) {
        echo '';
      } else {

      

        echo '
          <div class="sw-ui-notification bg-dodgerblue st-white">
        <div class="select-all-box ds-inline_block">
          <input type="checkbox" class="select-all" id="file_id[]" name="select-all" />
          <label for="">Select All</label>
        </div>
        <div class="action ds-inline_block sw-right">
          <button class="sw-btn sw-btn-xs sw-btn-i-l sw-rsq-2 sw-no-br font-xd st-dodgerblue" id="delete"><i class="fa fa-trash"></i>DELETE</button>
          <button class="sw-btn sw-btn-xs sw-btn-i-l sw-rsq-2 sw-no-br font-xd st-dodgerblue"><i class="fa fa-copy"></i>COPY</button>
        <button class="sw-btn sw-btn-xs sw-btn-i-l sw-rsq-2 sw-no-br font-xd st-dodgerblue"><i class="fa fa-cut"></i>MOVE</button>
        </div>
      </div>
          ';
      }

       ?>
      <!-- /Notification -->

      <!-- Panel -->

  <div class="sw-table-responsive">
<table class="sw-table sw-table-striped font-xd">

  <tr class="<?=$subFolder;?>">
<td colspan="6" class="st-bold"><button class="sw-btn sw-btn-i-l bg-trans sw-no-br"><i class="st-dodgerblue fa fa-angle-double-left"></i>Go Back</button> </td>
</tr>

  <thead class="st-left">
<tr>
<th></th>
<th>File/Folder</th>
<th>Type</th>
<th>Size</th>
<th>Date</th>
</tr>
</thead>
<tbody>
  
<?php 


if ($countFiles == 0) {
  echo '<tr>
  <td colspan="6" class="st-center">NO FILES YET</td>
</tr>';
}  else {

   $getFiles = "SELECT * FROM repository WHERE file_directory = :parentFolder";
      $stmt = $conn->prepare($getFiles);
      $result = $stmt->execute([
        'parentFolder' => "repository"
      ]);

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $fileID = $row["file_id"];
        $fileName = $row["file_name"];
        $fileType = $row["file_type"];
        $fileSize = $row["file_size"];
        $fileDate = $row["created_on"];

        if ($fileSize < 1024) {
          $fileSize = $fileSize;
          $fileSizeUnit = "Bytes";
        } else if (($fileSize > 1024) && ($fileSize < 1024000)) {
          $fileSize = number_format($fileSize/1024, 2);
          $fileSizeUnit = "KB";
        } else if ($fileSize > 1024000) {
          $fileSize = number_format($fileSize/1024000, 2);
          $fileSizeUnit = "MB";
        }

        $fileTime = substr($fileDate, 11,15);

        $fileDate = substr($fileDate, 0,11);
        $fileDate = date("j F, Y", strtotime($fileDate));

        if ($fileType == "folder") {
          $cat = "dir";
        } else {
          $cat = "file";
        }

        if ($fileType == "folder") {
          $fileType = "FOLDER";
          $fileIcon = "st-goldenrod fa fa-folder";
        } else if ($fileType == "video/mp4") {
          $fileType = "VIDEO FILE";
          $fileIcon = "st-orange fa fa-file-video-o";
        } else if ($fileType == "text/plain") {
          $fileType = "TXT FILE";
          $fileIcon = "st-black fa fa-file-text";
        } else if ($fileType == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
          $fileType = "DOCX FILE";
          $fileIcon = "st-blue fa fa-file-word-o";
        } else if ($fileType == "application/vnd.openxmlformats-officedocument.presentationml.presentation") {
          $fileType = "PPT FILE";
          $fileIcon = "st-red fa fa-file-powerpoint-o";
        } else if ($fileType == "application/x-zip-compressed") {
          $fileType = "PPT FILE";
          $fileIcon = "st-goldenrod fa fa-file-zip-o";
        } else if ($fileType == "audio/mpeg") {
          $fileType = "AUDIO FILE";
          $fileIcon = "st-turquoise fa fa-music";
        } else if ($fileType == "application/vnd.ms-excel.sheet.macroEnabled.12" || $fileType == "application/vnd.ms-excel.sheet") {
          $fileType = "XLS FILE";
          $fileIcon = "st-green fa fa-file-excel-o";
        } else if ($fileType == "image/jpg" || $fileType == "image/jpg" || $fileType == "image/png" || $fileType == "image/gif") {
          $fileType = "IMAGE FILE";
          $fileIcon = "st-crimson fa fa-file-photo-o";
        } else if ($fileType == "application/pdf") {
          $fileType = "PDF FILE";
          $fileIcon = "st-coral fa fa-file-pdf-o";
        } else if ($fileType == "text/html") {
          $fileType = "HTML FILE";
          $fileIcon = "st-tomato fa fa-html5";
        } else {
          $fileType = "FILE";
          $fileIcon = "st-gray fa fa-file-o";
        }



   ?>

<tr>
<td width="10"><input type="checkbox" class="file_checkbox" name="file_id[]" value="<?=$fileID;?>" /></td>
<td><a href="https://localhost/file-manager?<?=$cat;?>=<?=$fileName;?>" class="st-black"><i class="<?=$fileIcon;?> font-sw"></i>&nbsp;<?=$fileName;?></a></td>
<td><?=$fileType;?></td>
<td><?="$fileSize $fileSizeUnit";?></td>
<td width="125"><span class="st-dodgerblue st-bold"><?=$fileDate;?></span><span class="ds-block st-mute font-xm"><?=$fileTime;?></span> </td>
</tr>
<?php }} ?>
</tbody>
</table>
</div>

<!-- /Panel -->
    </div>
  </div>



    <script src="static/js/jquery-3.3.1.min.js"></script>
    <script src="static/js/swift-ui.min.js"></script>
    <script src="static/js/script.js"></script>
  </body>
</html>

<?php 


 ?>


