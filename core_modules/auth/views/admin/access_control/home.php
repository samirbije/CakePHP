<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo lang('backendpro_system');?><small><?php echo lang('backendpro_access_control'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <?php print displayStatus();?>

        <!-- row -->
        <div class="row">

            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box">
                    <div class="inner">
                        <h3 class="access-control"><?php echo $this->lang->line('access_permissions');?></h3>
                        <p><?php print wordwrap($this->lang->line('access_permissions_desc'), 175, "<br />");?></p>
                    </div>
                    <a href="<?php echo site_url('admin/auth/permissions');?>" class="small-box-footer bg-green" style="text-align:right">
                        Open &nbsp;&nbsp;<i class="fa fa-folder-open" style="margin-right:5px"></i>
                    </a>
                </div>
            </div>


            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box">
                    <div class="inner">
                        <h3 class="access-control"><?php echo $this->lang->line('access_groups');?></h3>
                        <p><?php print wordwrap($this->lang->line('access_groups_desc'), 175, "<br />");?></p>
                    </div>
                    <a href="<?php echo site_url('admin/auth/groups');?>" class="small-box-footer bg-green" style="text-align:right">
                        Open &nbsp;&nbsp;<i class="fa fa-folder-open" style="margin-right:5px"></i>
                    </a>
                </div>
            </div>

        </div>


        <div class="row">

            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box">
                    <div class="inner">
                        <h3 class="access-control"><?php echo $this->lang->line('access_resources');?></h3>
                        <p><?php print wordwrap($this->lang->line('access_resources_desc'), 175, "<br />");?></p>
                    </div>
                    <a href="<?php echo site_url('admin/auth/resources');?>" class="small-box-footer bg-green" style="text-align:right">
                        Open &nbsp;&nbsp;<i class="fa fa-folder-open" style="margin-right:5px"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box">
                    <div class="inner">
                        <h3 class="access-control"><?php echo $this->lang->line('access_actions');?></h3>
                        <p><?php print wordwrap($this->lang->line('access_actions_desc'), 175, "<br />");?></p>
                    </div>
                    <a href="<?php echo site_url('admin/auth/actions');?>" class="small-box-footer bg-green" style="text-align:right">
                        Open &nbsp;&nbsp;<i class="fa fa-folder-open" style="margin-right:5px"></i>
                    </a>
                </div>
            </div>

            <?php /* ?>
            <div class="col-lg-6 col-xs-6">
                <!-- small box -->
                <div class="small-box">
                    <div class="inner">
                        <h3><?php echo $this->lang->line('access_actions');?></h3>
                        <p><?php print wordwrap($this->lang->line('access_actions_desc'), 75, "<br />");?></p>
                    </div>
                    <div class="icon">
                        <?php print img('assets/images/ac_actions.png')?>
                    </div>
                    <a href="<?php echo site_url('admin/auth/actions');?>" class="small-box-footer bg-green">
                        Open &nbsp;&nbsp;<i class="fa fa-folder-open" style="margin-right:5px"></i>
                    </a>
                </div>
            </div>
            <?php */ ?>

        </div>

    </section><!-- /.content -->
</aside><!-- /.right-side -->