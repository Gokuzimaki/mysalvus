<?php
  $outs=getAllManagementTeam("admin",'','');
?>
<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Management Team</h3>
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
                        <i class="fa fa-sliders"></i> New Management Team Entry
                      </a>
                    </h4>
                </div>
                <div id="NewPageManagerBlock" class="panel-collapse collapse in">
                    <div class="row">
                        <form name="contentform" method="POST" enctype="multipart/form-data" action="../snippets/basicsignup.php">
                          <input type="hidden" name="entryvariant" value="createteamentry"/>
                          <input type="hidden" name="maintype" value="about"/>
                          <input type="hidden" name="subtype" value="teamentry"/>
                          <div class="col-md-12" name="surveysliderpoint">
                                <h4>Create Management Team Members</h4>
                                <input type="hidden" name="curteamslidecount" value="1"/>
                                <div class="col-md-12">
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label>Select Image</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="file" class="form-control" name="slide1" Placeholder=""/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                        <div class="form-group">
                                          <label>Member Name:</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="fullname1" Placeholder="Member Name"/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                        <div class="form-group">
                                          <label>Qualifications:</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="qualifications1" Placeholder="Member Qualification"/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                        <div class="form-group">
                                          <label>Member Position:</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="position1" Placeholder=""/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                        <div class="form-group">
                                          <label>Member Details:</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <textarea rows="4" class="form-control" name="details1" id="postersmallthree" Placeholder="Place the team members details here"/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                      </div>
                                      
                                    <div name="entryteamslidepoint"></div>
                                </div>
                              <!-- <a href="##" class="addpoint" name="addextrateamslide">Click to add another slide</a> -->
                              </div>
                              <script>
                                  tinyMCE.init({
                                        theme : "modern",
                                        selector: "textarea#adminposter",
                                        skin:"lightgray",
                                        width:"94%",
                                        height:"650px",
                                        external_image_list_url : ""+host_addr+"snippets/mceexternalimages.php",
                                        plugins : [
                                         "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                                         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                                         "save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
                                        ],
                                        // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                                        toolbar1: "undo redo | bold italic underline | fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                                        toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                                        image_advtab: true ,
                                        editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                                        content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                                        external_filemanager_path:""+host_addr+"scripts/filemanager/",
                                        filemanager_title:"Adsbounty Admin Blog Content Filemanager" ,
                                        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
                                  });
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
                                          height:"400px",
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
                                          width:"80%",
                                          height:"300px",
                                          toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                                          toolbar2: "| responsivefilemanager | link unlink anchor | emoticons",
                                          image_advtab: true ,
                                          editor_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                                          content_css:""+host_addr+"stylesheets/mce.css?"+ new Date().getTime(),
                                      external_filemanager_path:""+host_addr+"scripts/filemanager/",
                                        filemanager_title:"NYSC Admin Blog Content Filemanager" ,
                                        external_plugins: { "filemanager" : ""+host_addr+"scripts/filemanager/plugin.min.js"}
                                  });   
                            </script>
                              <div class="col-md-12">
                                  <div class="box-footer">
                                      <input type="button" class="btn btn-danger" name="teamentrysubmit" value="Create"/>
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
                      <i class="fa fa-gear"></i> Edit Management Team
                    </a>
                  </h4>
                </div>
                <div id="EditBlock" class="panel-collapse collapse">
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

