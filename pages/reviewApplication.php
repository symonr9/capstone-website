<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once PUBLIC_FILES . '/includes/header.php' ?>
	<title>Review Applications</title>
</head>

<?php include_once PUBLIC_FILES . '/db/dbManager.php' ?>
<?php include_once PUBLIC_FILES . '/modules/redirect.php' ?>

<?php
	$applicationID;
	if (isset($_GET['id'])) {
		$applicationID = $_GET['id'];
		$result = getApplication($applicationID);
		$row = $result->fetch_assoc();

		//Validate User.
		if(($row['user_id'] == $_SESSION['userID']) || (array_key_exists("accessLevel", $_SESSION) && $_SESSION['accessLevel'] == "Admin")){
			$validUserCredentials = true;
			
			//Retrieve all relevant application and project information here.
			$justification = $row['justification'];
			$time_available = $row['time_available'];
			$skill_set = $row['skill_set'];
			$external_link = $row['external_link'];
			$user_id = $row['user_id'];
			$project_id = $row['project_id'];
			
			$title = $row['title'];
			$description = $row['description'];
			$motivation = $row['motivation'];
			$objectives = $row['objectives'];
			$minQualifications = $row['minimum_qualifications'];
			$prefQualifications = $row['preferred_qualifications'];
			
			$firstName = $row['first_name'];
			$lastName = $row['last_name'];
			
			//Retrieve existing application review data and style page accordingly.
			$reviewResult = getApplicationReviewEntry($applicationID);
			$reviewRow = $reviewResult->fetch_assoc();
			
			$comments = $reviewRow['comments'];
			switch($reviewRow['interest_level']){
				case 'Desirable':
					$desirableBtnClass = "btn-success";
					$impartialBtnClass = "btn-outline-secondary";
					$undesirableBtnClass = "btn-outline-warning";
					break;
				case 'Impartial':
					$desirableBtnClass = "btn-outline-success";
					$impartialBtnClass = "btn-secondary";
					$undesirableBtnClass = "btn-outline-warning";
					break;
				case 'Undesirable':
					$desirableBtnClass = "btn-outline-success";
					$impartialBtnClass = "btn-outline-secondary";
					$undesirableBtnClass = "btn-warning";
					break;
				default: 
					$desirableBtnClass = "btn-outline-success";
					$impartialBtnClass = "btn-outline-secondary";
					$undesirableBtnClass = "btn-outline-warning";
					break;
			}
		}
		else{
			//Redirect if user is not allowed to visit this page.
			header("Location: ./index.php");
			exit();
		}

	}else {
		header("Location: ../"); /* Redirect browser */
		exit();
	}
?>

