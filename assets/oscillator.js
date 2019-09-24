var $jQ = jQuery.noConflict();
window.requestAnimFrame = (function(){
	return  window.requestAnimationFrame       ||
			window.webkitRequestAnimationFrame ||
			window.mozRequestAnimationFrame    ||
			window.oRequestAnimationFrame      ||
			window.msRequestAnimationFrame     ||
			function (callback) { window.setTimeout(callback, 1000 / 180); };
})();

var c = document.getElementById('oscillator');
var $jQ = c.getContext('2d'),
	w, h;

c.width = w = window.innerWidth * 0.9;
c.height = h = window.innerHeight * 0.4;

//circuits
var circ1 = new circ(),
	circ2 = new circ(),
	circ3 = new circ(),
	hor = h * 0.5;  //horizon(height from bottom)
	cnt = 180,  //count
	pace = Math.ceil(w / cnt),  
	buffer = new ArrayBuffer(cnt * 8),
	pts = new Float32Array(buffer);

circ1.max = h * 0.5;  //max wave height
circ1.max = h * c1fix;  //fixed data
circ2.max = 10;  //max wave height
circ2.sp = 0; //sp is speed
circ3.max = 10; //max wave height
circ3.sp = 0;

function fill() {
	for(var i = 0; i < cnt; i++) { 
		pts[i] = blend(circ1, circ2, circ3);
	}
}
fill();
$jQ.lineWidth = 5;
$jQ.strokeStyle = 'hsla(1, 0%, 95%, 1)';
$jQ.fillStyle = 'hsla(0, 0%, 0%, 1)';

function go() {
	var i;
	/// move  left
	for(i = 0; i < cnt - 1; i++) {
		pts[i] = pts[i + 1];
	}
	/// get a new point
	pts[cnt - 1] = blend(circ1, circ2, circ3);
	$jQ.fillRect(0, 0, w, h);
	/// render wave
	$jQ.beginPath();
	$jQ.moveTo(0, pts[0]);
	for(i = 1; i < cnt; i++) {
		$jQ.lineTo(i * pace, pts[i]);
	}
	$jQ.stroke();
	window.requestAnimFrame(go);
}
go();

/// oscillator
function circ() {
	this.vary = 0.9;
	this.max = 50;
	this.sp = 0.1;
	
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

function blend() {
	var args = arguments.length,
		i = args,
		sum = 0;
	if (args < 1) return 0;
	while(i--) sum += arguments[i].Amp();
	return sum / args + hor;
}