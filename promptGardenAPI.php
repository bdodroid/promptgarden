<?php 
/*
 * -API for prompt garden.  Must supply a action, and a type 
 * types are camel cased always. Actions are all caps always.
 * -actions == GET, PUT, DELETE, UPDATE
 * -types == prompt, user, promptComment,promptLike,commentLike,promptUnlike,commentUnlike
 * 		commentBulkLike,commentBulkUnlike,promptFavorite,promptUnfavorite,userFavorites
 * 		userInfo,bioUpdate
 * -Though 'liking' results in an insert command,(inserting into the userLikes tables)
 * it adds points into previously created data, so I have decided they belong in the update
 * section.
 * -also, delete commands take precedence over update commands, even if a query chain requires an update,

 * -'favoriting, though, only adds to the user favorites table, so it belongs in the insert section.
 * created by neal 07/02/2015
 **********CHANGE LOG***************
 *07/18/2015 -Neal
 *-changed the query for getting prompts to use the newly created userPromptLikes table.
 *	IN: function promptGetQuery
 *-changed the query for comments to check for likes as well,
 *	IN: function promptCommentGetQuery
 *-filled out thhe promptPutQuery function to put a prompt into the database
 *prompts also now have the color attribute
 *	IN: function promptPutQuery
 *-created like functions for comments and prompts.
 *	IN: functions userPromptLikeQuery,userCommentLikeQuery
 *
 *07/20/2015 -Neal
 *-updated the query for getting prompts to get if an item is favorited
 *	IN: function promptGetQuery
 *-added a function for unliking a prompt
 *	IN: function promptUnlikeQuery
 *-added a function for unliking a comment
 *	IN: function commentUnlikeQuery
 *-filled out deleteAction stub to check for like or unlike actions on comments and prompts
 *	IN: function deleteAction
 *
 *07/27/2015 -Neal
 *-created function that returns queries for mass comment liking
 *	IN: function commentBulkLike
 *-created function that returns queries for mass comment unliking
 *	IN: function commentBulkUnlike
 *-updated the updateAction function to detect bulkComment likings, and unlikings
 *	IN: function updateAction
 *
 *07/28/2015 -Neal
 *-cleaned up debug code from the bulk update functons
 *	IN: functions commentBulkLike,commentBulkUnlike
 *-created bulk insert and update queires for efficiency
 *	IN: funcion commentBulkLike
 *   
 *07/31/2015 -Neal
 *-created a function for favoriting prompts
 *	IN: function promptFavoriteQuery
 *-created a function for unfavoriting prompts
 *	IN: function promptUnfavoriteQuery
 *-fixed typo  in calling name of a function
 *	IN: deleteAction
 *-created a function for getting a users favorites
 *	IN: function userFavoritesGetQuery
 *-added new types to the respective action functions
 *	IN: functions insertAction,deleteAction,getAction
 *
 *08/03/2015 -Neal
 *-created a function for getting the count of a users prompts,comments,points, bio, and username.
 *	IN: function userInfoQuery
 *-created a function for updating a users bio.
 *	IN: function bioUpdateQuery
 *-added types userInfo, and bioUpdate to the getAction, and updateAction functions respectivly
 *	IN: functions getAction,updateAction
 *-fixed a error in the userFavoritesGetQuery, where the queries array wasnt returned
 *	IN: function userFavoritesGetQuery
 *-users points are now updated where someone upvotes a comment, or a prompt
 *	IN: functions commentBulkLike, commentBulkUnlike, promptLikeQuery,promptUnlikeQuery
 *
 *08/04/2015 -Neal
 *-did tons of things.  Too tired to write them down. (shame)
 *
 *08/06/2015 -Neal
 *- the promptGetQuery() function has been updated to support catagories, and sorting options
 *	IN: function promptGetQuery
 *
 *08/15/15 -Neal
 *- added basic account creation.  You can now create an account, and get a userId back
 *	IN: function userInsertQuery
 *- added basic account sign ins.  You can sign into your account with the proper name and password.
 *	IN: function userSigninGetQuery
 *- created a sanatizing function in the database, and sanatized all input sent into the api
 *	IN: all functions
 */
require_once('database.php');
$GLOBALS['err']=false;

