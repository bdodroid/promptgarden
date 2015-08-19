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
			/////////////////////////////
			//variables
			/////////////////////////////
			var promptAPI = "promptGardenAPI.php";
			var globalColor1 = "#ffffff";
			var globalColor2 = "#ffffff";
			var milliseconds = Math.floor((new Date).getTime()/1000);
			var showCards = 5;
			var activeCards = 0;
			var sortCatagory = "none";
			var sortBy = "newest";
			var curModal = 0;
			var submitCatagory = "none";
			/////////////////////////////
			//initialization
			/////////////////////////////
			$( document ).ready(function() {
				jQuery('#newPrompt').hide();
				jQuery('#fullscreenPromptAdd').hide();

				$("#newPromptBtn").click(function () {
					jQuery('#newPrompt').show();
					jQuery('#fullscreenPromptAdd').show();
					jQuery('#newPromptBtn').hide();
				});
				$(".button-collapse").sideNav();
				
				GetCards();
				
				/////////////////////////////
				//form submition
				/////////////////////////////
				$('form').submit(function(event) {
					$('#debugOUT').html('submet?');
					$.post(promptAPI,
						{
						  type: "prompt",
						  action: "PUT",
						  userId: "1",
						  date: milliseconds,
						  catagory: submitCatagory,
						  color: globalColor1 + ',' + globalColor2,
						  desc: $('textarea[name=desc]').val()
						},
						function(data){

							var objects = data;
							$('#debugOUT').html('submet?');
						},
							"json"
						);
					//event.preventDefault();
				});
			
			});
			/////////////////////////////
			//functions
			/////////////////////////////
			function GetCards(){
				$.post(promptAPI,
					{
						type: "prompt",
						action: "GET",
						id: "1",
						catagory: sortCatagory,
						sort: sortBy,
						where: milliseconds
					},
					function(data){
						for(var i in data){
							if(i <= showCards){
								$('#promptsContainer').append('<div id="cardContainer'+i+'" class="col s12 m6 offset-m3" style="text-shadow: -0.3px 0 white, 0 0.3px white, 0.3px 0 white, 0 -0.3px white;"><div id="card'+i+'" class="card white" style=""><div class="card-content"><span class="card-title black-text"></span><p><span id="cardContent'+i+'" class="activator hoverable flow-text">'+data[i].description+'</span></p></div><div class="card-action"><!-- Modal Trigger --><a class="waves-effect waves-light btn modal-trigger" onclick="OpenModal('+i+','+data[i].id+');">'+data[i].commentCount+' Comments</a><span id="cardName'+i+'">'+ data[i].name +'</span></div><div class="card-reveal"><span class="card-title grey-text text-darken-4">Title <i class="material-icons right">close</i></span><p>Here is some more information about this produ.</p></div> <!-- Modal Structure --></div></div>');
//								$('#promptsContainer').append('<div id="cardContainer'+i+'" class="col s12 m6 offset-m3" style="text-shadow: -0.3px 0 white, 0 0.3px white, 0.3px 0 white, 0 -0.3px white;"><div id="card'+i+'" class="card white" style=""><div class="card-content"><span class="card-title black-text"></span><p><span id="cardContent'+i+'" class="activator hoverable flow-text">'+data[i].description+'</span></p></div><div class="card-action"><!-- Modal Trigger --><a class="waves-effect waves-light btn modal-trigger" href="#modal'+i+'">12 Comments</a><span id="cardName'+i+'">'+ data[i].name +'</span></div><div class="card-reveal"><span class="card-title grey-text text-darken-4">Title <i class="material-icons right">close</i></span><p>Here is some more information about this produ.</p></div> <!-- Modal Structure --><div id="modal'+i+'" class="modal bottom-sheet"><div class="modal-content left-align"> <h4>Comments</h4><ul class="collection"> <li class="collection-item avatar"><i class="material-icons circle blue">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle green">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li><li class="collection-item avatar"><i class="material-icons circle red">play_arrow</i><span class="title">Title</span><p>First Line <br>Second Line</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a</li></ul></div></div></div></div>');
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
					},
						"json"
				);
			//	$('#debugOUT').html(sortCatagory);
			}
			
			function AddCards(){
				$.post(promptAPI,
					{
						type: "prompt",
						action: "GET",
						id: "1",
						catagory: sortCatagory,
						sort: sortBy,
						where: milliseconds
					},
					function(data){
						var tmpMaxCards = activeCards + 5;
						for(var i in data){
							if(i >= activeCards && i <= tmpMaxCards){
								$('#promptsContainer').append('<div id="cardContainer'+i+'" class="col s12 m6 offset-m3" style="text-shadow: -0.3px 0 white, 0 0.3px white, 0.3px 0 white, 0 -0.3px white;"><div id="card'+i+'" class="card white" style=""><div class="card-content"><span class="card-title black-text"></span><p><span id="cardContent'+i+'" class="activator hoverable flow-text">'+data[i].description+'</span></p></div><div class="card-action"><!-- Modal Trigger --><a class="waves-effect waves-light btn modal-trigger" onclick="OpenModal('+i+','+data[i].id+');">'+data[i].commentCount+' Comments</a><span id="cardName'+i+'">'+ data[i].name +'</span></div><div class="card-reveal"><span class="card-title grey-text text-darken-4">Title <i class="material-icons right">close</i></span><p>Here is some more information about this produ.</p></div> <!-- Modal Structure --></div></div>');
//									
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
					},
						"json"
				);
			}
			
			function ClearCards(changeGenre, sortby, genre){
				ClearModals();
				jQuery('#promptsContainer div').html('');
				activeCards = 0;
				//$('#debugOUT').html(changeGenre);
				if(changeGenre == 'true'){
				//	$('#debugOUT').html('changing genre');
					sortCatagory = genre;
				}else{
				//	$('#debugOUT').html("changing sort");
					sortBy = sortby;
				}
				GetCards();
			}
			
			function OpenModal(number, prmtID){
				$('#modalContainer').append('<div id="modal'+number+'" class="modal bottom-sheet"><div class="modal-content left-align"> <h4>Comments</h4><ul class="collection"><span id="modalContainerContent'+number+'"></span></ul></div></div>');
				
					$.post(promptAPI,
					{
						type: "promptComment",
						action: "GET",
						id: prmtID,
						userId: "-1"
					},
					function(data){
						//var div = document.getElementById('modal'+number+'');	
						
						for(var i in data){
						//	$('#debugOUT').html('modal'+number+'');
						//	div.innerHTML = div.innerHTML + '';
							$('#modalContainerContent'+number).append('<li class="collection-item avatar"><i class="material-icons circle blue">play_arrow</i><span class="title">Username</span><p>'+data[i].description+'</p><a href="#!" class="secondary-content"><i class="material-icons">grade</i></a></li>');
						 }
						
						
					},
						"json"
				);
				
				$('#modal'+ number).openModal();
			}
			function ClearModals(){
				jQuery('#modalContainer div').html('');
			}
			//change color of the prompt creation box
			function ChangeCardColor(color1, color2){
			//	$('#debugOUT').html("color!");
				$('#newPrompt').attr('style', 'right: 10px; bottom: 0px; width: 30%; background: -webkit-linear-gradient(' + color1 + ', ' + color2 + ');');
				globalColor1 = color1;
				globalColor2 = color2;
			}
			
			function SortCards(sortby){
				var main = document.getElementById( 'promptsContainer' );

				[].map.call( main.children, Object ).sort( function ( a, b ) {
					return +a.id.match( /\d+/ ) - +b.id.match( /\d+/ );
				}).forEach( function ( elem ) {
					main.appendChild( elem );
				})
				
			}
			function SelectCatagory(btnName){
				submitCatagory = btnName.toLowerCase();
				$('#catagoryTag').html(btnName);
			}
			
			///////////////////////////
			//on event
			///////////////////////////
			$(window).scroll(function () {
				if ($(document).scrollTop() == $(document).height() - $(window).height()) {
					AddCards();
				}
			});
		</script>
	</head>
	<body class="teal" style="overflow-x: hidden;">
		<div class="navbar-fixed">
			<nav>
				<div class="nav-wrapper teal">
					<span class="hide-on-small-only">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/leafwhite.png" /></span><a href="http://promptgarden.com" class="brand-logo left">PromptGarden</a>
					<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>

					<ul id="nav-mobile" class="right hide-on-med-and-down">
						<li><a class="dropdown-button" href="#!" data-activates="sort">Sort By</a></li>
						<li><a></a></li>
						<li><a class="dropdown-button" href="#!" data-activates="genre">Catagory</a></li>
						<li><a></a></li>
						<li><a></a></li>
						<li><a></a></li>
					</ul>
					<ul class="side-nav" id="mobile-demo">
						<li><a >Sort By</a></li>
						<li class="divider"></li>
						<li><a onclick="ClearCards('false','newest',' ')" >Newest</a></li>
						<li><a onclick="ClearCards('false','liked',' ')" >Liked</a></li>
						<li><a onclick="ClearCards('false','popular',' ')" >Popular</a></li>
						<li class="divider"></li>
						<li><a>Genre</a></li>
						<li class="divider"></li>
						<li><a onclick="ClearCards('true',' ','none')" >All</a></li>
						<li><a onclick="ClearCards('true',' ','fantasy')" >Fantasy</a></li>
						<li><a onclick="ClearCards('true',' ','horror')" >Horror</a></li>
						
					</ul>
				</div>
				<!-- Dropdown Structure -->
				<ul id='sort' class='dropdown-content'>
					<li><a onclick="ClearCards('false','newest',' ')" >Newest</a></li>
					<li><a onclick="ClearCards('false','liked',' ')" >Liked</a></li>
					<li><a onclick="ClearCards('false','popular',' ')" >Popular</a></li>
				</ul>
				<ul id='genre' class='dropdown-content'>
					<li><a onclick="ClearCards('true',' ','none')" >All</a></li>
					<li><a onclick="ClearCards('true',' ','fantasy')" >Fantasy</a></li>
					<li><a onclick="ClearCards('true',' ','horror')" >Horror</a></li>
				</ul>
				<ul id="submitGenre" class="dropdown-content">
					<li><a onclick="ClearCards('true',' ','none')" >None</a></li>
					<li><a onclick="ClearCards('true',' ','fantasy')" >Fantasy</a></li>
					<li><a onclick="ClearCards('true',' ','horror')" >Horror</a></li>
				</ul>
			</nav>
		</div>
		<div id="debugOUT"></div>
		<div class="row center teal">
			<div class="container" id="promptsContainer"></div>
			<div id="modalContainer"></div>
			
			<div id="modal" class="modal bottom-sheet">
				<div class="modal-content left-align"> 
					<h4>Comments</h4>
					<ul class="collection"> 
						<li class="collection-item avatar"><i class="material-icons circle blue">play_arrow</i><span class="title">Title</span>
							<p>First Line <br>Second Line</p>
							<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
						</li>
						<li class="collection-item avatar"><i class="material-icons circle">play_arrow</i><span class="title">Title</span>
							<p>First Line <br>Second Line</p>
							<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
						</li>
						<li class="collection-item avatar"><i class="material-icons circle green">play_arrow</i><span class="title">Title</span>
							<p>First Line <br>Second Line</p>
							<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
						</li>
						<li class="collection-item avatar"><i class="material-icons circle red">play_arrow</i><span class="title">Title</span>
							<p>First Line <br>Second Line</p>
							<a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
						</li>
					</ul>
				</div>
			</div>
			<div id="newPromptBtn" class="fixed-action-btn" style="bottom: 45px; right: 24px;">
				<a class="btn-floating btn-large red"><i class="large material-icons" onclick="">add</i></a>
			</div>
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
								onclick="ChangeCardColor('#ffffff','#ffffff')">
							Pick
						</button>
						<button class="waves-effect waves-light btn" 
								style="background: -webkit-linear-gradient(#d6cc91,#ff8100);" 
								onclick="ChangeCardColor('#d6cc91','#ff8100')">
							Pick
						</button>
						<button class="waves-effect waves-light btn" 
								style="background: -webkit-linear-gradient(#00e5e5,#40e0d0);" 
								onclick="ChangeCardColor('#00e5e5','#40e0d0')">
							Pick
						</button>
						<button class="waves-effect waves-light btn" 
								style="background: -webkit-linear-gradient(#b0c3e7,#e7f8f9);" 
								onclick="ChangeCardColor('#b0c3e7','#e7f8f9')">
							Pick
						</button>
						<button class="waves-effect waves-light btn" 
								style="background: -webkit-linear-gradient(#262626,#fffdd6);" 
								onclick="ChangeCardColor('#262626','#fffdd6')">
							Pick
						</button>
						<button class="waves-effect waves-light btn" 
								style="background: -webkit-linear-gradient(#9781c3,#d63b3b);" 
								onclick="ChangeCardColor('#9781c3','#d63b3b')">
							Pick
						</button>
					</span>
				</div>
				<div id="catagory">
					<div id="catagoryTag">
						Select a Catagory
					</div>
					<button id="catBtnNone" class="waves-effect waves-light btn" onclick="SelectCatagory('None')">None</button>
					<button id="catBtnHorror" class="waves-effect waves-light btn" onclick="SelectCatagory('Horror')">Horror</button>
					<button id="catBtnFantasy" class="waves-effect waves-light btn" onclick="SelectCatagory('Fantasy')">Fantasy</button>
				</div>
				<div class="card-reveal">
					<span class="card-title grey-text text-darken-4">Stuff <i class="material-icons right">close</i></span>
					<p>Here is some more information about this produ.</p>
				</div>
			</div>
		</div>
	</body>
</html>