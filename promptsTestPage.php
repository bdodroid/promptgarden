<?php 
$cards = 5;
?>
<html>
    <head>
      <!--Import materialize.css-->
		<link rel="shortcut icon" href="images/icons/favicon.ico">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      	<link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		 <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="jquery-2.1.4.min.js"></script>
		
      <script type="text/javascript" src="js/materialize.js"></script>
		<script>
		var promptAPI = "promptGardenAPI.php";
		var milliseconds = Math.floor((new Date).getTime()/1000);
		var showCards = 5;
		var activeCards = 0;
		var sortby = "none";
			
		$( document ).ready(function() {
		$('.modal-trigger').leanModal();
			$('form').submit(function(event) {
			
				$.post(promptAPI,
					{
					  type: "prompt",
					  action: "PUT",
					  userId: "1",
					  date: milliseconds,
					  catagory: "none",
					  color: globalColor1 + ',' + globalColor2,//					  color: "#ba98cd,#ff6501"
					  desc: $('textarea[name=desc]').val()
					},
					function(data){
						
						var objects = data;
						$('#fuck').html();
						//$("#fuck").append("<b>Appended text</b>");
							//alert("Name: " + color[0] + "\nDescription: " + color[1]);
							
							//search #aafaf,#2352,
						 
				 		
						//alert("Name: " + color[0] + "\nDescription: " + data[0].description);
					},
						"json"
					);
				//event.preventDefault();
			});
			
			jQuery('#newPrompt').hide();
			jQuery('#fullscreenPromptAdd').hide();
			
			$("#newPromptBtn").click(function () {
				jQuery('#newPrompt').show();
				jQuery('#fullscreenPromptAdd').show();
				jQuery('#newPromptBtn').hide();
			});
			
			//alert("the jquery is doing stuff");
			$(".dropdown-button").dropdown();
			$(".button-collapse").sideNav();
			$('.parallax').parallax();
			
			GetCards();
			//testing jQuery .post
		
		});
			function GetCards(){
			$.post(promptAPI,
					{
					  type: "prompt",
					  action: "GET",
						id: "1",
						catagory: sortby,
						where: milliseconds
					},
					function(data){
						
						var objects = data;
						
						for(var i in data){
				 			if(i <= showCards){
								
								$('#promptsContainer').append('<div id="cardContainer'+i+'" class="col s12 m6 offset-m3" style="text-shadow: -0.3px 0 white, 0 0.3px white, 0.3px 0 white, 0 -0.3px white;"><div id="card'+i+'" class="card white" style=""><div class="card-content"><span class="card-title black-text"></span><p><span id="cardContent'+i+'" class="activator hoverable flow-text">'+data[i].description+'</span></p></div><div class="card-action"><!-- Modal Trigger --><a class="waves-effect waves-light btn modal-trigger" href="#modal'+i+'">12 Comments</a><span id="cardName'+i+'">'+ data[i].name +'</span></div><div class="card-reveal"><span class="card-title grey-text text-darken-4">Title <i class="material-icons right">close</i></span><p>Here is some more information about this produ.</p></div> <!-- Modal Structure --><div id="modal'+i+'" class="modal bottom-sheet"><div class="modal-content left-align"> <h4>Comments</h4><ul class="collection"> <li class="collection-item avatar"><i class="material-icons circle blue">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle green">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle red">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a</li></ul></div></div></div></div>');

								var tmp = data[i].color;

								if(tmp.indexOf('#') === -1){
									//image
									$('#card' + i).attr('style', 'background-image: url("images/promptBackgrounds/' + tmp + '.jpg");'); //' + tmp[0] + '""images/plant.png"
								}else{
									var color = tmp.split(",");
									$('#card' + i).attr('style', 'background: -webkit-linear-gradient(' + color[0] + ', ' + color[1] + ');');
								}
								
								activeCards += 1;
							}
						 }
				 		
						//alert("Name: " + color[0] + "\nDescription: " + data[0].description);
					},
						"json"
					);
			}
			/*
			$('#cardContent' + i).html(data[i].description);
							$('#cardName' + i).html(data[i].name);
							//data[i].color 
							var tmp = data[i].color;
							
							if(tmp.indexOf('#') === -1){
								//image
								$('#card' + i).attr('style', 'background-image: url("images/promptBackgrounds/' + tmp + '.png");'); //' + tmp[0] + '""images/plant.png"
							}else{
								var color = tmp.split(",");
								$('#card' + i).attr('style', 'background: -webkit-linear-gradient(' + color[0] + ', ' + color[1] + ');');
							}
							//alert("Name: " + color[0] + "\nDescription: " + color[1]);
							
							//search #aafaf,#2352,
			*/
		</script>
		<script>
				
				/*
			$(document).ready(function() {

				// process the form
				$('form').submit(function(event) {
					// get the form data
					
					// there are many ways to get this data using jQuery (you can use the class or id also)
					var formData = {
						'promptContent'             : $('input[name=promptContent]').val(),
					};

					// process the form
					$.ajax({
						type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
						url         : promptAPI, // the url where we want to POST
						data        : formData, // our data object
						dataType    : 'json', // what type of data do we expect back from the server
									encode          : true
					})
						// using the done promise callback
						.done(function(data) {
							//alert(data.email)
							// log data to the console so we can see
						//	console.log(data); 
							if(data.works){
								$('#submitReturn').addClass("green-text");
							}else{
								$('#submitReturn').addClass("red-text");
							}
							$('#submitReturn').html(data.email);
							// here we will handle errors and validation messages
						});

					// stop the form from submitting the normal way and refreshing the page
					event.preventDefault();
				});

			});
			*/
		</script>
		<script>
			var globalColor1 = "#ffffff";
			var globalColor2 = "#ffffff";
			
			function changeCardColor(color1, color2){
			//	$('#fuck').html("gaaaah!");
				$('#newPrompt').attr('style', 'right: 10px; bottom: 0px; width: 30%; background: -webkit-linear-gradient(' + color1 + ', ' + color2 + ');');
				globalColor1 = color1;
				globalColor2 = color2;
			}
		</script>
		<script>
			function addCards(){
				$.post(promptAPI,
					{
					  type: "prompt",
					  action: "GET",
						id: "1",
						catagory: sortby,
						where: milliseconds
					},
					function(data){
						
						var objects = data;
						var tmpMaxCards = activeCards + 5;
						
						for(var i in data){
				 			if(i >= activeCards && i <= tmpMaxCards){
								$('#promptsContainer').append('<div id="cardContainer'+i+'" class="col s12 m6 offset-m3"><div id="card'+i+'" class="card white" style=" background-size: 10% 100%;"><div class="card-content"><span class="card-title black-text"></span><p><span id="cardContent'+i+'" class="activator hoverable flow-text">'+data[i].description+'</span></p></div><div class="card-action"><!-- Modal Trigger --><a class="waves-effect waves-light btn modal-trigger" href="#modal1">12 Comments</a><span id="cardName'+i+'">'+ data[i].name +'</span></div><div class="card-reveal"><span class="card-title grey-text text-darken-4">Title <i class="material-icons right">close</i></span><p>Here is some more information about this produ.</p></div> <!-- Modal Structure --><div id="modal1" class="modal bottom-sheet"><div class="modal-content left-align"> <h4>Comments</h4><ul class="collection"> <li class="collection-item avatar"><i class="material-icons circle blue">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle green">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle red">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a</li></ul></div></div></div></div>');

								var tmp = data[i].color;

								if(tmp.indexOf('#') === -1){
									//image
									$('#card' + i).attr('style', 'background-image: url("images/promptBackgrounds/' + tmp + '.png");'); //' + tmp[0] + '""images/plant.png"
								}else{
									var color = tmp.split(",");
									$('#card' + i).attr('style', 'background: -webkit-linear-gradient(' + color[0] + ', ' + color[1] + ');');
								}
							}
						 }
				 		activeCards = tmpMaxCards;
						//alert("Name: " + color[0] + "\nDescription: " + data[0].description);
					},
						"json"
					);
			}
			function getDocHeight() {
				var D = document;
				return Math.max(
					D.body.scrollHeight, D.documentElement.scrollHeight,
					D.body.offsetHeight, D.documentElement.offsetHeight,
					D.body.clientHeight, D.documentElement.clientHeight
				);
			}
		$(window).scroll(function () {
		   if ($(document).scrollTop() == $(document).height() - $(window).height()) {
			  addCards();
		   }
		});
			function clearCards(sort){
			
				for(i = 0; i < activeCards; i++){
					var div = document.getElementById("cardContainer"+i);
					div.parentNode.removeChild(div);
					if(i == (activeCards - 1)){	
						activeCards = 0;
						sortby = sort;
						GetCards();
					}
				}
			}
		</script>
	</head>
	<body style="overflow-x: hidden;">
	<div class="navbar-fixed">
	 <!-- Dropdown Structure -->
	  <ul id='dropdown1' class='dropdown-content'>
		<li><a onclick="clearCards('none')" >All</a></li>
		<li class="divider"></li>
		  <li><a onclick="clearCards('fantasy')" >Fantasy</a></li>
		<li><a onclick="clearCards('horror')" >Horror</a></li>
	  </ul>
	<nav>
		<div class="nav-wrapper teal">
			<span class="hide-on-small-only">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/leafwhite.png" /></span><a href="http://promptgarden.com" class="brand-logo left">PromptGarden</a>
			<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>

			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a class="dropdown-button" href="#!" data-activates="dropdown1">Sort By</a></li>
			</ul>

			<ul class="side-nav" id="mobile-demo">
				<li><a >Sort By</a></li>
				<li class="divider"></li>
				<li><a onclick="clearCards('none')" >All</a></li>
				  <li><a onclick="clearCards('fantasy')" >Fantasy</a></li>
				<li><a onclick="clearCards('horror')" >Horror</a></li>
			</ul>
		</div>
	</nav>
