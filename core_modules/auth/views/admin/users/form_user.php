<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo lang('backendpro_system');?><small><?php echo lang('backendpro_users'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php print form_open('admin/auth/users/form/'.$this->validation->id,array('class'=>'horizontal'))?>
        <?php print form_hidden('id',$this->validation->id)?>
        <!-- row -->
        <div class="row">
            <div class="col-xs-12 connectedSortable">
                <div class="alert alert-info">
                    <strong>Info: </strong><?php print $this->lang->line('userlib_password_info')?>
                </div>
                <?php print displayStatus();?>
            </div>

            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title">System Login Information</h3>
                    </div><!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="username"><?php echo $this->lang->line('userlib_username')?></label>
                                <input type="text" name="username" value="<?php echo $this->validation->username?>" id="username" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="password"><?php echo $this->lang->line('userlib_password')?></label>
                                <input type="password" name="password" id="password" value="" class="form-control"/></td>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"><?php echo $this->lang->line('userlib_confirm_password')?></label>
                                <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control"/></td>
                            </div>
                            <div class="form-group">
                                <label for="email"><?php echo $this->lang->line('email')?></label>
                                <input type="text" name="email" value="<?php echo $this->validation->email?>" id="email" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="group"><?php echo $this->lang->line('userlib_group')?></label>
                                <?php print form_dropdown('group', $groups, $this->validation->group,'id="group" class="form-control" size="5"')?>
                            </div>
                            <div class="form-group">
                                <label for="active"><?php echo $this->lang->line('userlib_active')?></label><br />
                                    <label><?php print $this->lang->line('general_yes')?> </label>
                                    <?php print form_radio('active','1',$this->validation->set_radio('active','1'),'id="active" ')?>
                                    <label><?php print $this->lang->line('general_no')?></label>
                                    <?php print form_radio('active','0',$this->validation->set_radio('active','0'))?>
                            </div>

                             
                            
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-flat btn-xs no-shadow" name="submit" value="submit">
                                <?php print $this->lang->line('general_save')?>
                            </button>

                            <a class="btn btn-success btn-flat btn-xs no-shadow" href="<?php print site_url('admin/auth/users')?>" >
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
