<?php
include("../includes/header.php");
include("../includes/image-functions.php");
include("../includes/watermark.php");
if(!isset($_SESSION['9vvh56nrtljefnj9ksk3223ft6'])){
	header("Location:login.php?from=insert");
}else{
	if (isset($_POST['submit'])) {
		$title = strip_tags(trim($_POST['title']));
		$description = strip_tags(trim(str_replace("'", "\'", $_POST['description'])));
		$filename = $_FILES['myfile']['name'];
		$filetype = $_FILES['myfile']['type'];
		$filesize = $_FILES['myfile']['size'];
		$filetmpname = $_FILES['myfile']['tmp_name'];
		$youtube = strip_tags(trim($_POST['youtube']));
		$premiereDate = strip_tags(trim($_POST['premiereDate']));
		$edmontonStartDate = strip_tags(trim($_POST['edmontonStartDate']));
		$edmontonEndDate = strip_tags(trim($_POST['edmontonEndDate']));
		$musicby = strip_tags(trim($_POST['musicby']));
		$lyricsby = strip_tags(trim($_POST['lyricsby']));
		$category = $_POST['category'];
		$totalPerformances = strip_tags(trim($_POST['totalPerformances']));

		if(!empty($category)) {
			foreach($category as $value) {
				if ($value == "original") {
					$original = 1;
				}
				if ($value == "comedy") {
					$comedy = 1;
				}
				if ($value == "drama") {
					$drama = 1;
				}
				if ($value == "romance") {
					$romance = 1;
				}
			}
		}

		$boolTitle = true;
		$boolDescription = true;
		$boolFile = true;
		$boolYoutube = true;
		$boolPremiereDate = true;
		$boolEdmontonStartDate = true;
		$boolEdmontonEndDate = true;
		$boolMusicBy = true;
		$boolLyricsBy = true;
		$boolTotalPerformances = true;

		$strTitle = "";
		$strDescription = "";
		$strFile = "";
		$strYoutube = "";
		$strPremiereDate = "";
		$strEdmontonStartDate = "";
		$strEdmontonEndDate = "";
		$strMusicBy = "";
		$strLyricsBy = "";
		$strTotalPerformances = "";

		$unique = uniqid("", true);

		// Title
		if ($title == "") {
			$boolTitle = false;
			$strTitle = "Please enter a title<br>";		
		}elseif (strlen($title) < 2) {
			$boolTitle = false;
			$strTitle = "Please enter more than 2 characters<br>";
		}elseif (strlen($title) > 50) {
			$boolTitle = false;
			$strTitle = "Please limit your entry to 50 characters<br>";
		}

		// Description
		if ($description == "") {
			$boolDescription = false;
			$strDescription = "Please enter a description<br>";		
		}elseif (strlen($description) < 2) {
			$boolDescription = false;
			$strDescription = "Please enter more than 2 characters<br>";
		}elseif (strlen($description) > 10000) {
			$boolDescription = false;
			$strDescription = "Please limit your entry to 10000 characters<br>";
		}

		// File
		if ($filename == "") {
			$boolFile = false;
			$strFile = "Please upload a file.";
		}elseif ($filetype != "image/jpeg" && $filetype != "image/png") {
			$boolFile = false;
			$strFile .= "Please upload only JPEG and PNG images.<br>";
		}
		if ($filesize > 2000000) { // 2MB
			$boolFile = false;
			$strFile .= "File size is too large. Limit to (2MB).<br>";
		}

		// Youtube
		$regex_pattern = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";
		$match;
		$videourl;
		$videoid;

		if ($youtube != "") {
			if (preg_match($regex_pattern, $youtube, $match)){
				$boolYoutube = true;
				$videourl = $match[0];
				$videoid = $match[1];
			} else {
				$boolYoutube = false;
				$strYoutube = "Please enter a YouTube URL with a <span style=\"color:#fffc7c;\"><em>video ID</em></span>.<br>(Example: https://www.youtube.com/watch?<span style=\"color:#fffc7c;\"><em>v=t2E2vyqSSqU</em></span>)<br>";
			}
		}
		

		// Premiere Date
		if ($premiereDate != "") {
			if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$premiereDate)) {
				$boolPremiereDate = false;
				$strPremiereDate = "Please enter a valid date.<br>";
			}
		}

		// Edmonton Start Date
		if ($edmontonStartDate != "") {
			if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$edmontonStartDate)) {
				$boolEdmontonEndDate = false;
				$strEdmontonEndDate = "Please enter a valid date.<br>";
			}
		}

		// Edmonton End Date
		if ($edmontonEndDate != "") {
			if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$edmontonEndDate)) {
				$boolEdmontonEndDate = false;
				$strEdmontonEndDate = "Please enter a valid date.<br>";
			}
		}

		// // Music By
		// if ($musicby == "") {
		// 	$boolMusicBy = false;
		// 	$strMusicBy = "Please enter a value for music by.<br>";
		// }

		// // Lyrics By
		// if ($lyricsby == "") {
		// 	$boolLyricsBy = false;
		// 	$strLyricsBy = "Please enter a value for lyrics by.<br>";
		// }

		// Total Performances
		if ($totalPerformances < 0) {
			$boolTotalPerformances = false;
			$strTotalPerformances = "Please enter a value greater than 0.<br>";
		}

		// Validation passes
		if ($boolTitle == true && $boolDescription == true && $boolFile == true && $boolYoutube == true && $boolPremiereDate == true
			&& $boolEdmontonStartDate == true && $boolEdmontonEndDate == true && $boolTotalPerformances) {
			if (move_uploaded_file($filetmpname, "originals/" . $unique)) {
				$thisFile = "originals/" . $unique;

				if ($filetype == "image/png") {
					createSquarePNGImageCopy($thisFile, "thumbs/", 150, $unique);
					resizePNGImage($thisFile, "", 230, "placeholder");
					mergePNGPix("placeholder", "../includes/watermark-logo.png", "displays/".$unique, 4, 50);
					unlink("placeholder");
				} else {
					createSquareImageCopy($thisFile, "thumbs/", 150, $unique);
					resizeImage($thisFile, "", 230, "placeholder");
					mergePix("placeholder", "../includes/watermark-logo.png", "displays/".$unique, 4, 50);
					unlink("placeholder");
				}

			mysqli_query($con,
				"INSERT INTO broadway_catalog (title, description, filename, premiereDate, videourl, videoid, edmontonStartDate, edmontonEndDate, musicby, lyricsby, original, comedy, drama, romance, totalPerformances) 
				 VALUES ('$title', '$description', '$unique', '$premiereDate', '$videourl', '$videoid', '$edmontonStartDate', '$edmontonEndDate', '$musicby', '$lyricsby', '$original', '$comedy', '$drama', '$romance', '$totalPerformances')")
			     or die(mysqli_error($con));

			$title = "";
			$description = "";
			$musicby = "";
 			$lyricsby = "";
			$youtube = "";
			$premiereDate = "";
			$edmontonStartDate = "";
			$edmontonEndDate = "";
			$original = 0;
			$comedy = 0;
			$drama = 0;
			$romance = 0;

			$successMsg = "<p style=\"color:#4BB543; font-weight:bold;\">Broadway information uploaded!</p>";
			}else{
				$successMsg = "<p style=\"color:red; font-weight:bold;\">Error with uploading the broadway information</p>";
				$title = "";
				$description = "";
				$musicby = "";
 				$lyricsby = "";
				$youtube = "";
				$premiereDate = "";
				$edmontonStartDate = "";
				$edmontonEndDate = "";
				$original = 0;
				$comedy = 0;
				$drama = 0;
 				$romance = 0;
			}
		} // end of validation pass
	} // end of isset post submit
} // end of login validation

