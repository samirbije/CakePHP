<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
         <h1><?php echo lang('backendpro_access_control');?><small><?php echo lang('access_groups'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- row -->
        <div class="row">
            
            <?php print form_open('admin/auth/groups/delete')?> 
            <div class="col-md-12">

                <?php print displayStatus();?>

                <div class="buttons">
                    <a href="<?php print site_url('admin/auth/groups/form')?>" class="btn btn-success btn-flat btn-xs no-shadow" iconCls="icon-add">
                    <?php print $this->lang->line('access_create_group')?>
                    </a>
                </div>
                <br/>

                <div class="box box-solid">
                    <div class="box-body no-padding">
                        <table class="table table-striped8">
                            <thead>
                        <tr>
                            <th width=5%><?php print $this->lang->line('general_id')?></th>
                            <th><?php print $this->lang->line('access_groups')?></th>
                            <th width=15% style="text-align:center"><?php print $this->lang->line('access_disabled')?></th>  
                            <th width=15% style="text-align:center"><?php print $this->lang->line('general_edit')?></th>  
                            <th width=15% style="text-align:center"><?php print $this->lang->line('general_delete')?></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan=4>&nbsp;</td>
                            <td align="center"><?php print form_submit('delete',$this->lang->line('general_delete'),'class="btn btn-flat btn-danger btn-xs" onClick="return confirm(\''.$this->lang->line('access_delete_groups_confirm').'\');"')?></td>
                        </tr>
                    </tfoot>

                             <tbody>
                        <?php 
                            // Output nested resource view
                            $obj = & $this->access_control_model->group;
                            $tree = $obj->getTreePreorder($obj->getRoot());

                            while($obj->getTreeNext($tree)):        
            
                                // See if this group is locked
                                $query = $this->access_control_model->fetch('groups',NULL,NULL,array('id'=>$tree['row']['id']));
                                $row = $query->row();     

                                // Get Offset
                                $offset = $this->access_control_model->buildPrettyOffset($obj,$tree);
                                $disable = ($row->disabled?'tick':'cross');
                                $edit = ($obj->checkNodeIsRoot($tree['row']))?'&nbsp;':'<a href="'.site_url('admin/auth/groups/form/'.$tree['row']['id']).'">'.$this->bep_assets->icon('pencil').'</a>';
                        ?>  
                        <tr>
                            <td><?php print $tree['row']['id']?></td>
                            <td><?php print $offset.$tree['row']['name']?></td>
                            <td width=10% style="text-align:center"><?php print $this->bep_assets->icon($disable)?></td> 
                            <td width=10% style="text-align:center"><?php print $edit?></td> 
                            <td width=10% style="text-align:center"><?php print ($row->locked OR $this->preference->item('default_user_group')==$tree['row']['id'])?'&nbsp;':form_checkbox('select[]',$tree['row']['name'],FALSE)?></td>
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