$database = new Database("webepira_promptGardenDB","webepira_neal");
$type = $_REQUEST['type'];
$action = $_REQUEST['action'];
if(!isset($type) || !isset($action)){
	$GLOBALS['err']=true;
	$output = array();
}else{//do the things
	if($action==="GET"){
		$output=getAction($database,$type);
	}else if($action==="PUT"){
		$output= putAction($database,$type);
	}else if($action ==="DELETE"){
		$output= deleteAction($database,$type);
	}else if($action ==="UPDATE"){
		$output=updateAction($database,$type);
	}else{
		$GLOBALS['err']=true;
		$output=array();
	}
}
if($database->isOpen()){
	$database->close();
}
//output will always be an array, and will always be defined.
encodeResult($output);
//END

function deleteAction($database,$type){
	if($type==="promptUnlike"){
		$query = promptUnlikeQuery($database);
	}else if($type ==="commentUnlike"){//we want to get comments for this prompt
		$query = commentUnlikeQuery($database);
	}else if($type ==="commentBulkUnlike"){
		$query = commentBulkUnlikeQuery($database);
	}else if($type ==="promptUnfavorite"){
		$query = promptUnfavoriteQuery($database);
	}
	else{//not a recognized type.
		$GLOBALS['err']=true;
	}
	//we should have our query, or the empty string now.
	//if we have an error set up to this point, bail out without performing the query.
	if($GLOBALS['err']){
		return array();
	}
	$database->open();
	$output=array();
	foreach($query as $q){
		$result = $database->doQuery($q);
		while($row=mysqli_fetch_assoc($result)){
			$output[]=$row;
		}
	}
	return $output;
}
//actions that insert into the database.
//commands are: "prompt","promptComment",
function putAction($database,$type){
	if($type==="prompt"){
		$query = promptPutQuery();
	}else if($type==="promptComment"){
		$query = promptCommentPutQuery($database);
	}else if($type ==="promptFavorite"){
		$query = promptFavoriteQuery($database);
	}else{//not a recognized type
		$GLOBALS['err']=true;
	}
	if($GLOBALS['err']){
		return array();
	}
	$testResult = array();
	$database->open();
	foreach($query as $q){
		$testResult[]=$q;
		$result = $database->doQuery($q);
	}
	//return array("success"=>true);
	return $testResult;
}
function getAction($database,$type){
	if($type==="prompt"){//we want to get writing prompts
		$query = promptGetQuery($database);
	}else if($type =="promptComment"){//we want to get comments for this prompt
		$query = promptCommentGetQuery($database);
	}else if($type ==="userFavorites"){
		$query = userFavoritesGetQuery($database);
	}else if($type ==="userInfo"){
		$query = userInfoQuery($database);	
	}else{//not a recognized type. 
		$GLOBALS['err']=true;
	}
	//we should have our query, or the empty string now.  
	//if we have an error set up to this point, bail out without performing the query.
	if($GLOBALS['err']){
		return array();
	}
	$database->open();
	$output=array();
	foreach($query as $q){
		$result = $database->doQuery($q);
		while($row=mysqli_fetch_assoc($result)){
			$output[]=$row;
		}
	}
	return $output;
}
//does update commands to the database. ex. liking comments,liking prompts,
//adding friends etc.  Note:  favoriting items are a 'PUT' command.
//Commands are:promptLike,commentLike
function updateAction($database,$type){
	if($type==="promptLike"){//we want to get writing prompts
		$query = promptLikeQuery($database);
	}else if($type ==="commentLike"){//we want to get comments for this prompt
		$query = commentLikeQuery($database);
	}else if($type ==="commentBulkLike"){//we want to like a lot of comments
		$query = commentBulkLikeQuery($database);
	}else if($type ==="bioUpdate"){
		$query = bioUpdateQuery($database);
	}else{//not a recognized type.
		$GLOBALS['err']=true;
	}
	//we should have our query, or the empty string now.
	//if we have an error set up to this point, bail out without performing the query.
	if($GLOBALS['err']){
		return array();
	}
	$database->open();
	$output=array();
	foreach($query as $q){
		$result = $database->doQuery($q);
		while($row=mysqli_fetch_assoc($result)){
			$output[]=$row;
		}
	}
	return $output;
}

/////////////////////////////////////////
/////////////GET QUERIES/////////////////
/////////////////////////////////////////

