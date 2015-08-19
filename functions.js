<!-- Add materializeCSS ready functions here -->
<script>
//	$(document).ready(function(){
		
//	  $('.parallax').parallax();
//	$('.tabs-wrapper .row').pushpin({ top: $('.tabs-wrapper').offset().top });
//	});
	
</script>

<script>
	/*
$( document ).ready(function() {
	
	console.log( "ready!" );
	$(".dropdown-button").dropdown();
	$(".button-collapse").sideNav();
	$('.parallax').parallax();
	var milliseconds = Math.floor((new Date).getTime()/1000);
	//testing jQuery .post
	$.post("../promptGardenAPI.php",
			{
			  type: "prompt",
			  action: "GET",
				id: "1",
				date: milliseconds
			},
			function(data){
				var objects = data;

				for(var i in data){
					$('#jReturnContent' + i).html(data[i].description);
					$('#jReturnName' + i).html(data[i].name);
				 }

				alert("Name: " + data[0].name + "\nDescription: " + data[0].description);
			},
				"json"
			);
});
*/
</script>
<script>
	$(document).ready(function() {
		$(".dropdown-button").dropdown();
		$(".button-collapse").sideNav();
		$('.parallax').parallax();
		// process the form
		$('form').submit(function(event) {
			// get the form data

			// there are many ways to get this data using jQuery (you can use the class or id also)
			var formData = {
				'email'             : $('input[name=email]').val(),
			};

			// process the form
			$.ajax({
				type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
				url         : 'addToMailingList.php', // the url where we want to POST
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
</script>