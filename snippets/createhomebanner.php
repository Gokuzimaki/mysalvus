<?php
  $outs=getAllHomeBanners("admin",'','');
?>
<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">HomePage Banners</h3>
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
                        <i class="fa fa-sliders"></i> New Home Page Banners
                      </a>
                    </h4>
                </div>
                <div id="NewPageManagerBlock" class="panel-collapse collapse in">
                    <div class="row">
                        <form name="homebanners" method="POST" enctype="multipart/form-data" action="../snippets/basicsignup.php">
                          <input type="hidden" name="entryvariant" value="createhomebanner"/>
                          <input type="hidden" name="maintype" value="home"/>
                          <input type="hidden" name="subtype" value="bannerentry"/>
                          <div class="col-md-12" name="surveysliderpoint">
                                <h4>Create Home Page Banner</h4>
                                <input type="hidden" name="curbannerslidecount" value="1"/>
                                <div class="col-md-12">
                                  <small>
                                    Highlightcodes for captions: <br>
                                    <b>[|span-green|][|/span-green|]</b>,  
                                    <b>[|span-blue|][|/span-blue|]</b>,  
                                    <b>[|span-orange|][|/span-orange|]</b><br>
                                    copy and paste code in any of the caption fields below, then place your text
                                    between the tags.
                                  </small>
                                </div>
                                <div class="col-md-12">
                                      <div class="col-xs-6">
                                        <div class="form-group">
                                          <label>Select Slide Image(Preferrably 1920x1280 or 1170x900):</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="file" class="form-control" name="slide1" Placeholder=""/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                        <div class="form-group">
                                          <label>Header Caption(Large size caption)
                                          </label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="headercaption1" Placeholder=""/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                        <div class="form-group">
                                          <label>Mini Caption(Small size caption):</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="minicaption1" Placeholder=""/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                        <div class="form-group">
                                          <label>link Address(for links in the caption):</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="linkaddress1" Placeholder=""/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                        <div class="form-group">
                                          <label>link Title(The text the link displays):</label>
                                          <div class="input-group">
                                            <div class="input-group-addon">
                                              <i class="fa fa-file-text"></i>
                                            </div>
                                            <input type="text" class="form-control" name="linktitle1" Placeholder=""/>
                                          </div><!-- /.input group -->
                                        </div><!-- /.form group -->
                                      </div>
                                      
                                    <div name="entrybannerslidepoint"></div>
                                  </div>
                              <a href="##" class="addpoint" name="addextrabannerslide">Click to add another slide</a>
                          </div>
                              <div class="col-md-12">
                                  <div class="box-footer">
                                      <input type="button" class="btn btn-danger" name="homebannersubmit" value="Create"/>
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
                      <i class="fa fa-gear"></i> Edit Home Page Banners
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