//returns a query for getAction to run, or the empty string
//client SHOULD send a -1 as userId if there is no user logged in.  However
//we will set the userId to -1 if the data isnt set in the request.
function promptGetQuery($database){
	$whereClause=$database->sanatizeString($_REQUEST['where']);
	$promptId = $database->sanatizeString($_REQUEST['id']);
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$sort = $database->sanatizeString($_REQUEST['sort']);
	$idList = $database->sanatizeString($_REQUEST['idList']);
	$catagory = $database->sanatizeString($_REQUEST['catagory']);
	if(!isset($whereClause) || !isset($promptId)){
		$GLOBALS['err']=true;
		return array();
	}
	if(!isset($userId)){
		$userId = -1;
	}
	//sort = newest,liked,popular.
	if(!isset($sort)){
		$sort = "newest";
		$whereClause ="9999999999999";
	}
	//this is the list of id's we have seen if we weren't given one, it will just be one id. Our last prompt
	if(!isset($idList)){
		$idList = $promptId;
	}
	//if the catagory is not set, or it is ==="none"  our catagory string will be empty
	if(!isset($catagory) || $catagory ==="none"){
		$catagoryString = "";
	}else{
		$catagoryString = "and p.catagory ='$catagory' ";
	}
	$queries=array();
	//this is our base query. we will need all this info
	$query="select distinct p.*,u.name,
		 if(pl.userId = '$userId',1,0) as liked,
		 if(uf.userId='$userId',1,0) as favorited,
		 count(pc.id) as commentCount
		  from
	writingPrompts as p
	left join userPromptLikes as pl
		on pl.promptId = p.id
	left join userFavorites as uf
		on uf.promptId = p.id
	inner join users as u
		on p.userId = u.id
	left join promptToComments as pc
		on p.id = pc.promptId 
	where p.id NOT IN($idList)";
	//append the catagory choice to the query
	$query.=$catagoryString;
	//sort by the date needing to be lower than the one give, and stuff can't be in this idList
	if($sort ==="newest"){
		$query.=" and theDate <= '$whereClause'
		group by p.id
		order by p.theDate desc limit 20;";
	//sort by the prompt that has the most points.
	}else if($sort ==="liked"){
		$query.=" and p.points <=$whereClause
				group by p.id 
				order by p.points desc limit 20;";
	//sort by the prompt that has the most comments.
	}else if($sort ==="popular"){
		$query .="
		group by p.id
		having commentCount <= $whereClause 
		order by commentCount desc limit 20;";
	}
	$queries[]=$query;
	//echo(json_encode($queries));
	return $queries;
}
//grabs the userId, or -1 when given a name and pass
function userIdGetQuery($database){
	$username= $database->sanatizeString($_REQUEST['username']);
	$password = $database->sanatizeString($_REQUEST['password']);
	if(!isset($username) || !isset($password)){
		$GLOBALS['err']=true;
		return array();
	}
	$hashedpass = crypt($password);
	$queries=[];
	$queries[]="select IFNULL(select userId from users where name='$username' and
		password = '$hashedpass' limit 1),-1);";
	return $queries;
}
//grabs all th eprompts a user has favorited and returns them.
function userFavoritesGetQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$idList = $database->sanatizeString($_REQUEST['idList']);
	if(!isset($userId)){
		$GLOBALS['err']=true;
		return array();
	}
	if(!isset($idList)){
		$idList="-1";
	}
	$queries = [];
	$queries[]="select distinct p.*,u.name,
		 if(pl.userId = '1',1,0) as liked,
		 if(uf.userId='1',1,0) as favorited,
		 count(pc.id) as commentCount
		  from
		writingPrompts as p
		left join userPromptLikes as pl
			on pl.promptId = p.id
		left join userFavorites as uf
			on uf.promptId = p.id
		inner join users as u
			on p.userId = u.id
		left join promptToComments as pc
			on pc.promptId = p.id
		where p.id NOT IN('-1') and u.id='1' and uf.userId = u.id 
			group by p.id 
			order by p.theDate desc;";
	return $queries;
}
//gets the count of prompts,comments,points,username, and the bio
function userInfoQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	if(!isset($userId)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = [];
	$queries[]="select count(distinct p.id) as prompts,count(distinct c.id) as comments,
		count(distinct uf.promptId) as favorites, u.points as points,
		u.name as name,u.bio as bio from
			writingPrompts as p
			inner join users as u
				on p.userId = u.id
			inner join comments as c
				on c.userId = u.id
			inner join userFavorites as uf
				on uf.userId = u.id
			where u.id = '$userId';";
	return $queries;	
}
function promptCommentGetQuery($database){
	$promptId = $database->sanatizeString($_REQUEST['id']);
	$userId = $database->sanatizeString($_REQUEST['userId']);
	if(!isset($promptId)){
		$GLOBALS['err']=true;
		return array();
	}
	if(!isset($userId)){
		$userId = -1;
	}
	$queries = array();
	$query = "select distinct c.*,u.name, if(cl.userId = '$userId',true,false) as liked from
			comments as c 
			left join userCommentLikes as cl
				on cl.commentId = c.id
			inner join users as u
				on c.userId = u.id
			inner join promptToComments as pc
				on c.id = pc.commentId
			inner join writingPrompts as p
				on p.id = pc.promptId
			where p.id = '$promptId'
			order by c.theDate desc;";
	$queries[]=$query;
	return $queries;
}

