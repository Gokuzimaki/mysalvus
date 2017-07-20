<?php 
	// blogcommentparser.php
	// this file is responsible for parsing blog post comments output
	// based on normal i.e system defined post or utilised a predefined set of supported
	// plugins
	// the variable $curblogdata is expected to be available
	if(isset($curblogdata)&&$curblogdata['commenttype']=="normal"){
		// first we check for the type of comment being used
		// current comment types include normal and disqus though disqus is still
		// being integrated
?>
							
		<div class="row">
			<div class="col-md-12">
				<h3 class="page-header">
					<span>
						<i class="glyphicon glyphicon-comment"></i> 
						Comments <?php echo $blog_comment_count?>
					</span>
				</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<ol class="media-list comments">
<?php
		if($blog_comment_count>0){
			for($bi=0;$bi<$blog_comment_count;$bi++){
				$ti=$bi+1;
				$eodd=$ti%2==0?"even":"odd";
				
				$curcomment=$blog_comment_data[$bi];
				// comments only go down 3 levels maximum
				// the indexes available here are
				// type(comment/reply),fullname,website,email,pid(posterid),
				// cid(commentid refers to the parent comment for the reply.),
				// Note, Proper parsing of replies have already been done so all you 
				// have to do is focus on looping through the replies per level
				// i.e loop lvl 1 ('curcomment['reply']['depth'][0-n]') gets replies for 
				// current reply loop
				//  then loop lvl 2 (curcomment['reply']['depth'][0-n]['depth'])
				// store the class that differntiates the author/admin response
				// from normal posts by checking the pid index, which is only greater
				//  than zero when an administrator makes a comment
				$authorclass=$curcomment['pid']>0?"bypostauthor":"";
				$authormarkup=$curcomment['pid']>0?'<small>(Post Author)</small>':"";
?>
					<li class="media <?php echo $eodd;?>  
						<?php echo $authorclass;?>">
						
						<a href="##" class="pull-left">
	                        <img alt="<?php echo $curcomment['fullname']?>" 
	                        style="width: 64px; height: 64px;" 
	                        src="<?php echo $curcomment['avatar']?>" 
	                        class="img-circle commenter_img img-responsive">
	                    </a>
						<div class="media-body">
                            <h4 class="media-heading">
                            	<span class="comment-by"><?php echo $curcomment['fullname']?></span>
                            	
                            	<span class="comment-date pull-right">
                            		<i class="glyphicon glyphicon-time"></i> 
                            		<?php echo $curcomment['comment_date'];?>
                            	</span>
                            </h4>
                            <div class="hold">
                            	<?php echo $curcomment['comment'];?>
                            </div>
                        </div>
						<?php
							// loop through the replies if any
						?>
						<?php
						?>
					</li>									
<?php
			}
?>
<?php
		}else{
?>
			<li class="media odd depth-1">
				<div class="comment-box">
					<div class="media-body">
						No comments yet, be the first to make one 
					</div>

				</div>

			</li>
<?php
		}
?>
				</ol>							
			</div>
		</div>

<?php
			// this section is for making comments, the form should submit to the 
			// basicsignup.php file located in $host/snippets/basicsignup.php
			// the following form fields must be made available before submission
			// is done
			// 
			// the default comments module also provides you with the following
			// variables  to be used optionally/ compulsorily in the form
			/*
				$c_form_url (compulsory)
				$c_formname(compulsory) - the nameattribute value to be used for the 
				comment form i.e <form name="$c_formname">
				
				$c_form_validate_number(compulsory, if you use security_field) -  
				this number is used to verify if
				the person posting is human, it must be shown within the form the 
				the comment is being made in

				$c_form_fields(compulsory) - this fields carry necessary information
				that allows the comment form to be processed by the handler file
				
				use either the captcha or the security field 
				
				$c_form_security_captcha(optional)
				
				$c_form_security_field(optional)
				
				$c_form_validate_fieldname(compulsory, if using security_field)
				
				$c_form_validate_fieldattr(compulsory, if using security_field)
				
				$c_form_script(compulsory, if you want to use tinyMCE rich comment field)
				the comment field must have an id of "comment" for this function to 
				truly work. As with any script, this must be echoed out after the 
				form.

				$c_form_email_attr(compulsory, if using extra_data and you want proper
				email verification) - carries attributes for the email field that allows
				automatic regex verification of the email
				
				$c_form_extra_data - carries the error map field for the comment form
				
				$c_form_submit_attr(compulsory if using extra_data) this attributes
				can be included with the submit button.
			*/
				
?>

			<!-- <div id="respond" class="comment-respond">
				<form action="<?php echo $c_form_url;?>" method="post" 
				id="commentform" name="<?php echo $c_form_name;?>" 
				<?php echo $c_form_attr;?> class="comment-form">
				<?php echo $c_form_fields;?>
				 <?php echo $c_form_security_field?>
					<div class="row">
						<div class="col-md-12">
							<h4 class="underlined">Leave a <strong>Comment</strong>
							</h4>
							<p>
								<i>Entry code :
									<?php echo $c_form_validate_number;?></i>
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<input type="text" id="security"
							 name="<?php echo $c_form_validate_fieldname;?>"
							 <?php echo $c_form_validate_fieldattr;?> 
							 placeholder="Security Code"/>

							<input type="text" id="author"
							 name="fullname" placeholder="Fullname"/>
							<input type="email" id="email" 
							<?php echo $c_form_email_attr;?> 
							name="email" 
							placeholder="Email Address"/>	
							<input type="text" id="url" name="website" 
							placeholder="website"/>
		
						</div>
						<div class="col-sm-8">
							<textarea id="comment" name="comment" 
							<?php echo $c_form_comment_attr;?>
							placeholder="message"></textarea>
							<?php echo $c_form_extra_data;?>
							<input type="button" 
							class="pull-right btn btn-primary tmq-btn" 
							name="comment" id="submit" <?php echo $c_form_submit_attr;?>
							value="comment">
						</div>
					</div>
				</form>
			</div> -->


			<!-- Separator Start -->
             <div class="separator top40 bottom40">
                <div class="separator-style"></div>
             </div>
             <!-- Separator End -->
			<div class="row bottom40">
                <div class="col-md-12">
	                <h3 class="page-header">
	                	<span>
	                	<i class="glyphicon glyphicon-pencil"></i> Leave a Comment 
	                </span></h3>
	                <p class="bottom20 text-center">Your email address will not be published. 
	                	Required fields are marked *</p>
	                <form action="<?php echo $c_form_url;?>" onsubmit="return false;" 
	                	method="post" name="<?php echo $c_form_name;?>" 
						<?php echo $c_form_attr;?> class="form">
	                	<?php echo $c_form_fields;?>
						<?php echo $c_form_security_field?>
	                    <div class="leave-a-comment">
	                        <h3 class="text-center">
	                            Your Comment </h3>
	                        <div class="form-group">
	                            <input type="text" class="form-control" id="name" 
	                            placeholder="Full Name *" name="fullname" required="">
	                        </div>
	                        <div class="form-group">
	                            <input type="email" name="email" 
	                            class="form-control" id="Email" 
	                            placeholder="Email *" required="">
	                        </div>
	                        <div class="form-group">
	                        	<label>Entry code :
										<?php echo $c_form_validate_number;?></label>
	                            <input type="text" id="security" data-error-output="The code given was not a match"
								 name="<?php echo $c_form_validate_fieldname;?>"
								 <?php echo $c_form_validate_fieldattr;?> 
								 placeholder="Place Comment Code here *" class="form-control"/>
	                        </div>
	                        <div class="form-group">
	                            <textarea 
	                            class="form-control" 
	                            rows="3" 
	                            <?php /*echo $c_form_comment_attr;*/?>
	                            name="comment"
	                            placeholder="Comment *" 
	                            required=""></textarea>
	                        </div>

	                        <div class="col-md-12 text-centerclear-both">
							<?php echo $c_form_extra_data;?>
	                        	<input type="button" 
								class="pull-right btn btn-primary tmq-btn" 
								name="createcomment"  
								<?php echo $c_form_submit_attr;?>
								value="Post Comment">
	                        </div>
	                    </div>
	                </form>
                </div>
            </div>
			
<?php

?>

<?php
		
	}else{
		// this section means the blog comment type in use is not normal and coding
		// here simply refers to using one variable $blog_comment_data_output
		//  we check on its availability and we echo it here
		$blog_comment_data_output=isset($blog_comment_data_output)?
		$blog_comment_data_output:"";
		echo $blog_comment_data_output;
?>

<?php
	}

?>