</div>
	
		<div id="newPromptBtn" class="fixed-action-btn" style="bottom: 45px; right: 24px;">
			<a class="btn-floating btn-large red">
			  <i class="large material-icons" onclick="">add</i>
			</a>
			<ul>
			 
			</ul>
		</div>
		<!--
		<div id="fullscreenPromptAdd" class="pinned hide-on-med-and-up" style="width: 100%; height: 100%; background: black; z-index: 2;">
			<div id="fullPrompt" class=" card white" style="margin: auto auto auto auto;">
			<div class="card-content">
				<span class="card-title black-text">Make a New Prompt</span>
				<p>
					<span id="newCardContent" class="activator flow-text">
						<form id="postNewPrompt" class="col s12 m12">
							<div class="row">	   
								<div class="input-field col s12 m12">
								<textarea id="desc" name="desc" class="materialize-textarea"></textarea>
								<label for="icon_prefix">Start writing...</label>
								</div></div>
							<a class="waves-effect waves-light btn" onclick="$(this).closest('form').submit()"><i class="material-icons right">send</i>Post it!</a>
						</form>
					</span>
				</p>
			</div>
			<div class="card-action">
				<span id="newCardName">COLOR PICKING OPTIONS</span>
			</div>
			<div class="card-reveal">
				<span class="card-title grey-text text-darken-4">Stuff <i class="material-icons right">close</i></span>
				<p>Here is some more information about this produ.</p>
			</div>
			</div>
		</div>
		-->
		<div id="newPrompt" class="hide-on-med-and-down card white pinned" style="right: 10px; bottom: 0px; width: 30%;">
			<div class="card-content">
				<span class="card-title black-text">Make a New Prompt</span>
				<p>
					<span id="newCardContent" class="activator flow-text">
						<form id="postNewPrompt" class="col s12 m12">
							<div class="row">	   
								<div class="input-field col s12 m12">
								<textarea id="desc" name="desc" class="materialize-textarea"></textarea>
								<label for="icon_prefix">Start writing...</label>
								</div></div>
							<a class="waves-effect waves-light btn" onclick="$(this).closest('form').submit()"><i class="material-icons right">send</i>Post it!</a>
						</form>
					</span>
				</p>
			</div>
			<div class="card-action">
				<span id="newCardName">
					<button class="waves-effect waves-light btn" 
							style="background: -webkit-linear-gradient(#ffffff,#ffffff);" 
							onclick="changeCardColor('#ffffff','#ffffff')">
						Pick
					</button>
					<button class="waves-effect waves-light btn" 
							style="background: -webkit-linear-gradient(#d6cc91,#ff8100);" 
							onclick="changeCardColor('#d6cc91','#ff8100')">
						Pick
					</button>
					<button class="waves-effect waves-light btn" 
							style="background: -webkit-linear-gradient(#00e5e5,#40e0d0);" 
							onclick="changeCardColor('#00e5e5','#40e0d0')">
						Pick
					</button>
					<button class="waves-effect waves-light btn" 
							style="background: -webkit-linear-gradient(#b0c3e7,#e7f8f9);" 
							onclick="changeCardColor('#b0c3e7','#e7f8f9')">
						Pick
					</button>
					<button class="waves-effect waves-light btn" 
							style="background: -webkit-linear-gradient(#262626,#fffdd6);" 
							onclick="changeCardColor('#262626','#fffdd6')">
						Pick
					</button>
					<button class="waves-effect waves-light btn" 
							style="background: -webkit-linear-gradient(#9781c3,#d63b3b);" 
							onclick="changeCardColor('#9781c3','#d63b3b')">
						Pick
					</button>
				</span>
			</div>
			<div class="card-reveal">
				<span class="card-title grey-text text-darken-4">Stuff <i class="material-icons right">close</i></span>
				<p>Here is some more information about this produ.</p>
			</div>
					
		</div>
		<div class="row center teal">
			<div class="container" id="promptsContainer">
				<span id="fuck"></span>
			<?php
