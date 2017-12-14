<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><small>POPUP</small></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php print form_open_multipart('admin/advertise/form/'.$this->validation->id,array('class'=>'horizontal'))?>
        <?php print form_hidden('id',$this->validation->id)?>
        <!-- row -->
        <div class="row">
            <div class="col-xs-12 connectedSortable">
                <?php print displayStatus();?>
            </div>

            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-solid">
                        <!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="username">Location</label>
                                <select name="location_name" id="location" required>
								<option value="">SELECT</option>
								<?php
								foreach($locations as $location)
								{
									if($this->validation->location_name==$location['location'])
									{
										$sel = "selected=selected";
									}
									else
									{
										$sel = "";
									}
								?>
								<option value="<?PHP echo $location['location'];  ?>" <?php echo $sel;  ?>><?PHP echo $location['location'];  ?></option>
								<?php
								}
								?>
								</select>
                                
		                    </div>
                            <div class="form-group">
                                <label for="password">language</label>
                                <select name="language_name" id="language" required>
								<option value="">SELECT</option>
								<?php
								foreach($languages as $language)
								{
									if($this->validation->language_name==$language['language'])
									{
										$sel = "selected=selected";
									}
									else
									{
										$sel = "";
									}
								?>
								<option value="<?PHP echo $language['language'];  ?>" <?php echo $sel;  ?>><?PHP echo $language['language'];  ?></option>
								<?php
								}
								?>
								</select>
                                </td>
                            </div>
                            <div class="form-group">
                                <label for="group">Imgaes/MP4</label>
                                <input type="file" id="image" name="image" required/>
                            </div>
                        </div><!-- /.box-body -->
				        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-flat btn-xs no-shadow" name="submit" value="submit">
                                <?php print $this->lang->line('general_save')?>
                            </button>
                            <a class="btn btn-success btn-flat btn-xs no-shadow" href="<?php print site_url('admin/advertise/index')?>" >
                                <?php print $this->lang->line('general_cancel')?>
                            </a>
                        </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
        <!-- /.row -->
        <?php print form_close()?>
    </section><!-- /.content -->
</aside><!-- /.right-side -->