/////////////////////////////////////////////
///////////////INSERT QUERIES////////////////
/////////////////////////////////////////////

//returns queries needed to be run to the putAction() function
//queries result in the given prompt being put into the database.
//grab the userId,date,description,catagory,and color, insert a new prompt
//with 0 points into the database.
function promptPutQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$desc = $database->sanatizeString($_REQUEST['desc']);
	$theDate = $database->sanatizeString($_REQUEST['date']);
	$catagory = $database->sanatizeString($_REQUEST['catagory']);
	$color = $database->sanatizeString($_REQUEST['color']);
	if(!isset($userId)|| !isset($desc)|| !isset($theDate)|| !isset($catagory)|| !isset($color)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="insert into writingPrompts(userId,description,color,theDate,catagory,points)
			values('$userId','$desc','$color','$theDate','$catagory',0);";
	return $queries;	
}
//returns queries needed to be run to the putAction() function
//queries result in the given comment on a prompt being put in the database.
//NOTE: these comments are on prompts only.  They will insert into the promptComment table.
function promptCommentPutQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$desc = $database->sanatizeString($_REQUEST['desc']);
	$theDate = $database->sanatizeString($_REQUEST['date']);
	$promptId = $database->sanatizeString($_REQUEST['id']);
	if(!isset($userId)|| !isset($desc) || !isset($theDate) || !isset($promptId)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="insert into comments(userId,description,theDate,points)
				values('$userId','$desc','$theDate','0');";
	$queries[]="insert into promptToComments(promptId,commentId)
				values('$promptId',LAST_INSERT_ID());";
	return $queries;	
}
//inserts a promptId, and userId into the userFavorites table
function promptFavoriteQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$promptId = $database->sanatizeString($_REQUEST['promptId']);
	if(!isset($userId)|| !isset($promptId)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="insert into userFavorites(userId,promptId)
	values('$userId','$promptId');";
	return $queries;
}

///////////////////////////////////////////////
//////////////UPDATE QUERIES///////////////////
///////////////////////////////////////////////

