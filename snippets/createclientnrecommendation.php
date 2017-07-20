<?php
        // $formcontentdisplaycontrol="clientelle";
        $outs=getAllClientRecommendations("admin","$formcontentdisplaycontrol",'');
        $formcontentdisplaycontrol=isset($formcontentdisplaycontrol)?$formcontentdisplaycontrol:"recommendation";
        // control block for adjusting displayed form elements on the new post form, default is for recommendation entry
        $entryvariantoutput="recommendation";
        $formtitledisplay="Create/Edit Recommendation Entry";
        $nsubtitledisplay="New Recommendation Entry";
        $nsubtitle2display="Edit Recommendation Entry";
        $fullnamedisplay="";
        $contentpicdisplay="";
        $pwebsitedisplay="";
        $companynamedisplay="";
        $cwebsitedisplay="";
        $positiondisplay="";
        $contentdisplay="";
        $formelemcountertitle="currecommendationslidecount";
        $formelemcountertrigger="addextrarecommendationslide";
        $formelemsubmittrigger="recommendationsubmit";
        $formelemcountermarker="entryrecommendationslidepoint";
        if($formcontentdisplaycontrol=="clientelle"){
          $entryvariantoutput="clientelle";
          $formtitledisplay="Create/Edit Client Base Entry";
          $nsubtitledisplay="New Client Entry";
          $nsubtitle2display="Edit Client Entry";
          $fullnamedisplay="display:none;";
          $contentpicdisplay="";
          $pwebsitedisplay="display:none;";
          $companynamedisplay="";
          $cwebsitedisplay="";
          $positiondisplay="display:none;";
          $contentdisplay="";
          $formelemcountertitle="curclientelleslidecount";
          $formelemcountertrigger="addextraclientelleslide";
          $formelemcountermarker="entryclientelleslidepoint";
          $formelemsubmittrigger="clientellesubmit";
        }else if($formcontentdisplaycontrol=="testimonial"){
          $entryvariantoutput="testimonial";
          $formtitledisplay="Create/Edit Testimonial Entry";
          $nsubtitledisplay="New Testimonial Entry";
          $nsubtitle2display="Edit Testimonial Entry";
          $fullnamedisplay="";
          $contentpicdisplay="";
          $pwebsitedisplay="";
          $companynamedisplay="";
          $cwebsitedisplay="";
          $positiondisplay="";
          $contentdisplay="";
          $formelemcountertitle="curtestimonialslidecount";
          $formelemcountertrigger="addextratestimonialslide";
          $formelemcountermarker="entrytestimonialslidepoint";
          $formelemsubmittrigger="testimonialsubmit";
        }