function checkValidation($boolValidation) {
	if (isset($_POST['submit'])) {
		if($boolValidation == false)
		{
			echo 'style="border: 2px solid red; background: rgba(255, 0, 0, 0.2); color: white;"';
		}
	}
}

function errorMessage($boolValidation, $strValidation) {
	if ($boolValidation == false) {
		echo "<p style=\"color:red; font-weight:bold;\">".$strValidation."</p>";
	}
}
?>
<div class="well col-md-10 col-md-offset-1">
	<h1>Insert</h1>
	<hr>
	<?php 
		if ($successMsg){
			echo $successMsg;
		}
	?>
	<form id="myform" name="myform" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
		<div class="form-group col-md-12">
			<label for="title">Title: *</label>
			<input type="text" name="title" class="form-control" value="<?php echo $title ?>" <?php checkValidation($boolTitle) ?>>
			<?php errorMessage($boolTitle, $strTitle) ?>
		</div>
		<div class="form-group  col-md-12">
			<label for="description">Description: *</label>
			<textarea name="description" class="form-control" <?php checkValidation($boolDescription) ?>><?php echo $description ?></textarea>
			<?php errorMessage($boolDescription, $strDescription) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="musicby">Music By:</label>
			<input type="text" name="musicby" class="form-control" value="<?php echo $musicby ?>" <?php checkValidation($boolMusicBy) ?>>
			<?php errorMessage($boolMusicBy, $strMusicBy) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="lyricsby">Lyrics By:</label>
			<input type="text" name="lyricsby" class="form-control" value="<?php echo $lyricsby ?>" <?php checkValidation($boolLyricsBy) ?>>
			<?php errorMessage($boolLyricsBy, $strLyricsBy) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="myfile">Upload an image: *</label>
			<input type="file" name="myfile" class="form-control" <?php checkValidation($boolFile) ?>>
			<?php errorMessage($boolFile, $strFile) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="youtube">Youtube URL:</label>
			<input type="url" name="youtube" class="form-control" value="<?php echo $youtube ?>" <?php checkValidation($boolYoutube) ?>>
			<?php errorMessage($boolYoutube, $strYoutube) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="totalPerformances">Total Performances:</label>
			<input type="number" name="totalPerformances" class="form-control" value="<?php echo $totalPerformances ?>" <?php checkValidation($boolTotalPerformances) ?>>
			<?php errorMessage($boolTotalPerformances, $strTotalPerformances) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="premiereDate">Premiere Date:</label>
			<input type="text" id="datepicker" name="premiereDate" class="form-control" value="<?php echo $premiereDate ?>" <?php checkValidation($boolPremiereDate) ?>>
			<?php errorMessage($boolPremiereDate, $strPremiereDate) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="edmontonStartDate">Start Date in Edmonton:</label>
			<input type="text" id="datepicker2" name="edmontonStartDate" class="form-control" value="<?php echo $edmontonStartDate ?>" <?php checkValidation($boolEdmontonStartDate) ?>>
			<?php errorMessage($boolEdmontonStartDate, $strEdmontonStartDate) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="edmontonEndDate">End Date in Edmonton:</label>
			<input type="text" id="datepicker3" name="edmontonEndDate" class="form-control" value="<?php echo $edmontonEndDate ?>" <?php checkValidation($boolEdmontonEndDate) ?>>
			<?php errorMessage($boolEdmontonEndDate, $strEdmontonEndDate) ?>
		</div>
		<div class="form-group col-md-6">
			<label class="col-md-12 row">Category:</label>
			<div class="checkbox col-md-3 row" style="margin-top: 0;">
				<label><input type="checkbox" name="category[]" value="original" <?php if($original == 1) echo "checked" ?>>Original</label>
			</div>
			<div class="checkbox col-md-3 row" style="margin-top: 0;">
				<label><input type="checkbox" name="category[]" value="comedy" <?php if($comedy == 1) echo "checked" ?>>Comedy</label>
			</div>
			<div class="checkbox col-md-3 row" style="margin-top: 0;">
				<label><input type="checkbox" name="category[]" value="drama" <?php if($drama == 1) echo "checked" ?>>Drama</label>
			</div>
			<div class="checkbox col-md-3 row" style="margin-top: 0;">
				<label><input type="checkbox" name="category[]" value="romance" <?php if($romance == 1) echo "checked" ?>>Romance</label>
			</div>
		</div>

		<div class="form-group col-md-12">
			<input type="submit" name="submit" class="btn btn-info" value="Insert">
		</div>
	</form>
</div>
<script>
  $( function() {
  $( "#datepicker" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "yy-mm-dd"
  });
  } );
</script>
<script>
  $( function() {
  $( "#datepicker2" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "yy-mm-dd"
  });
  } );
</script>
<script>
  $( function() {
  $( "#datepicker3" ).datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "yy-mm-dd"
  });
  } );
</script>
<?php
	include("../includes/footer.php");
?>