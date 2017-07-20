	<!-- Start encapsed pagination content -->
	<div class="col-lg-12">
		<ul class="pagination page-numbers">
	
<?php
	// for previous page list element display
	// get the previous element url
	$cururl=isset($outs['num_url']['prev_url'])?$outs['num_url']['prev_url']:"##";
	// get the previous element page number
	$curpagenum=isset($outs['num_url']['prev_pagenum'])?$outs['num_url']['prev_pagenum']:1;	

	if($showprevbtn==true){
		$cclass=$outs['current_page']==$curpagenum||
				!isset($outs['num_url']['prev_pagenum'])?" disabled":"";
?>
	<?php 
		// this is where the multiple type of outputs can be attended to
		if($b_options['paginationtype']=="default"){
	?>
			<li class="<?php echo $cclass;?>">
				<a class="next page-numbers " 
					href="<?php echo $cururl;?>">
					<i class="fa fa-chevron-left"></i>
				</a>
			</li>
	<?php
			// end if
		}
	?>

<?php
		// end if
	}
?>

<?php
	// for page list display
	for ($i=0; $i < $outs['num_pages']; $i++) { 
		# code...
		$cururl=$outs['num_url'][$i];
		$curpagenum=$outs['pagenumbers'][$i];	
		// create the current class option for the list if the list element being
		// created carries the current page
		$cclass=$outs['current_page']==$curpagenum?" current":"";
?>
	<?php 
		if($b_options['paginationtype']=="default"){

	?>
		<li class="<?php echo $cclass;?>">
			<a class="page-numbers" 
			href="<?php echo $cururl;?>"><?php echo $curpagenum;?></a>
		</li>
	<?php
		}
	?>
<?php
	}
?>

<?php
	// for next page list element display
	$cururl=isset($outs['num_url']['next_url'])?$outs['num_url']['next_url']:"##";
	$curpagenum=isset($outs['num_url']['next_pagenum'])?$outs['num_url']['next_pagenum']:1;	

	if($shownextbtn==true){
		$cclass=$outs['current_page']==$curpagenum||
				!isset($outs['num_url']['next_pagenum'])?" disabled":"";
?>
	<?php 
		if($b_options['paginationtype']=="default"){

	?>
		<li class="<?php echo $cclass;?>">
			<a class="next page-numbers" href="<?php echo $cururl;?>">
				<i class="fa fa-chevron-right"></i>
			</a>
		</li>
	<?php
		}
	?>

<?php
	}
?>
<?php
	// for all content page list element display, this would rende all the database 
	// items on one page i.e pulls all content at once
	if($showallbtn==true){
		$cururl=isset($outs['num_url']['all_url'])?$outs['num_url']['all_url']:"##";
?>
		<li><a class="page-numbers" href="<?php echo $cururl?>">
				<i class="fa fa-list"></i> All
			</a>
		</li>
<?php
	}
?>
	</ul>
</div>
	<!-- end encapsed pagination content -->