?>
       
      <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $formtitledisplay;?></h3>
              <div class="box-tools pull-right">
                <!-- <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button> -->
                <!-- <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button> -->
              </div>
            </div>
            <div class="box-body">
              <div class="box-group" id="generaldataaccordion">
                <div class="panel box overflowhidden box-primary">
                      <div class="box-header with-border">
                          <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#NewPageManagerBlock">
                              <i class="fa fa-sliders"></i> <?php echo $nsubtitledisplay;?>
                            </a>
                          </h4>
                      </div>
                      <div id="NewPageManagerBlock" class="panel-collapse collapse">
                          <div class="row">
                              <form name="contentform" method="POST" enctype="multipart/form-data" action="../snippets/basicsignup.php">
                                <input type="hidden" name="entryvariant" value="<?php echo $entryvariantoutput;?>"/>
                                <div class="col-md-12" name="surveysliderpoint">
                                      <h4><?php echo $nsubtitledisplay;?></h4>
                                      <input type="hidden" name="<?php echo $formelemcountertitle;?>" value="1"/>
                                      <div class="col-md-12">
                                            <div class="col-md-6">
                                              <div class="form-group" style="<?php echo $contentpicdisplay;?>">
                                                <label>Select Image</label>
                                                <div class="input-group">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                  </div>
                                                  <input type="file" class="form-control" name="slide1" Placeholder=""/>
                                                </div><!-- /.input group -->
                                              </div><!-- /.form group -->
                                              <div class="form-group" style="<?php echo $fullnamedisplay;?>">
                                                <label>Fullname:</label>
                                                <div class="input-group">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                  </div>
                                                  <input type="text" class="form-control" name="fullname1" Placeholder="Fullname"/>
                                                </div><!-- /.input group -->
                                              </div><!-- /.form group -->
                                              <div class="form-group" style="<?php echo $companynamedisplay;?>">
                                                <label>Company Name:</label>
                                                <div class="input-group">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                  </div>
                                                  <input type="text" class="form-control" name="companyname1" Placeholder="Company Name"/>
                                                </div><!-- /.input group -->
                                              </div><!-- /.form group -->
                                              <div class="form-group" style="<?php echo $cwebsitedisplay;?>">
                                                <label>Company Website:</label>
                                                <div class="input-group">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                  </div>
                                                  <input type="text" class="form-control" name="companywebsite1" Placeholder="Company website format:(thewebsitename.com)"/>
                                                </div><!-- /.input group -->
                                              </div><!-- /.form group -->
                                              <div class="form-group" style="<?php echo $positiondisplay;?>">
                                                <label>Position:</label>
                                                <div class="input-group">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                  </div>
                                                  <input type="text" class="form-control" name="position1" Placeholder="Position at Company"/>
                                                </div><!-- /.input group -->
                                              </div><!-- /.form group -->
                                              <div class="form-group" style="<?php echo $pwebsitedisplay;?>">
                                                <label>Personal Website:</label>
                                                <div class="input-group">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                  </div>
                                                  <input type="text" class="form-control" name="personalwebsite1" Placeholder="Personal website format:(thewebsitename.com)"/>
                                                </div><!-- /.input group -->
                                              </div><!-- /.form group -->
                                              <div class="form-group" style="<?php echo $contentdisplay;?>">
                                                <label>Details:</label>
                                                <div class="input-group">
                                                  <div class="input-group-addon">
                                                    <i class="fa fa-file-text"></i>
                                                  </div>
                                                  <textarea rows="4" class="form-control" name="details1" id="postersmallthree" Placeholder="Place the details for this entry here"></textarea>
                                                </div><!-- /.input group -->
                                              </div><!-- /.form group -->
                                            </div>
                                          <div name="<?php echo $formelemcountermarker;?>"></div>
                                      </div>
                                    <a href="##" class="addpoint" name="<?php echo $formelemcountertrigger;?>">Click to add another slide</a>
                                    </div>
                                   
                                    <div class="col-md-12">
                                        <div class="box-footer">
                                            <input type="button" class="btn btn-danger" name="<?php echo $formelemsubmittrigger;?>" value="Create"/>
                                        </div>
                                  </div>
                              </form>
                          </div>
                      </div>
                </div>
                <div class="panel box overflowhidden box-primary">
                      <div class="box-header with-border">
                        <h4 class="box-title">
                          <a data-toggle="collapse" data-parent="#generaldataaccordion" href="#EditBlock">
                            <i class="fa fa-gear"></i> <?php echo $nsubtitle2display;?>
                          </a>
                        </h4>
                      </div>
                      <div id="EditBlock" class="panel-collapse collapse in">
                        <div class="box-body">
                            <div class="row">
                              <div class="col-md-12">
                                <?php
                                  echo $outs['adminoutput'];
                                ?>
                              </div>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
      </div>
      <script>
            tinyMCE.init({
                    theme : "modern",
                    selector:"textarea#postersmallthree",
                    menubar:false,
                    statusbar: false,
                    plugins : [
                     "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                     "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                     "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
                    ],
                    width:"100%",
                    height:"300px",
                    toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                    toolbar2: "| responsivefilemanager | link unlink anchor | code",
                    image_advtab: true ,
                    content_css:""+host_addr+"stylesheets/mce.css",
                    external_filemanager_path:""+host_addr+"scripts/filemanager/",
                    filemanager_title:"Site Content Filemanager" ,
                    external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
            });
            tinyMCE.init({
                    theme : "modern",
                    selector:"textarea#postersmallfive",
                    menubar:false,
                    statusbar: false,
                    plugins : [
                     "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                     "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                     "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
                    ],
                    width:"100%",
                    height:"300px",
                    toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                    toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
                    image_advtab: true ,
                    editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                    content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                    external_filemanager_path:""+host_addr+"scripts/filemanager/",
                    filemanager_title:"Max-Migold Content Filemanager" ,
                    external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
            });   
      </script>