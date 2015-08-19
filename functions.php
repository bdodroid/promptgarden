<html>
<?php 
require_once("database.php");
//takes a  promptId, and gets the comments for that prompt.
function getComments($database,$promtId){
	$query = "select * from 'comments' as c where c.id =
		(select * from promptToComments' as pc where pc.promptId ='$promptId')
		order by 'theDate description;";
	$result=$database->doQuery($query);
	return $result;
}
//fills the tables if they are empty
function fillUsersTable($database){
	$nameBase="Neal-";
	$passwordBase="password";
	$bioBase="this is me.";
	
	for($i=0; $i<50; $i++){
		$name = $nameBase.strval($i);
		$password = $passwordBase.$i;
		$month = $i%12;
		$month++;
		$joinedDate=strtotime("2014/{$month}/01");
		$points = $i;
		$bio = $bioBase.$i;
		///insert query
		$query = "insert into users(name,password,joinDate,points,bio) 
			values('$name','$password',$joinedDate,$points,'$bio');";
		$result = $database->doQuery($query);
	}
}
function fillPromptsTable($database){
	$usersI = $database->doQuery("select id from users;");
	$users = array();
	while($row=mysqli_fetch_array($usersI)){
		$users[]=$row[0];//append the id to our users array.
	}
	$descriptionBase = "This is my test prompt. It is medium length. number:";
	$catagoryBase = "Catagory#";
	$dateBase="01/";
	for($i=0;$i<100;$i++){
		$userId = $users[$i%count($users)];//index to wrap overflow.
		$description=$descriptionBase.$i;
		$day = $i%27;
		$day++;
		$d = new DateTime($dateBase.$day."/2010");
		$theDate = $d->format('U');
		echo"date:".$d->format('m/d/Y');
		echo"===";
		echo $theDate;
		echo"   Original ==".$dateBase.$day."/2010";
		echo "<br>";
		$catagory = $catagoryBase.$i;
		$points = $i;
		$color = "#1E2DF0";
		$query = "insert into writingPrompts(userId,description,color,theDate,catagory,points)
			 values($userId,'$description','$color',$theDate,'$catagory',$points);";
		$result = $database->doQuery($query);
	}
	$query = "insert into writingPrompts(userId,description,color,theDate,catagory,points)
			values('1','this should have a texture.','tree','999999999999999','none','1');";
	$result = $database->doQuery($query);
}
//creates all the prompt garden database tables if they dont exist.
function createAll($database){
	$Query = [];
	$Query[]="create table if not exists writingPrompts(
		id mediumint not null auto_increment,
		userId mediumint not null,
		description varchar(320) not null,
		color varchar(30) not null,
		theDate int not null,
		catagory varchar(32) not null,
		points int not null,
		primary key(id));" ;
	$Query[]="create table if not exists users(
		id mediumint not null auto_increment,
		name varchar(32) not null,
		password varchar(320),
		joinDate int not null,
		points int not null,
		bio varchar(600),
		primary key(id));" ;
	$Query[] ="create table if not exists comments(
		id mediumint not null auto_increment,
		userId mediumint not null,
		description varchar(240) not null,
		points mediumint not null,
		theDate int not null,
		primary key(id));" ;
	$Query[] = "create table if not exists promptToComments(
		id mediumint not null auto_increment,
		promptId mediumint not null,
		commentId mediumint not null,
		primary key(id));";
	$Query[] = "create table if not exists commentToComments(
		id mediumint not null auto_increment,
		parentCommentId mediumint not null,
		childCommentId mediumint not null,
		primary key(id));";
	$Query[]="create table if not exists userFavorites(
		id mediumint not null auto_increment,
		userId mediumint not null,
		promptId mediumint not null,
		primary key(id));";
	$Query[]="create table if not exists userPromptLikes(
		id mediumint not null auto_increment,
		userId mediumint not null,
		promptId mediumint not null,
		primary key(id));";
	$Query[]="create table if not exists userCommentLikes(
		id mediumint not null auto_increment,
		userId mediumint not null,
		commentId mediumint not null,
		primary key(id));";
	foreach($Query as $q){
		$database->doQuery($q);
	}
}
function dropAll($database){
	$database->doQuery("drop table i