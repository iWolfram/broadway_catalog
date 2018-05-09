<?php
include ("includes/header.php");
$showid = $_GET['showid'];

if (isset($showid)) {
	$result = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE showid = '$showid'") or die(mysqli_error($con));

	while ($row = mysqli_fetch_array($result)) {
		$title = $row['title'];
		$description = nl2br($row['description']);
		$filename = $row['filename'];
		$videoid = $row['videoid'];
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
	}

	// Pagination
	$next = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE showid = (SELECT MIN(showid) FROM broadway_catalog WHERE showid > '$showid')") or die(mysqli_error($con));
	if (mysqli_num_rows($next) == 0) {
		$nonextrecords = true;
	}else{
		while ($row = mysqli_fetch_array($next)) {
			$nextshowid = $row['showid'];
		}
	}

	$previous = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE showid = (SELECT MAX(showid) FROM broadway_catalog WHERE showid < '$showid')") or die(mysqli_error($con));
	if (mysqli_num_rows($previous) == 0) {
		$nopreviousrecords = true;
	}else{
		while ($row = mysqli_fetch_array($previous)) {
			$previousshowid = $row['showid'];
		}
	}
}

$minYearResult = mysqli_query($con, "SELECT MIN(YEAR(premiereDate)) as minYear FROM broadway_catalog WHERE YEAR(premiereDate) != 0000") or die(mysqli_error($con));
while ($row = $minYearResult->fetch_assoc()) {
	$minYear = $row['minYear'] - 5;
}
$maxYearResult = mysqli_query($con, "SELECT MAX(YEAR(premiereDate)) as maxYear FROM broadway_catalog WHERE YEAR(premiereDate) != 0000") or die(mysqli_error($con));
while ($row = $maxYearResult->fetch_assoc()) {
	$maxYear = $row['maxYear'] + 2;
}
$lowYear = floor($minYear + 10);
$highYear = floor($maxYear - 10);

$minPerformancesResult = mysqli_query($con, "SELECT MIN(totalPerformances) as minPer FROM broadway_catalog WHERE totalPerformances != 0") or die(mysqli_error($con));
while ($row = $minPerformancesResult->fetch_assoc()) {
	$minPerformances = $row['minPer'] - 1;
}
$maxPerformancesResult = mysqli_query($con, "SELECT MAX(totalPerformances) as maxPer FROM broadway_catalog WHERE totalPerformances != 0") or die(mysqli_error($con));
while ($row = $maxPerformancesResult->fetch_assoc()) {
	$maxPerformances = $row['maxPer'] + 1;
}
$lowPerformances = floor($minPerformances + 3000);
$highPerformances = floor($maxPerformances - 3000);

