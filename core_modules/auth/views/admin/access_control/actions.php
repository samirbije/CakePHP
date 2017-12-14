<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
         <h1><?php echo lang('backendpro_access_control');?><small><?php echo lang('access_actions'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- row -->
        <div class="row">
            
            <?php print form_open('admin/auth/actions/delete')?> 
            <div class="col-md-12">

                <?php print displayStatus();?>
                <div class="buttons">
                    <a href="<?php print site_url('admin/auth/actions/form')?>" class="btn btn-success btn-flat btn-xs no-shadow">
                    <?php print $this->lang->line('access_create_action')?>
                    </a>
                </div>
                <br/>

                <div class="box box-solid">
                    <div class="box-body no-padding">
                        <table class="table table-striped8">
                            <thead>
                                <tr>
                                    <th width=5%><?php print $this->lang->line('general_id')?></th>
                                    <th><?php print $this->lang->line('access_resources')?></th>
                                    <th><?php print $this->lang->line('access_actions')?></th>
                                    <th><?php print $this->lang->line('general_delete')?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                    <td><?php print form_submit('delete',$this->lang->line('general_delete'),'class="btn btn-flat btn-danger btn-xs" onClick="return confirm(\''.$this->lang->line('access_delete_actions_confirm').'\');"')?></td>
                                </tr>
                            </tfoot>

                            <tbody>
                                <?php 
                                    $query = $this->access_control_model->getAclActions();
                                    foreach($query as $sn => $result): ?>
                                    <tr>
                                        <td><?php print ($sn+1)?></td>
                                        <td><?php print ($result->resource != '') ? $result->resource : 'N/A'; ?></td>
                                        <td><?php print $result->name?></td>
                                        <td><?php print form_checkbox('select[]',$result->id . "|" . $result->name,FALSE)?></td>
                                    </tr>    
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <?php print form_close()?>
        </div>
        <!-- /.row -->

    </section><!-- /.content -->
</aside><!-- /.right-side -->