<body>
	<?php include_once '../modules/navbar.php' ?>

	<div class="container-fluid">
		<br>
		<div class="row">
			<div class="col-sm-1">	
				<br><br><br><br><br>
				<br><br><br><br><br>
				<br><br><br><br><br>
				<br><br><br><br>
				<a href="./myApplications.php"><button class="btn btn-lg btn-outline-primary">Back</button></a>
			</div>
			<div class="col-sm-6">
				<div class="scrollShorter jumbotron capstoneJumbotron">
					<!-- Display application data. -->
					<?php 
						echo '<h3>Application ' . $row['application_id'] . '</h3>';
						echo '<h4>By ' . $firstName . ' ' . $lastName . '</h4>';
						echo '<br>';
						echo '<h4>Justification:</h4>' . $justification . '';
						echo '<br><br>';
						echo '<h4>Skill Set:</h4> ' . $skill_set . '';
						echo '<br><br>';
						echo '<h4>Time Available:</h4> ' . $time_available . '';
						echo '<br><br>';
						echo '<h4>External Link:</h4> <a href="' . $external_link . '" target="_blank">' . $external_link . '</a>';
					?>
			
				</div>
				<center>
					<?php
					if(array_key_exists("userID",$_SESSION) && $_SESSION['accessLevel'] == 'Admin'){

						$result = applicantOtherProjects($user_id, $project_id);
						$row = $result->fetch_assoc();
						$otherProjects = $row["GROUP_CONCAT(title)"];
						if ($comments != ""){
							echo('<h6 style="float:left;">Proposer Comments About Applicant: '.$comments.'</h6>');
						}
						if ($otherProjects != ""){
							echo('<h6 style="float:left;">Other Projects Applicant has Applied For: '.$otherProjects.'</h6>');
						}

						echo('
						<br>
						<br>
						<br>

						<!-- Each buttons class is dynamically generated so that the selected one will be filled whereas the other two will have "outline" property. -->
						<h6 style="float:center;">Assign Desirability To Applicant:</h6>
						<button class="btn btn-lg '.$desirableBtnClass.' id="desirableBtn">Desirable</button>
						<button class="btn btn-lg '.$impartialBtnClass.' id="impartialBtn">Impartial</button>
						<button class="btn btn-lg '.$undesirableBtnClass.' id="undesirableBtn">Undesirable</button>
						');
					}
					else {
						echo('
						<div id="successText" class="successText" style="display:none;">Successfully rated application!</div>
						<form>
							<div class="form-group">
								<h6 style="float:left;">Put Comments About Applicant Here (Not Required*):</h6>
								<textarea class="form-control" id="commentsText" rows="2"> '.$comments.' </textarea>
							</div>
						</form>
						<!-- Each buttons class is dynamically generated so that the selected one will be filled whereas the other two will have "outline" property. -->
						<h6 style="float:center;">Assign Desirability To Applicant:</h6>
						<h6 style="float:left;">*Assigning desirability in no way indicates that you will have an applicant chosen/not chosen.  All applicants will in the end be chosen by the admins*</h6>
						<button class="btn btn-lg '.$desirableBtnClass.' id="desirableBtn">Desirable</button>
						<button class="btn btn-lg '.$impartialBtnClass.' id="impartialBtn">Impartial</button>
						<button class="btn btn-lg '.$undesirableBtnClass.' id="undesirableBtn">Undesirable</button>
						');
					}?>
				</center>
			</div>
			<div class="col-sm-1">
			</div>
			<div class="col-sm-4">
				<div class="scroll jumbotron capstoneJumbotron">
					<!-- Display project data. -->
					<?php
					echo '
											<br>
						<h2>'. $title .'</h2> 
						<p>'. $description .'</p>
						<br><br>
						<h5>Motivation:</h5>
						<p>'. $motivation .'</p>
						<h5>Objectives:</h5>
						<p>'. $objectives .'</p>
						<h5>Minimum Qualifications:</h5>
						<p>'. $minQualifications .'</p>
						<h5>Preferred Qualifications:</h5>
						<p>'. $prefQualifications .'</p>
						';
					?>
				</div>
			</div>
		</div>
	</div>
		
	<?php include_once PUBLIC_FILES . '/modules/footer.php' ?>
</body>

<script type="text/javascript">
function createSaveText(){
	document.getElementById('successText').style.display = "block";
	setTimeout(fade_out, 2000);
}

function fade_out(){
	$("#successText").fadeOut();
}



function createApplicationReview(actionType){
	applicationID = <?php echo $row['application_id']; ?>;
	comments = $('#commentsText').val();

	$.ajax({
		type: 'POST',
		url: '../db/dbManager.php',
		dataType: 'html',
		data: {
				applicationID: applicationID,
				comments: comments,
				action: actionType},
				success: function(result)
				{
					createSaveText();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(xhr.responseText);
					alert(thrownError);
				}
	});
}

$('#desirableBtn').on('click', function(e){
	createApplicationReview('desirableApplication');
});

$('#impartialBtn').on('click', function(e){
	createApplicationReview('impartialApplication');
});

$('#undesirableBtn').on('click', function(e){
	createApplicationReview('undesirableApplication');
});

</script>

</html>