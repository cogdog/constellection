<script>

var graphnodes = <?php echo $starnodes?>
  
var graphlinks = <?php echo $starlinks?>

var universeDiv = document.getElementById("universe");
var svg = d3.select(universeDiv).append("svg");


function draw(){

	// Extract the width and height that was computed by CSS.
	var width = universeDiv.getBoundingClientRect().width;
	var height = universeDiv.getBoundingClientRect().height;
	
	var scale_factor = width / 960;
	
	// Use the extracted size to set the size of an SVG element.
	svg
	  .attr("width", width)
	  .attr("height", height)
	  .attr("class", "starmap");

	var simulation = d3.forceSimulation()
		.force("link", d3.forceLink().id(function(d) { return d.id; }).distance(function(d) { return d.dist; }).strength(0.1))
		.force("charge", d3.forceManyBody())
		.force("center", d3.forceCenter( width / (3*scale_factor),  height / (2*scale_factor)));

	// remove existing nodes and lines (if we need to redraw)
	d3.selectAll("g").remove();
	d3.selectAll("circle").remove();
	d3.selectAll("text").remove();
	d3.selectAll("title").remove();
	
	// draw links
	var link = svg.append("g")
	  .attr("class", "links")
	.selectAll("line")
	.data(graphlinks)
	.enter().append("line")
	  .attr("stroke-width", function(d) { return Math.sqrt(d.value); });

	// create nodes, with hyperlinks
	  var node = svg.append("g")
		  .attr("class", "nodes")
		.selectAll("g")
		.data(graphnodes)
		.enter().append("g")
		.on('click', function(d, i) {window.open(d.url, '_blank');})

   
     // draw stars
	  var circles = node.append("circle")
		  .attr("r", function(d) { return d.size; })
		  .attr("fill", function(d) { return d3.rgb('#ffffff') })
		  .on("mouseover", function(d){d3.select(this).style("fill","orange");})
		  .on("mouseout", function(d){d3.select(this).style("fill","white");})
		  .call(d3.drag()
			  .on("start", dragstarted)
			  .on("drag", dragged)
			  .on("end", dragended));

	 
	  var labels = node.append("text")
		  .text(function(d) {
			return d.title;
		  })
		  .attr("class", "labels")
		  .style("fill", "#cccccc")
		  .attr('x', 20)
		  .attr('y', 8)
		  .on("mouseover", function(d){d3.select(this).style("fill","orange");})
		  .on("mouseout", function(d){d3.select(this).style("fill","#cccccc");});

		  
	  node.append("title")
		  .text(function(d) { return d.id; }) 
		  .on('click', function(d, i) {window.open(d.url, '_blank');}); 

 		// Add a Show/Hide for labels
		svg.append("text")
			.attr("x", width-100)             
			.attr("y", height - 30)    
			.attr("class", "legend")
			.style("fill", "#666666")         
			.on("click", function(){
				// Determine if current line is visible
				var active   = labels.active ? false : true ,
				  newOpacity = active ? 0 : 1;
				// Hide or show the elements
				d3.selectAll(".labels").style("opacity", newOpacity);
				// Update whether or not the elements are active
				labels.active = active;
			})
			.text("+/-");	  
			
	  simulation
		  .nodes(graphnodes)
		  .on("tick", ticked);

	  simulation.force("link")
		  .links(graphlinks);

	  function ticked() {
		link
			.attr("x1", function(d) { return d.source.x * scale_factor; })
			.attr("y1", function(d) { return d.source.y * scale_factor; })
			.attr("x2", function(d) { return d.target.x * scale_factor; })
			.attr("y2", function(d) { return d.target.y * scale_factor; });

		node
			.attr("transform", function(d) {
			  return "translate(" + d.x * scale_factor + "," + d.y * scale_factor + ")";
			})
	  }

 
	  function dragstarted(d) {
		 if (!d3.event.active) simulation.alphaTarget(0.8).restart();
		  d.fx = d.x;
		  d.fy = d.y;
		}

		function dragged(d) {
		  d.fx = d3.event.x;
		  d.fy = d3.event.y;
		}

		function dragended(d) {
		 if (!d3.event.active) simulation.alphaTarget(0);
		  d.fx = null;
		  d.fy = null;
		}
}

// Draw for the first time to initialize.
draw();

// Redraw based on the new size whenever the browser window is resized.
window.addEventListener("resize", draw);
</script>