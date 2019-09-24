<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Dra Pulido | EKG Control</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
		
		<link rel="stylesheet" href="../assets/styles.css">
	</head> 
	<body>

		<div class="container">
			<div class="row">
				<div class="range_container panel panel-primary">
  					<div class="panel-heading">
  						<span class="title">CF</span> 
  						<span id="value-c1" class="value pull-right"></span>
  					</div>
  					
  					<div class="panel-body">
						<div class="range_input_container">
							<input class="range_input" type="range" name="c1" id="c1"" min="0" max="180" step="1" 
							onchange="showRangeValueC1(this.value)" 
							oninput="showRangeValueC1(this.value)" 
							value="0">
						</div>
  					</div>
				</div>

				<div class="range_container panel panel-primary">
  					<div class="panel-heading">
  						<span class="title">SPO<small>2</small></span> 
  						<span id="value-c2" class="value pull-right"></span>
  					</div>
  					
  					<div class="panel-body">
						<div class="range_input_container">
							<input class="range_input" type="range" name="c2" id="c2"" min="0" max="100" step="1" 
							onchange="showRangeValueC2(this.value)" 
							oninput="showRangeValueC2(this.value)" 
							value="0">
						</div>
  					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			var rangeC1 = document.getElementById('value-c1');
			function showRangeValueC1(rangeValueC1) {
				rangeC1.innerHTML = rangeValueC1;
			}
			var rangeC2 = document.getElementById('value-c2');
			function showRangeValueC2(rangeValueC2) {
				rangeC2.innerHTML = rangeValueC2;
			}

			$(document).ready(function(e){
				<?php
				$c1=fopen("../data/c1.txt","r") or exit();
				$c2=fopen("../data/c2.txt","r") or exit();
				?>
				c1Init = <?php while (!feof($c1)){echo fgetc($c1);}?>;
				c2Init = <?php while (!feof($c2)){echo fgetc($c2);}?>;
				<?php 
				fclose($c1);
				fclose($c2);
				?>

				$("#value-c1").html(c1Init);
				$("#c1").val(c1Init);
				$("#value-c2").html(c2Init);
				$("#c2").val(c2Init);

				$('#c1').change(function(e){
					c1 = $(this).val();
					$.post("process_input.php", {c1: c1}, function(result){
						$("#value-c1").html(result);
						$("#c1").val(result);
					});

				})
				$('#c2').change(function(e){
					c2 = $(this).val();
					$.post("process_input.php", {c2: c2}, function(result){
						$("#value-c2").html(result);
						$("#c2").val(result);
					});
				})
			})
		</script>
	</body>
</html>