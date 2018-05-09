<?php
include ("../includes/header.php");
include("../includes/image-functions.php");
include("../includes/watermark.php");
if(!isset($_SESSION['9vvh56nrtljefnj9ksk3223ft6'])){
	header("Location:login.php?from=edit");
}else{
	$showid = $_GET['showid'];
	if (!isset($showid)) {
		$result = mysqli_query($con, "SELECT * FROM broadway_catalog LIMIT 1") or die(mysqli_error($con));

		while($row = mysqli_fetch_array($result)){
			$showid = $row['showid'];
		} // end of while
	}

	// The following is used for capturing the exisiting filename in case the user deletes the picture
	$result2 = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE showid = '$showid'") or die(mysqli_error($con));

	while($row = mysqli_fetch_array($result2)){
		// $title = $row['title'];
		// $description = $row['description'];
		$filename = $row['filename'];
	} // end of while

	// Step 2:
	if (isset($_POST['submit'])) {
		if ($_FILES['myfile']['name'] != "") {
			// delete existing iamges
			@unlink("originals/" . $filename);
			@unlink("thumbs/" . $filename);
			@unlink("displays/" . $filename);
		}

		$newTitle = strip_tags(trim($_POST['title']));
		$newDescription = strip_tags(trim(str_replace("'", "\'", $_POST['description'])));
		$newFilename = $_FILES['myfile']['name'];
		$newFiletype = $_FILES['myfile']['type'];
		$newFilesize = $_FILES['myfile']['size'];
		$newFiletmpname = $_FILES['myfile']['tmp_name'];
		$newYoutube = strip_tags(trim($_POST['youtube']));
		$newPremiereDate = strip_tags(trim($_POST['premiereDate']));
		$newEdmontonStartDate = strip_tags(trim($_POST['edmontonStartDate']));
		$newEdmontonEndDate = strip_tags(trim($_POST['edmontonEndDate']));
		$newMusicby = strip_tags(trim($_POST['musicby']));
		$newLyricsby = strip_tags(trim($_POST['lyricsby']));
		$newCategory = $_POST['category'];
		$newTotalPerformances = strip_tags(trim($_POST['totalPerformances']));

		if(!empty($newCategory)) {
			foreach($newCategory as $value) {
				if ($value == "original") {
					$newOriginal = 1;
				}
				if ($value == "comedy") {
					$newComedy = 1;
				}
				if ($value == "drama") {
					$newDrama = 1;
				}
				if ($value == "romance") {
					$newRomance = 1;
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
		if ($newTitle == "") {
			$boolTitle = false;
			$strTitle = "Please enter a title<br>";		
		}elseif (strlen($newTitle) < 2) {
			$boolTitle = false;
			$strTitle = "Please enter more than 2 characters<br>";
		}elseif (strlen($newTitle) > 50) {
			$boolTitle = false;
			$strTitle = "Please limit your entry to 50 characters<br>";
		}

		// Description
		if ($newDescription == "") {
			$boolDescription = false;
			$strDescription = "Please enter a description<br>";		
		}elseif (strlen($newDescription) < 2) {
			$boolDescription = false;
			$strDescription = "Please enter more than 2 characters<br>";
		}elseif (strlen($newDescription) > 10000) {
			$boolDescription = false;
			$strDescription = "Please limit your entry to 10000 characters<br>";
		}

		// File
		if ($newFilename == "") {
			$newFilename = "";
		}elseif ($newFiletype != "image/jpeg" && $newFiletype != "image/png") {
			$boolFile = false;
			$strFile .= "Please upload only JPEG and PNG images.<br>";
		}
		if ($newFilesize > 2000000) { // 2MB
			$boolFile = false;
			$strFile .= "File size is too large. Limit to (2MB).<br>";
		}

		// Youtube
		$regex_pattern = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";
		$match;
		$newVidourl;
		$newVideoid;

		if ($newYoutube != "") {
			if (preg_match($regex_pattern, $newYoutube, $match)){
				$boolYoutube = true;
				$newVideourl = $match[0];
				$newVideoid = $match[1];
			} else {
				$boolYoutube = false;
				$strYoutube = "Please enter a YouTube URL with a <span style=\"color:#fffc7c;\"><em>video ID</em></span>.<br>(Example: https://www.youtube.com/watch?<span style=\"color:#fffc7c;\"><em>v=t2E2vyqSSqU</em></span>)<br>";
			}
		}
		

		// Premiere Date
		if ($newPremiereDate != "") {
			if (!preg_match("/^[0-9]{4}-(0[0-9]|1[0-2])-(0[0-9]|[1-2][0-9]|3[0-1])$/",$newPremiereDate)) {
				$boolPremiereDate = false;
				$strPremiereDate = "Please enter a valid date.<br>";
			}
		}

		// Edmonton Start Date
		if ($newEdmontonStartDate != "") {
			if (!preg_match("/^[0-9]{4}-(0[0-9]|1[0-2])-(0[0-9]|[1-2][0-9]|3[0-1])$/",$newEdmontonStartDate)) {
				$boolEdmontonEndDate = false;
				$strEdmontonEndDate = "Please enter a valid date.<br>";
			}
		}

		// Edmonton End Date
		if ($newEdmontonEndDate != "") {
			if (!preg_match("/^[0-9]{4}-(0[0-9]|1[0-2])-(0[0-9]|[1-2][0-9]|3[0-1])$/",$newEdmontonEndDate)) {
				$boolEdmontonEndDate = false;
				$strEdmontonEndDate = "Please enter a valid date.<br>";
			}
		}

		// // Music By
		// if ($newMusicby == "") {
		// 	$boolMusicBy = false;
		// 	$strMusicBy = "Please enter a value for music by.<br>";
		// }

		// // Lyrics By
		// if ($newLyricsby == "") {
		// 	$boolLyricsBy = false;
		// 	$strLyricsBy = "Please enter a value for lyrics by.<br>";
		// }

		// Total Performances
		if ($newTotalPerformances < 0) {
			$boolTotalPerformances = false;
			$strTotalPerformances = "Please enter a value greater than 0.<br>";
		}

		// Validation passes
		if ($boolTitle == true && $boolDescription == true && $boolFile == true && $boolYoutube == true && $boolPremiereDate == true && $boolEdmontonStartDate == true && $boolEdmontonEndDate == true && $boolMusicBy == true && $boolLyricsBy == true && $boolTotalPerformances == true) {
			if ($newFilename == "") {
				mysqli_query($con,
					"UPDATE broadway_catalog
					 SET
					 title = '$newTitle',
					 description = '$newDescription',
					 videourl = '$newVideourl',
					 videoid = '$newVideoid',
					 premiereDate = '$newPremiereDate',
					 edmontonStartDate = '$newEdmontonStartDate',
					 edmontonEndDate = '$newEdmontonEndDate',
					 musicby = '$newMusicby',
					 lyricsby = '$newLyricsby',
					 original = '$newOriginal',
					 comedy = '$newComedy',
					 drama = '$newDrama',
					 romance = '$newRomance',
					 totalPerformances = '$newTotalPerformances'
					 WHERE showid = '$showid'")
					 or die(mysqli_error($con));

				// $title = "";
				// $description = "";
				// $youtube = "";
				// $premiereDate = "";
				// $edmontonStartDate = "";
				// $edmontonEndDate = "";
				// $musicby = "";
				// $lyricsby = "";
				// $totalPerformances = "";

				$successMsg = "<p style=\"color:#4BB543; font-weight:bold;\">Broadway information updated!</p>";
			}else{
				if (move_uploaded_file($newFiletmpname, "originals/" . $unique)) {
					$thisFile = "originals/" . $unique;

					if ($newFiletype == "image/png") {
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
					"UPDATE broadway_catalog
					 SET
					 title = '$newTitle',
					 description = '$newDescription',
					 filename = '$unique',
					 videourl = '$newVideourl',
					 videoid = '$newVideoid',
					 premiereDate = '$newPremiereDate',
					 edmontonStartDate = '$newEdmontonStartDate',
					 edmontonEndDate = '$newEdmontonEndDate',
					 musicby = '$newMusicby',
					 lyricsby = '$newLyricsby',
					 original = '$newOriginal',
					 comedy = '$newComedy',
					 drama = '$newDrama',
					 romance = '$newRomance',
					 totalPerformances = '$newTotalPerformances'
					 WHERE showid = '$showid'")
					 or die(mysqli_error($con));

				// $title = "";
				// $description = "";
				// $youtube = "";
				// $premiereDate = "";
				// $edmontonStartDate = "";
				// $edmontonEndDate = "";
				// $musicby = "";
				// $lyricsby = "";

				$successMsg = "<p style=\"color:#4BB543; font-weight:bold;\">Broadway information updated!</p>";
				}else{
					$successMsg = "<p style=\"color:red; font-weight:bold;\">Error with updating the broadway information</p>";
					// $title = "";
					// $description = "";
					// $youtube = "";
					// $premiereDate = "";
					// $edmontonStartDate = "";
					// $edmontonEndDate = "";
					// $musicby = "";
					// $lyricsby = "";
				}
			}
		} // end of validation pass
	} // end of if submit

	// Step 1: retrieve data for the selected character only; use this to prepopulate the form
	$result2 = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE showid = '$showid'") or die(mysqli_error($con));

	while($row = mysqli_fetch_array($result2)){
		$title = $row['title'];
		$description = $row['description'];
		$filename = $row['filename'];
		$youtube = $row['videourl'];
		$premiereDate = $row['premiereDate'];
		$edmontonStartDate = $row['edmontonStartDate'];
		$edmontonEndDate = $row['edmontonEndDate'];
		$musicby = $row['musicby'];
		$lyricsby = $row['lyricsby'];
		$original = $row['original'];
		$comedy = $row['comedy'];
		$drama = $row['drama'];
		$romance = $row['romance'];
		$totalPerformances = $row['totalPerformances'];
	} // end of while

	if(isset($_POST['delete'])){
		unlink("originals/" . $filename);
		unlink("thumbs/" . $filename);
		unlink("displays/" . $filename);
		mysqli_query($con, "DELETE FROM broadway_catalog WHERE showid = '$showid'") or die(mysqli_error($con));
		header("Location:edit.php"); // this refreshes the page
	}
}

function checkValidation($var) {
	if (isset($_POST['submit'])) {
		if($var == false)
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

<script>
function go()
{
	// box = document.navform.entryselect; // gets the form element by the name attribute
	box = document.getElementById('entryselect'); // gets form element by the id.
	destination = box.options[box.selectedIndex].value;
	if (destination) location.href = destination;
}
</script>

<div class="well col-md-10 col-md-offset-1">
	<h1>Select a Broadway to Edit</h1>
	<form>
		<div class="form-group">
			<select name="selectImage" class="form-control" id="entryselect" onchange="go()">
				<?php 
				$result3 = mysqli_query($con, "SELECT * FROM broadway_catalog") or die(mysqli_error($con));

				while($row = mysqli_fetch_array($result3)):
					$titleLink = $row['title'];
					$showidLink = $row['showid'];

					if ($showidLink == $showid): ?>

						<option value="edit.php?showid=<?php echo $showidLink ?>" selected="selected"><?php echo $titleLink ?></option>
					<?php else: ?>
						<option value="edit.php?showid=<?php echo $showidLink ?>"><?php echo $titleLink ?></option>
					<?php endif; ?>
				<?php endwhile; // end of while	?>
			</select>
		</div>
	</form>
</div>
<div class="well col-md-10 col-md-offset-1">
	<h1>Edit Section</h1>
	<?php 
		if ($successMsg) {
			echo $successMsg;
		}
	?>
	<form id="myform" name="myform" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">
		<div class="form-group col-md-12">
			<label for="title">Title: *</label>
			<input type="text" name="title" class="form-control" value="<?php echo $title ?>" <?php checkValidation($boolTitle) ?>>
			<?php errorMessage($boolTitle, $strTitle) ?>
		</div>
		<div class="form-group col-md-12">
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
		<!-- <div class="form-group col-md-12">
			<label for="image">Current Image:</label>
			<img src="thumbs/<?php echo $filename ?>" style="margin-left: 20px;">
		</div> -->
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
			<input type="text" id="datepicker" name="premiereDate" class="form-control" value="<?php if ($premiereDate != "0000-00-00") echo $premiereDate ?>" <?php checkValidation($boolPremiereDate) ?>>
			<?php errorMessage($boolPremiereDate, $strPremiereDate) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="edmontonStartDate">Start Date in Edmonton:</label>
			<input type="text" id="datepicker2" name="edmontonStartDate" class="form-control" value="<?php if ($edmontonStartDate != "0000-00-00") echo $edmontonStartDate ?>" <?php checkValidation($boolEdmontonStartDate) ?>>
			<?php errorMessage($boolEdmontonStartDate, $strEdmontonStartDate) ?>
		</div>
		<div class="form-group col-md-6">
			<label for="edmontonEndDate">End Date in Edmonton:</label>
			<input type="text" id="datepicker3" name="edmontonEndDate" class="form-control" value="<?php if ($edmontonEndDate != "0000-00-00") echo $edmontonEndDate ?>" <?php checkValidation($boolEdmontonEndDate) ?>>
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
		<div class="form-group col-md-6">
			<label for="image">Current Image:</label>
			<img src="thumbs/<?php echo $filename ?>" style="margin-left: 20px;">
		</div>
		<div class="form-group col-md-12">
			<input type="submit" name="submit" class="btn btn-info" value="Update">
			<label for="delete">&nbsp;</label>
			<input type="submit" name="delete" class="btn btn-danger" value="Delete" onclick="return confirm('Are you sure you want to delete <?php echo $title; ?>?')">
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
include ("../includes/footer.php");
?>