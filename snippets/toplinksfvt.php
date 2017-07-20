<?php
		// if active page not set, initalize all affected variables for each link
		!isset($activepage1)?$activepage1="":$activepage1=$activepage1;
		!isset($activepage2)?$activepage2="":$activepage2=$activepage2;
		!isset($activepage3)?$activepage3="":$activepage3=$activepage3;
		!isset($activepage4)?$activepage4="":$activepage4=$activepage4;
		!isset($activepage5)?$activepage5="":$activepage5=$activepage5;
		!isset($activepage6)?$activepage6="":$activepage6=$activepage6;
		!isset($activepage7)?$activepage7="":$activepage7=$activepage7;
		!isset($activepage8)?$activepage8="":$activepage8=$activepage8;
		$mpagesearchclass="";
        // var_dump($pweldata);
?>
<!-- NO SCRIPT -->
<noscript>
    <style type="text/css">
        .loader-wrapper{
            opacity: 0;
            visibility: hidden;
        }
    </style>
    <div class="javascript-required">
        <i class="fa fa-times-circle"></i> You seem to have Javascript disabled. This website needs javascript in order to function properly!
    </div>
</noscript>

<!-- LOADER START -->
<div class="loader-wrapper">
	<div class="spinner">
		<div class="bounce1"></div>
		<div class="bounce2"></div>
		<div class="bounce3"></div>
	</div>
</div>

<header id="header">
    <div class="navigation-box ">
        <div class="container">
            <strong class="logo">
                <a href="<?php echo $host_addr?>">
                    <img class="logo-img" src="<?php echo $host_addr?>images/fvtimages/fvtlogofull.png">
                </a>
            </strong>
            <div class="top-bar-outer">
                <div class="nav-box">
                    <div class="navbar">
                        <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="nav-collapse collapse in">
                            <nav id="nav">
                                <ul>
                                    <li class="<?php echo $activepage1;?>">
                                        <a href="<?php echo $host_addr;?>index.php">Home</a>
                                    </li>
                                    <li class="<?php echo $activepage2;?>">
                                        <a href="<?php echo $host_addr;?>resources.php">Resources</a>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $host_addr;?>resources.php?t=Articles">Articles</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>resources.php?t=Videos">Videos</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>resources.php?t=Case Studies">Case Studies</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>resources.php?t=Seminars">Seminars</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="<?php echo $activepage3;?>">
                                        <a href="<?php echo $host_addr;?>infodesk.php">Info Desk</a>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $host_addr;?>infodesk.php?t=Donations">Donation</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>infodesk.php?t=MnP">Membership & Partnerships</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>infodesk.php?t=Events">Events</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="<?php echo $activepage4;?>">
                                        <a href="<?php echo $host_addr;?>services.php">Our Services</a>
                                        <!-- <ul>
                                            <li>
                                                <a href="<?php echo $host_addr;?>services.php?t=Schools">Schools</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>services.php?t=YouthInstitutions">Youth Institutions</a>
                                            </li>

                                            <li>
                                                <a href="<?php echo $host_addr;?>services.php?t=FaithOrganisations">Faith Organisations</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>services.php?t=Leaders">Leaders</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>services.php?t=NnC">Society</a>
                                            </li>
                                        </ul> -->
                                    </li>
                                    <li class="<?php echo $activepage5;?>">
                                        <a href="<?php echo $host_addr;?>about.php">About</a>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $host_addr;?>about.php?t=AboutFVT">About FVT</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $host_addr;?>about.php?t=Trustees">Trustees</a>
                                            </li>
                                            <!-- <li>
                                                <a href="<?php echo $host_addr;?>about.php?t=Stakeholders">Stakeholders</a>
                                            </li> -->
                                            <li>
                                                <a href="<?php echo $host_addr;?>about.php?t=OurCharter">Our Charter</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="<?php echo $activepage6;?>">
                                        <a href="<?php echo $host_addr;?>portfolio.php">Gallery</a>
                                    </li>
                                    <li class="<?php echo $activepage7;?>">
                                        <a href="<?php echo $host_addr;?>contact.php">Contact Us</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="bottom-row">
                    <form action="<?php echo $host_addr?>search.php" method="GET" class="header-search">
                        <div class="input-box <?php echo $mpagesearchclass;?>">
                            <input name="q" required type="text" placeholder="Search our site..." class="header-input">
                            <button class="header-btn-search" value="">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <strong class="title">
                            <a href="<?php echo $host_addr;?>infodesk.php?t=MnP" class="btn-membership">JOIN OUR <span>CAMPAIGN!</span></a>
                        </strong>
                    </form>
                    <div class="donate-box">
                        <a href="<?php echo $host_addr;?>infodesk.php?t=Donations" class="btn-donate">
                            Support <span>Us</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
        //refer to tjhe headcontent snippet for documentation on below variables
        echo $mpagecrumbpath;
        echo $mpagetopbanner;
        echo $mpagetopbanners;

    ?>
</header>
