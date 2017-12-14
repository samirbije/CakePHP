<aside class="right-side">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Account<small>Change Password</small></h1>
</section>

<!-- Main content -->
<section class="content">

    <!-- row -->
    <div class="row">
      	<div class="col-xs-12 connectedSortable">
        <?php print displayStatus();?>

	        <div class="box box-solid">
                <?php echo form_open('account/change_password', array('id' =>'login')); ?>
	        	<div class="box-header">
					<h3 class="box-title">Change Password</h3>
	        	</div><!-- /.box-header -->
	         	<div class="box-body">
                    <div class="form-group">
                        <label for="password"><?php echo lang('password')?></label>
                        <input type="password" name="password" id="password" class="form-control" style="width:300px" />
                    </div>
                    <div class="form-group">
                        <label for="new_password"><?php echo lang('new_password')?></label>
                        <input type="password" name="new_password" id="new_password" class="form-control" style="width:300px" />
                    </div>
                    <div class="form-group">
                        <label for="conf_password"><?php echo lang('conf_password')?></label>
                        <input type="password" name="conf_password" id="conf_password" class="form-control" style="width:300px" />
                    </div>
				</div>
				<div class="box-footer">
                    <button type="submit" class="btn btn-success btn-xs btn-flat" name="submit" value="Change Password">Change Password</button>
                </div>
            	<?php echo form_close(); ?>
			</div>
		</div><!-- /.col -->
	</div>
<!-- /.row -->
</section><!-- /.content -->
</aside><!-- /.right-side -->

<script>