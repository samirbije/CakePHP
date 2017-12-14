<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo lang('backendpro_access_control');?><small><?php echo lang('access_resources'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- row -->
        <div class="row">
            
            <?php print form_open('admin/auth/resources/delete')?>
            <div class="col-md-12">

                <?php print displayStatus();?>

                <div class="buttons">
                    <a href="<?php print site_url('admin/auth/resources/form')?>" class="btn btn-success btn-flat btn-xs" iconCls="icon-add">
                        <?php print $this->lang->line('access_create_resource')?>
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
                                    <th width=15% style="text-align:center"><?php print $this->lang->line('general_edit')?></th>
                                    <th width=15% style="text-align:center"><?php print $this->lang->line('general_delete')?></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td colspan=3>&nbsp;</td>
                                    <td align="center"><?php print form_submit('delete',$this->lang->line('general_delete'),'class="btn btn-flat btn-danger btn-xs" onClick="return confirm(\''.$this->lang->line('access_delete_resources_confirm').'\');"')?></td>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                        // Output nested resource view
                                $obj = & $this->access_control_model->resource;
                                $tree = $obj->getTreePreorder($obj->getRoot());

                                while($obj->getTreeNext($tree)):
                            // See if this resource is locked
                                    $query = $this->access_control_model->fetch('resources','locked',NULL,array('id'=>$tree['row']['id']));
                                $row = $query->row();

                            // Get Offset
                            // INFO: This is a bit of a hack for php 4 as noted in bug #55
                                if (floor(phpversion()) < 5) {
                                // Use pass by reference since its not deprecated yet
                                    $offset = $this->access_control_model->buildPrettyOffset($obj,$tree);
                                } else {
                                // Can't use pass by reference since it may be deprecated
                                    $offset = $this->access_control_model->buildPrettyOffset($obj,$tree);
                                }
                                $edit = ($obj->checkNodeIsRoot($tree['row'])?'&nbsp;':'<a href="'.site_url('admin/auth/resources/form/'.$tree['row']['id']).'">' . $this->bep_assets->icon('pencil') . '</a>');
                                ?>
                                <tr>
                                    <td><?php print $tree['row']['id']?></td>
                                    <td><?php print $offset.$tree['row']['name']?></td>
                                    <td style="text-align:center"><?php print $edit?></td>
                                    <td style="text-align:center"><?php print ($row->locked?'&nbsp;':form_checkbox('select[]',$tree['row']['name'],FALSE))?></td>
                                </tr>
                            <?php endwhile; ?>
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