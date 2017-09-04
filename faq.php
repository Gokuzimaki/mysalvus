<?php  
session_start();
include('snippets/connection.php');
// include('login.php');
$activepage7="active";
include($host_tpathplain.'themesnippets/mysalvussnippets/headcontentmysalvus.php');
// check to see if an faq id has been passed with the current url
$outputdata="";
$search="";
if(isset($_GET['qid'])&&$_GET['qid']>0){
	$singledata="single";
	$data['queryextra']=" status='active'";
	$qdata=getSingleFAQ($id,$data);
}elseif(isset($_GET['q'])&&$_GET['q']!==""){
	$singledata="group";
	$search=true;
	$datq=mysql_real_escape_string($_GET['q']);
	$data['queryextra']=" title LIKE '%$datq%' OR content LIKE '%$datq%'";
	$data['queryorder']=" ORDER BY title";
	$qdata=getAllFAQ("viewer","all","",$data);
}else{
	$singledata="group";
	$data['queryorder']=" ORDER BY title";
	$qdata=getAllFAQ("viewer","all","",$data);
}
?>
	<body class="">
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/toplinksmysalvus.php');
		?>
	    <!-- Content start -->
  		<div class="container">
  			<div class="row content">
				<div class="col-md-9 col-sm-8">
	  				<h2 class="page-header ">Frequently Asked Questions (FAQs)<br>
	  					<small>All you need to know on getting help and more...</small>
	  				</h2>
	  				<div class="bottom40">
	  					Our team continually updates the list of questions and corresponding
	  					answers to ensure we cover a wide and comprehensive range of possible
	  					information you require or concerns you need addressed. 
	  				</div>
	  				<div class="col-md-12">
	  					<div class="tab-content faq-cat-content disable-border">
                            <div class="tab-pane active in fade" id="faq-cat-20">
                                <div class="panel-group" id="accordion-cat-20">
	  					<?php
		  					if($qdata['numrows']>0){
	  							for($i=0;$i<$qdata['numrows'];$i++){
	  								if($singledata=="single"){
	  									$curdata=$qdata;
	  								}else if($singledata=="group"){
	  									$curdata=$qdata['resultdataset'][$i];
	  								}
	  					?>
	  								<!-- FAQ Item Start -->
			                        <div class="panel panel-default panel-faq">
			                            <div class="panel-heading">
			                                <a data-toggle="collapse" 
			                                data-parent="#accordion-cat-20" 
			                                href="#faq-cat-20-sub-<?php echo $i;?>">
			                                    <h4 class="panel-title">
			                                        <?php echo $curdata['title'];?>
			                                        <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
			                                    </h4>
			                                </a>
			                            </div>
			                            <div id="faq-cat-20-sub-<?php echo $i;?>" 
			                            	class="panel-collapse collapse">
			                                <div class="panel-body">
			                                    <?php echo $curdata['content'];?>
			                                    
			                                </div>
			                            </div>
			                        </div>
			                        <!-- FAQ Item End -->
	  					<?php
	  							}
	  						}else{
	  							$failtitle="Nothing Posted Yet";
	  							$failcontent="There is no data to be shown.";
	  							if($search==true){
	  								$failtitle="Nothing found for your search <b>$datq</b>";
	  								$failcontent="Nothing found for your search";
	  							}
	  					?>
			  					<!-- FAQ Item Start -->
		                        <div class="panel panel-default panel-faq">
		                            <div class="panel-heading">
		                                <a data-toggle="collapse" data-parent="#accordion-cat-20" href="#faq-cat-20-sub-5">
		                                    <h4 class="panel-title">
		                                        <?php echo $failtitle;?>
		                                        <span class="pull-right"><i class="glyphicon glyphicon-plus"></i></span>
		                                    </h4>
		                                </a>
		                            </div>
		                            <div id="faq-cat-20-sub-5" class="panel-collapse collapse">
		                                <div class="panel-body">
		                                    <?php echo $failcontent;?>
		                                    
		                                </div>
		                            </div>
		                        </div>
		                        <!-- FAQ Item End -->
	  					<?php
	  						}
	  					?>
	  							</div>
	  						</div>
	  					</div>

	  				</div>
				</div>
				<div class="col-md-3 col-sm-4">
					<?php 
						$sidebartype="faqdefault";
						include($host_tpathplain.'themesnippets/mysalvussnippets/modules/sidebarparser.php');

					?>	
					<div class="row custom-search">
						<h5 class="underlined"><strong>Check</strong> Resources</h5>
						<a href="##Resources" class="sidebar-lg-link">Resources</a>
					</div>
				</div>

  			</div>
  		</div>
	    <!-- Content End -->
	    <?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/footermysalvus.php');
		?>
		<?php
			include($host_tpathplain.'themesnippets/mysalvussnippets/themescriptsdumpmysalvus.php');
		?>
	</body>
</html>