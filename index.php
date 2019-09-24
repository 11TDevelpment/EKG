<!-- <!DOCTYPE html> -->
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="assets/styles.css">
		<style type="text/css">
			.scope_cont{
				display: table;
				text-align: center;
				width: 100%;
				height: 100%;
			}
			.scope_cont_inner{
				display: table-cell;
				vertical-align: middle;
				width: 100%;
			}
			#oscillator{
				width: 100%;
				height: 80%;
			}
			#spectrum{
				width: 100%;
				height: 80%;
			}
		</style>

	</head> 
	<body>

		<div id="viewer">
			<div id="up_graph">
				<div class="scope_cont">
					<div class="scope_cont_inner">
						<div id="canvas_container">
							<canvas id="spectrum"></canvas>
						</div>
					</div>
				</div>
			</div>
			<div id="up_data">
				<div class="data_cont">
					<div class="data_cont_inner">
						<div class="title">HR <i id="heart_beat" class="fa fa-heart" aria-hidden="true"></i></div>
						<div id="c1"></div>
					</div>			
				</div>
			</div>
			<div id="down_graph">
				<div class="scope_cont">
					<div class="scope_cont_inner">
						<div id="canvas_container">
							<canvas id="oscillator"></canvas>
						</div>
					</div>
				</div>
			</div>
			<div id="down_data">
				<div class="data_cont">
					<div class="data_cont_inner">
						<div class="title">Spo<small>2</small></div>
						<div id="c2"></div>	
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			var $jq = jQuery.noConflict();
			var c1fix = "0";
			var c2fix = "0";

				function getDataFromProcessSpectrum() {	
					c1 = 'TRUE';
					$jq.post("control/process_output.php", {c1: c1}, function(result){
						duration = parseInt(100) - parseInt(result);
						if (duration < parseInt(10)) {duration = "0.5s"} else if (duration == parseInt(100)) {duration = "0s"} else {duration = "1."+duration+"s"};
						document.getElementById("heart_beat").style.animationDuration = duration;
						$jq("#c1").html(result);
						c1fix = result/100;
					});

				}

				function getDataFromProcessOscillator() {	

					c2 = 'TRUE';
					$jq.post("control/process_output.php", {c2: c2}, function(result){
						$jq("#c2").html(result);
						c2fix = result/100;
					});

				}		
		</script>
		
		<script type="text/javascript">
			var $jQ = jQuery.noConflict();
			window.requestAnimFrame = (function(){

			return  window.requestAnimationFrame       || window.webkitRequestAnimationFrame ||
					window.mozRequestAnimationFrame    || window.oRequestAnimationFrame      ||
					window.msRequestAnimationFrame     ||

					function (callback) { 
						window.setTimeout(callback, 1000 / 180);
					};

			})();

			/* **************** Declare var Spectrum **************** */

			var spec 	 = document.getElementById('spectrum');
			var $jQspec  = spec.getContext('2d'),spectWidth, spectHeight;

			spec.width  = spectWidth = window.innerWidth * 0.9;
			spec.height = spectHeight = window.innerHeight * 0.4;

			//Circuits spectrum
			var spectCirc1  = new circ();
			var	spectCirc2  = new circ();
			var	spectCirc3  = new circ();

			var	spectHor    = spectHeight * 0.5;  //horizon(height from bottom)
			var	spectCnt    = 180;      //count
			var	spectPace   = Math.ceil(spectWidth / spectCnt);  
			var	spectBuffer = new ArrayBuffer(spectCnt * 8);
			var	spectPts    = new Float32Array(spectBuffer);

			spectCirc1.max  = spectHeight * 0.5;  //max wave height
			spectCirc2.max  = 10;       		  //max wave height
			spectCirc2.sp   = 0;        		  //sp is speed
			spectCirc3.max  = 10;       		  //max wave height
			spectCirc3.sp   = 0;

			/* ******** Declare var Spectrum */


			/* **************** Declare var Oscillator **************** */

			var osci     = document.getElementById('oscillator');
			var $jQosci  = osci.getContext('2d'),osciWidth, osciHeight;

			osci.width  = osciWidth = window.innerWidth * 0.9;
			osci.height = osciHeight = window.innerHeight * 0.4;

			//Circuits oscillator
			var osciCirc1  = new circ();
			var	osciCirc2  = new circ();
			var	osciCirc3  = new circ();

			var	osciHor    = osciHeight * 0.5;  //horizon(height from bottom)
			var	osciCnt    = 180;      //count
			var	osciPace   = Math.ceil(osciWidth / osciCnt);  
			var	osciBuffer = new ArrayBuffer(osciCnt * 8);
			var	osciPts    = new Float32Array(osciBuffer);

			osciCirc1.max  = osciHeight * 0.5;  //max wave height
			osciCirc2.max  = 10;       			//max wave height
			osciCirc2.sp   = 0;        			//sp is speed
			osciCirc3.max  = 10;       			//max wave height
			osciCirc3.sp   = 0;

			/* ******** Declare var Oscillator */


			/* **************** fill **************** */

			function fill() {

				for(var i = 0; i < spectCnt; i++) { 
					spectPts[i] = blendSpectrum(spectCirc1, spectCirc2, spectCirc2);
				}

				for(var i = 0; i < osciCnt; i++) { 
					osciPts[i] = blendOscillator(osciCirc1, osciCirc2, osciCirc3);
				}

				
			}
			fill();
			$jQspec.lineWidth   = 5;
			$jQspec.strokeStyle = 'hsla(1, 0%, 95%, 1)';
			$jQspec.fillStyle   = 'hsla(0, 0%, 0%, 1)';

			$jQosci.lineWidth   = 5;
			$jQosci.strokeStyle = 'hsla(1, 0%, 95%, 1)';
			$jQosci.fillStyle   = 'hsla(0, 0%, 0%, 1)';

			/* ******** fill */


			/* **************** go **************** */

			function go() {

				getDataFromProcessSpectrum();
				getDataFromProcessOscillator();
				spectCirc1.max = spectHeight * c1fix;//fixed data Spectrum
				osciCirc1.max  = osciHeight * c2fix;//fixed data Oscillator
				
				var i;

				/// Move left Spectrum
				for(i = 0; i < spectCnt - 1; i++) {
					spectPts[i] = spectPts[i + 1];
				}

				/// Move left Oscillator
				for(i = 0; i < osciCnt - 1; i++) {
					osciPts[i] = osciPts[i + 1];
				}

				/// get a new point Spectrum
				spectPts[spectCnt - 1] = blendSpectrum(spectCirc1, spectCirc2, spectCirc2);
				$jQspec.fillRect(0, 0, spectWidth, spectHeight);

				/// get a new point Oscillator
				osciPts[osciCnt - 1] = blendOscillator(osciCirc1, osciCirc2, osciCirc3);
				$jQosci.fillRect(0, 0, osciWidth, osciHeight);


				/// render wave Spectrum
				$jQspec.beginPath();
				$jQspec.moveTo(0, spectPts[0]);

				/// render wave Oscillator
				$jQosci.beginPath();
				$jQosci.moveTo(0, osciPts[0]);

				/// LineTo Spectrum
				for(i = 1; i < spectCnt; i++) {
					$jQspec.lineTo(i * spectPace, spectPts[i]);
				}

				/// LineTo Oscillator
				for(i = 1; i < osciCnt; i++) {
					$jQosci.lineTo(i * osciPace, osciPts[i]);
				}

				$jQspec.stroke();
				$jQosci.stroke();
				window.requestAnimFrame(go);
			}
			go();

			/* ******** go */


			/* **************** Oscillator circ **************** */

			function circ() {

				this.vary = 0.9;
				this.max  = 50;
				this.sp   = 0.1;
				
				var it = this,
					a = 0,
					max = Max();
				this.Amp = function() {
					a += this.sp;
					if (a >= 2.0) {
						a = 0;
						max = Max();
					}
					return max * Math.sin(a * Math.PI);
				}
				function Max() {
					return Math.random() * it.max * it.vary +
						it.max * (1 - it.vary);
				}
				return this;    
			}

			/* ******** Oscillator circ */


			/* **************** Oscillator blend **************** */
			
			function blendSpectrum() {
				var args = arguments.length,
					i = args,
					sum = 0;

					// console.log(hor);

				if (args < 1) return 0;
				while(i--) sum += arguments[i].Amp();
				return sum / args + spectHor;
			}

			/* ******** Oscillator blend */	


			/* **************** Oscillator blend **************** */
			
			function blendOscillator() {
				var args = arguments.length,
					i = args,
					sum = 0;

					// console.log(hor);

				if (args < 1) return 0;
				while(i--) sum += arguments[i].Amp();
				return sum / args + osciHor;
			}

			/* ******** Oscillator blend */			
		</script>



	</body>
</html>
