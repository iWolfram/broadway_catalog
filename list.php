<?php
include ("includes/header.php");
$header = "";
$searchterm = $_POST['searchterm'];
$displayby = $_GET['displayby'];
$displayvalue = $_GET['displayvalue'];
$min = $_POST['min'];
$max = $_POST['max'];

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

// if (isset($searchterm)) {
// 	$getcount = mysqli_query($con, "SELECT COUNT(*) FROM broadway_catalog WHERE title LIKE '$searchterm'") or die(mysqli_error($con));
// } elseif (isset($displayby)) {
// 	if (isset($displayvalue)) {
// 		if ($displayby == "categories") {
// 			if ($displayvalue == "original") {
// 				$getcount = mysqli_query($con, "SELECT COUNT(*) FROM broadway_catalog WHERE $displayvalue = 1") or die(mysqli_error($con));
// 			}
// 			if ($displayvalue == "comedy") {
// 				$getcount = mysqli_query($con, "SELECT COUNT(*) FROM broadway_catalog WHERE $displayvalue = 1") or die(mysqli_error($con));
// 			}
// 			if ($displayvalue == "drama") {
// 				$getcount = mysqli_query($con, "SELECT COUNT(*) FROM broadway_catalog WHERE $displayvalue = 1") or die(mysqli_error($con));
// 			}
// 			if ($displayvalue == "romance") {
// 				$getcount = mysqli_query($con, "SELECT COUNT(*) FROM broadway_catalog WHERE $displayvalue = 1") or die(mysqli_error($con));
// 			}
// 		}
// 	} elseif (isset($min) && isset($max)) {
// 		$getcount = mysqli_query($con, "SELECT COUNT(*) FROM broadway_catalog WHERE YEAR(premiereDate) BETWEEN $min AND $max") or die(mysqli_error($con));
// 	}
// } else {
// 	$getcount = mysqli_query ($con,"SELECT COUNT(*) FROM broadway_catalog");
// }

//////////// pagination
$getcount = mysqli_query ($con,"SELECT COUNT(*) FROM broadway_catalog");
$postnum = mysqli_result($getcount,0);// this needs a fix for MySQLi upgrade; see custom function below
$limit = 6;
if($postnum > $limit){
$tagend = round($postnum % $limit,0);
$splits = round(($postnum - $tagend)/$limit,0);

if($tagend == 0){
$num_pages = $splits;
}else{
$num_pages = $splits + 1;
}

if(isset($_GET['pg'])){
$pg = $_GET['pg'];
}else{
$pg = 1;
}
$startpos = ($pg*$limit)-$limit;
$limstring = "LIMIT $startpos,$limit";
}else{
$limstring = "LIMIT 0,$limit";
}

// MySQLi upgrade: we need this for mysql_result() equivalent
function mysqli_result($res, $row, $field=0) { 
    $res->data_seek($row); 
    $datarow = $res->fetch_array(); 
    return $datarow[$field]; 
}
//////////////

