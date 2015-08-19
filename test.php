  <html>
    <head>
	<!--Import materialize.css-->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      	<link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		 <!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.js"></script>
		
      <script type="text/javascript" src="js/materialize.min.js"></script>
		
	  </head>
	  <body>
		   <h2> example </h2>
  <h3>Output: </h3>
  <div id="output">this element will be accessed by jquery and this text replaced</div>

	  <script id="source" language="javascript" type="text/javascript">
		 $(document).ready(function(){
			 
			$.ajax({                                      
			  url: 'http://www.shadowtopstudios.com/shadowtopstudios/promptGardenAPI.php',                  //the script to call to get data          
			  data: "type=prompt&action=GET&id=1&date=1262329200",//you can insert url argumnets here to pass to api.php
											   //for example "id=5&parent=6"
			  dataType: 'json',                //data format      
			  success: function(data)          //on recieve of reply
			  {
				  data = JSON.parse(data);
				var name = data[0].name;              //get id
				var content = data[0].description;           //get name
				//--------------------------------------------------------------------
				// 3) Update html content
				//--------------------------------------------------------------------
				 $('#output').html("<b>Name: </b>"+name+"<b> Content: </b>"+content); //Set output element html

				//recommend reading up on jquery selectors they are awesome 
				// http://api.jquery.com/category/selectors/
			  }, 
				error: function(XMLHttpRequest, textStatus, errorThrown) {
				
		  }
			});
		  }); 
	  </script>
		<script id="source" language="javascript" type="text/javascript">
			$(document).ready(function(){
				$("button").click(function(){
					$.post("../shadowtopstudios/promptGardenAPI.php",
					{
					  type: "prompt",
					  action: "GET",
						id: "1",
						date: "1262329200"
					},
					function(data){
						alert("Name: " + data[0].name + "\nDescription: " + data[0].description);
					},
						"json"
					);
				});
			});
		 </script>	
		  <button>
			 Click ME DAMNIT! 
		  </button>
	  </body>
</html>