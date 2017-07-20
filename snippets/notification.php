<?php 
session_start();
include('./snippets/connection.php');
$activemainlink8="activemainlink";
$tid="no trans id";
$fid="no file id";
$dquery="Noquery found";
if(isset($_SESSION['transaction_id'])&&$_SESSION['transaction_id']!==""){
	$tid=$_SESSION['transaction_id'];
	$dquery=$_SESSION['noquery'];
	$fid=$_SESSION[''.$tid.''];
}
if(isset($_POST['transaction_id'])){
	$merchant_id="2528-0026209";
	$tid=$_POST['transaction_id'];
	//get the full transaction details as an json from voguepay
	$json = file_get_contents('https://voguepay.com/?v_transaction_id='.$_POST['transaction_id'].'&type=json');
	//create new array to store our transaction detail
	$transaction = json_decode($json, true);
	
	/*
	Now we have the following keys in our $transaction array
	$transaction['merchant_id'],
	$transaction['transaction_id'],
	$transaction['email'],
	$transaction['total'], 
	$transaction['merchant_ref'], 
	$transaction['memo'],
	$transaction['status'],
	$transaction['date'],
	$transaction['referrer'],
	$transaction['method']
	*/
	$fid=$_GET['fid'];
	$tid=$_POST['transaction_id'];
	$_SESSION['transaction_id']=$_POST['transaction_id'];
	$_SESSION[''.$tid.'']=$_GET['fid'];
	$email=$transaction['email'];
	$price=$transaction['total'];
	$vstatus=$transaction['status'];
	$stub=md5($fid);
	$_SESSION['stub_download']=$host_addr."snippets/download.php?stub=$stub";
	$date= date("Y-m-d")."";
	$enddate=date('Y-m-d', strtotime('10 days'));
	$query="INSERT INTO transaction(voguerefid,amountpaid,email,fileid,stublink,startdate,enddate,voguestatus)VALUES
	('$tid','$price','$email','$fid','$stub','$date','$enddate','$vstatus')";
	// echo $query;
	$_SESSION['noquery']=$query;
	if($transaction['total'] == 0)header('location:failure.php?er=total&msg=Invalid Amount&tid='.$tid.'');
	if($transaction['status'] !== 'Approved')header('location:failure.php?er=status&msg=Unapproved transaction&tid='.$tid.'');
	// if($transaction['merchant_id'] !== $merchant_id){header('location:failure.php?er=mid&msg=Invalid Merchant&tid='.$tid.'');}

	$run=mysql_query($query)or die(mysql_error()." Line 757");
	/*You can do anything you want now with the transaction details or the merchant reference.
	You should query your database with the merchant reference and fetch the records you saved for this transaction.
	Then you should compare the $transaction['total'] with the total from your database.*/
	header('location:transactionsuccess.php');
}

?>
