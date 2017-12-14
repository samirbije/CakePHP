<div class="box box-solid">
    <div class="box-header">
        <h3 class="box-title"><?php echo $header; ?></h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <?php print form_open($form_link);?>
    <div class="box-body">
        <?php foreach($field as $name => $data): ?>
        
        <div class="form-group">
            <?php print form_label($data['label'],$name);?>
            <?php print $data['input'];?>
        </div>
        <?php endforeach; ?>
        
    </div><!-- /.box-body -->

    <div class="box-footer">
        <button type="submit" class="btn btn-success btn-flat btn-xs no-shadow" name="submit" value="submit">
            <?php print $this->lang->line('general_save')?>
        </button>

        <a class="btn btn-success btn-flat btn-xs no-shadow" href="<?php print site_url($cancel_link); ?>" >
            <?php print $this->lang->line('general_cancel')?>
        </a>

    </div>
    <?php print form_close();?>
</div><!-- /.box -->    