/*
		for ($i = 0; $i <= $cards; $i++) {
			echo '
				<div class="col s12 m6 offset-m3">
					<div id="card'.$i.'" class="card white" style="">
						<div class="card-content">
							<span class="card-title black-text"></span>
							<p>
								<span id="cardContent'.$i.'" class="activator hoverable flow-text"></span>
							</p>
						</div>
						<div class="card-action">
							<!-- Modal Trigger -->
 						<a class="waves-effect waves-light btn modal-trigger" href="#modal1">12 Comments</a>
						<span id="cardName'.$i.'"></span>
						</div>
						<div class="card-reveal">
							<span class="card-title grey-text text-darken-4">Title <i class="material-icons right">close</i></span>
							<p>Here is some more information about this produ.</p>
						</div>
						
						  <!-- Modal Structure -->
						  <div id="modal1" class="modal bottom-sheet">
							<div class="modal-content left-align">
							  <h4>Comments</h4>
							   <ul class="collection">
							  <li class="collection-item avatar">
								<i class="material-icons circle blue">play_arrow</i>
								<span class="title">Title</span>
								<p>First Line <br>
								   Second Line
								</p>
								<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
							  </li>
							  <li class="collection-item avatar">
								<i class="material-icons circle">play_arrow</i>
								<span class="title">Title</span>
								<p>First Line <br>
								   Second Line
								</p>
								<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
							  </li>
							  <li class="collection-item avatar">
								<i class="material-icons circle green">play_arrow</i>
								<span class="title">Title</span>
								<p>First Line <br>
								   Second Line
								</p>
								<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
							  </li>
							  <li class="collection-item avatar">
								<i class="material-icons circle red">play_arrow</i>
								<span class="title">Title</span>
								<p>First Line <br>
								   Second Line
								</p>
								<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
							  </li>
							</ul>
							</div>
							
						  </div>
					</div>
				</div>';
		}
		*/
			?>
			</div>
		</div>
	</body>
</html>