if (isset($searchterm)) {
	if ($searchterm == "") {
		$header = "Invalid search.";
	} else {
		$result = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE title LIKE '$searchterm%' ORDER BY title") or die(mysqli_error($con));
		if (mysqli_num_rows($result) > 1 || mysqli_num_rows($result) == 0) {
			$header = "Search results for: \"".$searchterm."\"";
		} else {
			$header = "Search result for: \"".$searchterm."\"";
		}
	}
} elseif (isset($displayby)) {
	if (isset($displayvalue)) {
		if ($displayby == "categories") {
			// if ($displayvalue == "original") {
			// 	$result = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE $displayvalue = 1") or die(mysqli_error($con));
			// 	$header = "Category: Originals";
			// }
			if ($displayvalue == "comedy") {
				$result = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE $displayvalue = 1 ORDER BY title") or die(mysqli_error($con));
				$header = "Category: Comedy";
			}
			if ($displayvalue == "drama") {
				$result = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE $displayvalue = 1 ORDER BY title") or die(mysqli_error($con));
				$header = "Category: Drama";
			}
			if ($displayvalue == "romance") {
				$result = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE $displayvalue = 1 ORDER BY title") or die(mysqli_error($con));
				$header = "Category: Romance";
			}
		}
	} elseif (isset($min) && isset($max)) {
		if ($displayby == "year") {
			$result = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE YEAR(premiereDate) BETWEEN $min AND $max ORDER BY premiereDate") or die(mysqli_error($con));
			$header = "Shows premiered between: $min - $max";
		} else {
			$result = mysqli_query($con, "SELECT * FROM broadway_catalog WHERE totalPerformances BETWEEN $min AND $max ORDER BY totalPerformances") or die(mysqli_error($con));
			$header = "Total performances between: $min - $max<br>";
			$header .= "<span style=\"font-size: 20px;\"><em>From lowest to highest</em></span>";
		}
	}
} else {
	$result = mysqli_query($con, "SELECT * FROM broadway_catalog ORDER BY title $limstring") or die(mysqli_error($con));
	$totalResult = mysqli_query($con, "SELECT * FROM broadway_catalog") or die(mysqli_error($con));
	$header = "List of all Broadway shows";
}
?>
<div class="col-md-12 well">
	<div class="col-md-8">
		<h1 class="page-header"><?php echo $header;?></h1>
		<h6>
			<?php
				if ($searchterm != "" || isset($displayby)) {
					if (mysqli_num_rows($result) > 1 || mysqli_num_rows($result) == 0) {
						$subheading = mysqli_num_rows($result)." results found";
					} else {
						$subheading = mysqli_num_rows($result)." result found";
					}
					// if((isset($subheading) && isset($displayby)) || (isset($subheading) && isset($searchterm)))
					echo $subheading;
				} else {
					$subheading = mysqli_num_rows($totalResult)." shows in total";
					echo $subheading;
				}
				 ?>
		</h6>
		<hr>
		<?php
			if (isset($searchterm) && $searchterm == ""):
				echo "Please enter a valid search term.";
			else:
				if (isset($searchterm) && mysqli_num_rows($result) == 0):
					echo "No results for ".$searchterm;
				// elseif (isset($displayby) && isset($displayvalue) && mysqli_num_rows($result) == 0):
				// 	echo "0 results for ".ucwords($displayvalue);
				else:
					// $subheading = mysqli_num_rows($result)."results found.";
					while($row = mysqli_fetch_array($result)):
							$title = $row['title'];
							$filename = $row['filename'];
							$showid = $row['showid']; ?>
		<div class="col-md-4" style="margin-bottom: 40px;">
			<h4 class="col-md-10 row" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?php echo $title ?></h4>
			<a href="display.php?showid=<?php echo $showid ?>"><img src="admin/thumbs/<?php echo $filename ?>" class="img-thumbnail"></a>
		</div>
			<?php
					endwhile;
			 	endif;
			endif;?>
		<?php if (!isset($displayby) && !isset($searchterm)):?>
		<nav class="col-md-12">
			<ul class="pagination pull-right">
				<?php
				if($postnum > $limit):
					$n = $pg + 1;
					$p = $pg - 1;
					$thisroot = $_SERVER['PHP_SELF'];

					if($pg > 1): ?>
				<li class="page-item"><a class="page-link" href="<?php echo "$thisroot?pg=$p" ?>">&laquo;</a><li>
					<?php
					else: ?>
				<li class="page-item disabled"><a class="page-link" href="#">&laquo;</a><li>
					<?php
					endif;

					for($i = 1; $i <= $num_pages; $i++):
						if($i != $pg): ?>
				<li class="page-item"><a class="page-link" href="<?php echo "$thisroot?pg=$i" ?>"><?php echo $i ?></a></li>
					<?php
						else: ?>
				<li class="page-item active"><a class="page-link" href="#"><?php echo $i ?></a></li>
					<?php
						endif;
					endfor;

					if($pg < $num_pages): ?>
				<li class="page-item"><a class="page-link" href="<?php echo "$thisroot?pg=$n" ?>">&raquo;</a><li>
					<?php
					else: ?>
				<li class="page-item disabled"><a class="page-link" href="#">&raquo;</a><li>
					<?php
					endif;
				endif; ?>
			</ul>
		</nav>
		<?php endif; ?>
	</div>

	<div class="col-md-4 sidebar">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" class="col-md-12">
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