?>
<div class="col-md-12 well">
	<div class="col-md-8 clearfix">
		<nav class="pull-right">
			<ul class="pagination">
				<?php 
				if ($nopreviousrecords == true): ?>
				<li class="page-item disabled"><a href="#" class="page-link">Previous</a></li>
				<?php else: ?>
				<li class="page-item"><a href="<?php echo $_SERVER['PHP_SELF'] . "?showid=$previousshowid"; ?>" class="page-link">Previous</a></li>
				<?php endif;
				if ($nonextrecords == true): ?>
				<li class="page-item disabled"><a href="#" class="page-link disabled">Next</a></li>
				<?php else: ?>
				<li class="page-item"><a href="<?php echo $_SERVER['PHP_SELF'] . "?showid=$nextshowid"; ?>" class="page-link">Next</a></li>
				<?php endif; ?>
			</ul>
		</nav>
		<h1 class="page-header text-center"><?php echo $title ?></h1>
		<hr>
		<div class="col-md-8">
			<form class="form-horizontal">
				<div class="form-group">
					<label class="control-label col-md-3" for="description">Description:</label>
					<div class="col-md-9">
						<p class="form-control-static" style="text-align: justify;"><?php echo $description; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="musicby">Music By:</label>
					<div class="col-md-9">
						<p class="form-control-static"><?php if ($musicby == "") echo "No information available"; else echo $musicby; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="lyricsby">Lyrics By:</label>
					<div class="col-md-9">
						<p class="form-control-static"><?php if ($lyricsby == "") echo "No information available"; else echo $lyricsby; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="totalPerformances">Total Performances:</label>
					<div class="col-md-9">
						<p class="form-control-static"><?php if ($totalPerformances == "") echo "No information available"; else echo $totalPerformances; ?></p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="category">Category:</label>
					<div class="col-md-9">
						<p class="form-control-static">
							<?php
							if ($original == 1) {
								echo "Originals";
							}
							if ($comedy == 1) {
								echo ", Comedy";
							}
							if ($drama == 1) {
								echo ", Drama";
							}
							if ($romance == 1) {
								echo ", Romance";
							}
							?>
						</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3" for="premiered">Premiered:</label>
					<div class="col-md-9">
						<p class="form-control-static"><?php if ($premiereDate != "0000-00-00") echo date("F, j, Y", strtotime($premiereDate)); else echo "No information available" ?></p>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="edmontondate">Edmonton Show Dates:</label>
					<div class="col-md-9">
						<p class="form-control-static"><?php if ($edmontonStartDate != "0000-00-00" && $edmontonEndDate != "0000-00-00") echo date("M, j, Y", strtotime($edmontonStartDate))." to ".date("M, j, Y", strtotime($edmontonEndDate)); else echo "No information available" ?></p>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-md-3" for="trailer">Trailer:</label>
					<div class="col-md-9">
						<p class="form-control-static"><?php if ($videoid != "") echo "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/$videoid?rel=0&amp;showinfo=0\" frameborder=\"0\"></iframe>"; else echo "No video available" ?></p>
					</div>
				</div>
			</form>
		</div>
		<img class="col-md-4" src="admin/displays/<?php echo $filename ?>">
	</div>
	<div class="col-md-4 sidebar">
		<form method="post" action="list.php" class="col-md-12">
			<div class="input-group form-group has-feedback">
				<input type="text" class="form-control" placeholder="Search for a broadway here" name="searchterm" autocomplete="off" />
				<span class="input-group-btn">
					<button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
				</span>
			</div>
		</form>
		<div class="col-md-12">
			<div class="panel panel-primary text-center">
				<div class="panel-heading">
					<div class="panel-title">
						<h4 class="panel-title">
							Filter By
						</h4>
					</div>
				</div>
				<div class="panel-body">
					
					<div class="panel-group text-center">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#categories">Categories</a>
								</h4>
							</div>
							<div class="panel-body">
								<div id="categories" class="panel-collapse collapse">
									<ul class="list-group">
										<li class="list-group-item"><a href="list.php">All</a></li>
										<!-- <li class="list-group-item"><a href="list.php?displayby=categories&displayvalue=original">Originals</a></li> -->
										<li class="list-group-item"><a href="list.php?displayby=categories&displayvalue=comedy">Comedy</a></li>
										<li class="list-group-item"><a href="list.php?displayby=categories&displayvalue=drama">Drama</a></li>
										<li class="list-group-item"><a href="list.php?displayby=categories&displayvalue=romance">Romance</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="panel panel-info">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#year">Premiered Year</a>
								</h4>
							</div>
							<div class="panel-body">
								<div id="year" class="panel-collapse collapse">
									<script>
										$( function() {
										$( "#slider-range" ).slider({
										  range: true,
										  min: <?php echo $minYear ?>,
										  max: <?php echo $maxYear ?>,
										  values: [ <?php echo $lowYear ?>, <?php echo $highYear ?> ],
										  slide: function( event, ui ) {
										  	$( "#min" ).val(ui.values[0]);
										    $( "#max" ).val(ui.values[1]);
										    $( "#amount" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
										  }
										});
										$( "#amount" ).val( $( "#slider-range" ).slider( "values", 0 ) + " - " + $( "#slider-range" ).slider( "values", 1 ) );
										} );
									</script>
									<form action="list.php?displayby=year" method="post" style="margin-top: 20px;">
										<label for="amount" style="font-size: initial; font-weight: bold;">Year range:</label>
										<input type="text" readonly id="amount" style="border: none; color: #f6931f; font-weight: bold; background: transparent;" /><br />

										<input type="hidden" id="min" name="min" value="<?php echo $lowYear; ?>" />
										<input type="hidden" id="max" name="max" value="<?php echo $highYear; ?>" />
										<div class="col-md-10 col-md-offset-1" id="slider-range" style="margin-bottom: 20px;"></div>

										<input type="submit" name="submit" value="FILTER YEAR" class="btn btn-info" />
									</form>
								</div>
							</div>
						</div>

						
						<div class="panel panel-info">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" href="#performances">Total Performances</a>
								</h4>
							</div>
							<div class="panel-body">
								<div id="performances" class="panel-collapse collapse">
									<script>
										$( function() {
										$( "#slider-range-performance" ).slider({
										  range: true,
										  min: <?php echo $minPerformances ?>,
										  max: <?php echo $maxPerformances ?>,
										  values: [ <?php echo $lowPerformances ?>, <?php echo $highPerformances ?> ],
										  slide: function( event, ui ) {
										  	$( "#minPerformance" ).val(ui.values[0]);
										    $( "#maxPerformance" ).val(ui.values[1]);
										    $( "#amountPerformance" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
										  }
										});
										$( "#amountPerformance" ).val( $( "#slider-range-performance" ).slider( "values", 0 ) + " - " + $( "#slider-range-performance" ).slider( "values", 1 ) );
										} );
									</script>
									<form action="list.php?displayby=totalPerformances" method="post" style="margin-top: 20px;">
										<label for="amountPerformance" style="font-size: initial; font-weight: bold;">Performance range:</label>
										<input type="text" readonly id="amountPerformance" style="border: none; color: #f6931f; font-weight: bold; background: transparent;" /><br />

										<input type="hidden" id="minPerformance" name="min" value="<?php echo $lowPerformances; ?>" />
										<input type="hidden" id="maxPerformance" name="max" value="<?php echo $highPerformances; ?>" />
										<div class="col-md-10 col-md-offset-1" id="slider-range-performance" style="margin-bottom: 20px;"></div>

										<input type="submit" name="submit" value="FILTER PERFORMANCE" class="btn btn-info" />
									</form>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div> <!-- end of panel-primary -->
		</div>
	</div>
</div>
<?php include ("includes/footer.php"); ?>