//returns an array of queries to run to the updateAction() function
//queries result in the viewed comment being 'liked'
//add 1 point to the comment with the given id,
//add userId, and commentId to the userCommentLikes table
function commentLikeQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$commentId = $database->sanatizeString($_REQUEST['commentId']);
	if(!isset($userId)|| !isset($commentId)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="insert into userCommentLikes(userId,commentId)
	values('$userId','$commentId');";
	$queries[]="update comments set points = points+1
	where id = '$commentId';";
	return $queries;
}
//returns an array of queries to run to the updateAction() function
//queries result in the viewed prompt being 'liked'
//add points to the prompt with the given id,
//add userId,promptId, to the userPromptLikes table.
function promptLikeQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$promptId = $database->sanatizeString($_REQUEST['promptId']);
	if(!isset($userId)|| !isset($promptId)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="insert into userPromptLikes(userId,promptId)
			values('$userId','$promptId');";
	$queries[]="update writingPrompts set points = points+1
			where id = '$promptId';";
	$queries[]="update users set points = points+1
			where id = (select userId from writingPrompts where id = '$promptId');";
	return $queries;	
}
//function updates a user's bio info.
function bioUpdateQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$bio = $database->sanatizeString($_REQUEST['bio']);
	if(!isset($userId) || !isset($bio)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="update users set bio = '$bio' where id = '$userId';";
	return $queries;
}
//creates a bulk update query that will update all items in the given set.
function commentBulkLikeQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$commentIdsString = $database->sanatizeString($_REQUEST['commentIds']);
	if(!isset($userId) || !isset($commentIdsString)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$commentIdsArray = explode(",",$commentIdsString);
	//prepare the start of the insert statement.
	$insertStatement = "insert into userCommentLikes(userId,commentId)
	values('$userId','$commentIdsArray[0]')";
	foreach($commentIdsArray as $k=>$value){//add each item as an insert.
		if($k>0){
			$insertStatement.=",('$userId','$value')";
		}
	}
	$insertStatement.=";";//dont forget the terminator.

	$updateStatement = "update comments set points = points+1
	where id IN($commentIdsString);";
	//update the users points as well.
	$queries[]=$insertStatement;
	$queries[]=$updateStatement;
	//update each users points at that position.
	foreach($commentIdsArray as $k=>$value){
		$queries[]="update users as u
		inner join comments c
		on u.id = c.userId
		set u.points = u.points+1
		where c.id = '$value'; ";
	}
	///////////////////////////////////////////////
	// IF BULK INSERT IS CAUSE OF PROBLEMS, USE THIS
	///////////////////////////////////////////////
	//foreach($commentIdsArray as $value){
	//	$queries[]="insert into userCommentLikes(userId,commentId)
	//			values('$userId','$value');";
	//	$queries[]="update comments set points=points+1
	//			where id = '$value';";
	//}
	//only update the comments if they are in the list of commentIds

	//echo(json_encode($queries));
	return $queries;
}
/////////////////////////////////////////////
//////////////DELETE QUERIES////////////////
/////////////////////////////////////////////

// creates queires that will unlike a prompt, and subtracts the points from the writing prompt.
function promptUnlikeQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$promptId = $database->sanatizeString($_REQUEST['promptId']);
	if(!isset($userId)|| !isset($promptId)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="delete from userPromptLikes
	 	where userId = '$userId' and
	 	promptId = '$promptId';";
	$queries[]="update writingPrompts set points = points-1
		where id = '$promptId';";
	$queries[]="update users set points = points-1
			where id = (select userId from writingPrompts where id = '$promptId');";
	return $queries;
}
//creates queries that will unlike a comment and subtract the points from a comment
function commentUnlikeQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$commentId = $database->sanatizeString($_REQUEST['commentId']);
	if(!isset($userId)|| !isset($commentId)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="delete from userCommentLikes
	where userId = '$userId' and
	commentId = '$commentId';";
	$queries[]="update comments set points = points-1
	where id = '$commentId';";
	return $queries;
}
//creates a bulk update query that will unlike all comments in the given Id string.
//deletes from the userLikes table, so it belongs in delete.
function commentBulkUnlikeQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$commentIdsString = $database->sanatizeString($_REQUEST['commentIds']);
	if(!isset($userId) || !isset($commentIdsString)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	//prepare the start of the delete statement.
	$insertStatement = "delete from userCommentLikes
				where userId = '$userId' and commentId IN($commentIdsString);";
	//only update the comments if they are in the list of commentIds
	$updateStatement = "update comments set points = points-1
	where id IN($commentIdsString);";
	//append the queries the query array.
	$queries[]=$insertStatement;
	$queries[]=$updateStatement;
	//-1 from each comment.
	$commentIdsArray = explode(",",$commentIdsString);
	foreach($commentIdsArray as $k=>$value){
		$queries[]="update users as u
		inner join comments c
		on u.id = c.userId
		set u.points = u.points-1
		where c.id = '$value'; ";
	}
	//echo(json_encode($queries));
	return $queries;
}
//deletes a record from the userFavorites table
function promptUnfavoriteQuery($database){
	$userId = $database->sanatizeString($_REQUEST['userId']);
	$promptId = $database->sanatizeString($_REQUEST['prompt']);
	if(!isset($userId) || !isset($promptId)){
		$GLOBALS['err']=true;
		return array();
	}
	$queries = array();
	$queries[]="delete from userFavorites
				where userId = '$userId' and promptId = '$promptId';";
	return queries;
}
//encodes the error (true or false) into the result, echos out the data
function encodeResult($output){
	//$output["error"]=$GLOBALS['err'];
	echo(json_encode($output));
}



?>