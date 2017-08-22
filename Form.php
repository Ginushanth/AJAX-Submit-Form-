<?php


session_start();
//Connection parameters
$server="";
$username="";
$password="";
$database="";
$table="";



/*---------------Table contact_info------------*/
//Input data
$name=$email=$company=$comments=$submitDate=$ipAddress="";
//Error messages
$_SESSION['nameErr']=$_SESSION['emailErr']=$_SESSION['commentsErr']="";
//Check to see if all data is validated for security purposes before inserting into database
$emailSucc=$companySucc=true;//Email and Company are not mandatory
$nameSucc=$commentsSucc=false;//Name and comments are mandatory


//Create connection
$conn= new mysqli($server,$username,$password,$database);

if ($conn->connect_error)
{
	die("Failed to connect: " . $conn->connect_error);
}



//If the form was submitted using jquery
if (isset($_POST['message-submit']))//Check to see if the URL parameter contains the submit button has been clicked
{
	if (empty($_POST['name']))//Name has not been filled in
	{
		$_SESSION['nameErr']="*Enter your name";
		$nameSucc=false;
	}
	else
	{
		if (preg_match("/^[a-zA-Z\s]+$/",$_POST['name']))//Check to see if there is only letters and white space
		{
			$name=input_data($_POST['name']);
			$nameSucc=true;
		}
		else
		{
			$nameSucc=false;
			$_SESSION['nameErr']="*Letters only";
		}
	}
	//Check to see if the user inputted an email as this is optional, if it is not empty collect the data
	if (!empty($_POST['email']))
	{
		$email=input_data($_POST['email']);
		//Sanitize Email
		$email=filter_var($email, FILTER_SANITIZE_EMAIL);
		//Validate email
		if(!filter_var($email,FILTER_VALIDATE_EMAIL))
		{
			$_SESSION['emailErr']="*Not a valid email";
			$emailSucc=false;
		}
		else
		{
			$emailSucc=true;
		}
	}
	
	if (isset($_POST['company']))//If the company field is filled in, store the entry
	{
		$company=input_data($_POST['company']);
	}
	
	//Check to see if comments is blank or not
	if(empty($_POST['comments']))
	{
		$_SESSION['commentsErr']="*Cannot be empty";
		$commentsSucc=false;
	}
	else
	{
		$comments=input_data($_POST['comments']);
		$commentsSucc=true;
	}

	//This data is not submitted by the user
	$submitDate=date('Y-m-d',time());
	$ipAddress=(string)$_SERVER['REMOTE_ADDR'];
}
	
//Return any error messages and allow jquery to process these error messages so it can display it in HTML
echo $_SESSION['nameErr'];
echo ' ';
echo $_SESSION['emailErr'];
echo ' ';
echo $_SESSION['commentsErr'];	

//Inputs are all validated
if ($nameSucc==true && $emailSucc==true && $companySucc==true &&$commentsSucc==true && $_SERVER['REQUEST_METHOD']=='POST')
{
	//Prepare SQL Statements to prevent SQL Injections
	$sql=$conn->prepare("INSERT INTO $table (Name,Email,Company,Comments,SubmitDate,IP_Address) VALUES (?,?,?,?,?,?)");
	$sql->bind_param("ssssss",$name,$email,$company,$comments,$submitDate,$ipAddress);
	$sql->execute();
	//Reset session variables
	$_SESSION['nameErr']="";
	$_SESSION['emailErr']="";
	$_SESSION['commentsErr']="";
	
}
//Close connection
$conn->close();


//Remove unwanted characters in the input data
function input_data($data)
{
	$data=trim($data);
	$data=stripslashes($data);
	$data=htmlspecialchars($data);
	$data=mysqli_real_escape_string($GLOBALS['conn'],$data);
	return $data;
}

?>