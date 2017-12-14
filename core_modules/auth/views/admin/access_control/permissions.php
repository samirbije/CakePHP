<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo lang('backendpro_access_control');?><small><?php echo lang('access_permissions'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- row -->
        <div class="row">
            
            <?php print form_open('admin/auth/permissions/delete')?>
            <div class="col-md-12">

                <?php print displayStatus();?>

                <div class="buttons">
                    <a href="<?php print  site_url('admin/auth/permissions/form')?>" class="btn btn-success btn-flat btn-xs" >
                        <?php print $this->lang->line('access_create_permission')?>
                    </a>

                </div>
                <br/>

                <div class="box box-solid">
                    <div class="box-body no-padding">
                        <table class="table table-striped8">
                            <thead>
                                <tr>
                                    <th width=5% style="text-align:center"><?php print $this->lang->line('general_id')?></th>
                                    <th width=25%><?php print $this->lang->line('access_groups')?></th>
                                    <th width=25%><?php print $this->lang->line('access_resources')?></th>
                                    <th width=25%><?php print $this->lang->line('access_actions')?></th>
                                    <th width=10% style="text-align:center"><?php print $this->lang->line('general_edit')?></th>
                                    <th width=10% style="text-align:center"><?php print $this->lang->line('general_delete')?></th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <td colspan=5>&nbsp;</td>
                                    <td style="text-align:center"><?php print form_submit('delete',$this->lang->line('general_delete'),'class="btn btn-flat btn-danger btn-xs" onClick = "return confirm(\''.$this->lang->line('access_delete_permissions_confirm').'\');"')?></td>
                                </tr>
                            </tfoot>

                            <tbody>
                                <?php $sn = 1;
                                foreach($this->access_control_model->getPermissions() as $key=>$row){?>
                                <tr>
                                    <td style="text-align:center"><?php print $sn++?></td>
                                    <td style="vertical-align:middle"><?php print $row['aro']?></td>
                                    <td style="vertical-align:middle"><span class="<?php print ($row['allow']) ? 'label label-success':'label label-danger'?>"><?php print $row['aco']?></span></td>
                                    <td>
                                        <?php
                // Print out the actions
                                        if(isset($row['actions'])){
                                            foreach($row['actions'] as $action)
                                            {
                                                print '<span class="';
                                                print ($action['allow']) ? 'label label-success':'label label-danger    ';
                                                print '">'.$action['axo'].'</span>&nbsp;';
                                            }
                                        }
                                        else { print "&nbsp;"; }
                                        ?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php if ($key != 1) { ?> <a href="<?php print site_url('admin/auth/permissions/form/'.$key)?>"><?php print $this->bep_assets->icon('pencil');?></a> <?php } ?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php if ($key != 1) { print form_checkbox('select[]',$key,FALSE); } ?>
                                    </td>
                                </tr>
                                <?php } ?>
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