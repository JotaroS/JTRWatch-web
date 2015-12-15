<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<!-- 軸の属性を決めている。特にfill:none;にしないと真っ黒になる。-->
<style>
 
body {
  font: 15px sans-serif;
}
 
.axis path,
.axis line {
  fill: none;
  stroke: #000;
  shape-rendering: crispEdges;
}
 
.x.axis path {
  display: none;
}
 
.line {
  fill: none;
  stroke: steelblue;
  stroke-width: 3px;
}
 
</style>
<body>
</head>


<body>    
	<p>JTRWatch</p>
	<p>Input value here....hahaha
		<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
			<input type="text" name="timestamp"><br><br>
			<input type="submit" name="btn1" value="Submit">
		</form>

		<?php
		date_default_timezone_set('UTC');
		$timestamp = $_POST['timestamp'];
		$value = date('Y-h:i:s');


		$data = $value.",".$timestamp."\n";

		$keijban_file = 'keijiban.txt';

		$fp = fopen($keijban_file, 'ab');
		echo date('Y-h:i:s');
		if ($fp){
			if (flock($fp, LOCK_EX) && $_SERVER["REQUEST_METHOD"] == "POST"){
				if (fwrite($fp,  $data) === FALSE){
					print('File Write failed.may be due to not post method.');
				}

				flock($fp, LOCK_UN);
			}else{
				print('failed to lock file.');
			}
		}

		fclose($fp);

		?>
		<script src="http://d3js.org/d3.v3.min.js"></script>

	<script>
 
var margin = {top: 20, right: 20, bottom: 30, left: 50},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;
 
var parseDate = d3.time.format("%X").parse;
 
var x = d3.time.scale()
    .range([0, width]);
 
var y = d3.scale.linear()
    .range([height, 0]);
 
var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");
 
var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");
 
var line = d3.svg.line()
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.close); })
    .interpolate("cardinal");
 
var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
 
d3.csv("data.csv", function(error, data) {
  // データの読み込み
  data.forEach(function(d) {
    d.date = parseDate(d.date);
    d.close = +d.close;
  });
   console.log(data);
  // x軸の値設定
  x.domain(d3.extent(data, function(d) { return d.date; }));
  // y軸の値設定
  y.domain(d3.extent(data, function(d) { return d.close; }));
 
  // x軸の追加
  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  // y軸の追加
  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Price ($)");
       
  // 折れ線を追加
  svg.append("path")
      .datum(data)
      .attr("class", "line")
      .attr("d", line);
});
 
</script>

</body>
</html>
