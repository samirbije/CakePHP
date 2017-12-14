<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo lang('backendpro_access_control');?><small><?php echo lang('access_permissions'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-solid">
            <div class="box-body">
                <?php print displayStatus();?>
                <?php print form_open('admin/auth/permissions/save')?>
                <?php print form_hidden('id',$this->validation->id)?>
                

                <table width="100%" cellspacing="0">
                    <tr>
                        <td width="33%"><b><?php print $this->lang->line('access_groups')?></b></td>
                        <td width="33%"><b><?php print $this->lang->line('access_resources')?></b></td>
                        <td width="33%"><b><?php print $this->lang->line('access_actions')?></b></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <?php
                        // Show regions as readonly?
                            $readonly = ($_POST['id'] == NULL)?'':' readonly';
                            ?>
                            <div class="col-md-4 scrollable_tree <?php print $readonly?>"><ul id="groups"><?php print $this->access_control_model->buildGroupSelector(($_POST['id']!=NULL))?></ul></div>
                            <div class="col-md-4 scrollable_tree <?php print $readonly?>"><ul id="resources"><?php print $this->access_control_model->buildResourceSelector(($_POST['id']!=NULL))?></ul></div>
                            <div class="col-md-4 scrollable_tree " id="access_actions">
                                <?php if ($readonly!= ''):
                                print  $this->access_control_model->buildActionSelectorCustomized($aco_id); 
                                endif; ?>
                            </div>

                        </td>
                    </tr>
                    <tr>

                        <td colspan="3">
                            <b><?php print $this->lang->line('access')?>:</b><br/>
                            <?php print form_radio('allow','Y',$this->validation->set_radio('allow','Y')) . $this->lang->line('access_allow')?>
                            <?php print form_radio('allow','N',$this->validation->set_radio('allow','N')) . $this->lang->line('access_deny')?>
                        </td>
                    </tr>
                </table>
                <br />
                <button type="submit" class="btn btn-success btn-flat btn-xs no-shadow" name="submit" value="submit">
                    <?php print $this->lang->line('general_save')?>
                </button>
                <a href="<?php print site_url('admin/auth/permissions')?>" class="btn btn-success btn-flat btn-xs no-shadow">
                    <?php print $this->lang->line('general_cancel')?>
                </a>
                <?php print form_close()?>

            </div>
        
        </div>
    </section><!-- /.content -->
</aside><!-- /.right-side -->

<script type="text/javascript">
$(document).ready(function(){    
    
    // Create permission trees
    $('#groups').treeview({
        animated: "fast",
        collapsed: true,
        unique: true,
        persist: "cookie",
        toggle: function() {
            
        }
    });

    $('#resources').treeview({
        animated: "fast",
        collapsed: true,
        unique: true,
        persist: "cookie",
        toggle: function() {
            
        }
    });

    /*
    // Get the initial advanced view    
    fetchViewResources($('#access_groups input[name="aro"]').val());    
    
    // When a user picks a differnt access_group load its access_rights in
    // the respective div
    $('#access_groups input[name="aro"]').click(function(){fetchViewResources($(this).val())});
    
    // Function to update the advanced view access rights tree
    function fetchViewResources(group){
        $.post(base_url+'admin/auth/permissions/ajax_fetch_resources/'+group,{'<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
        function(val){
            $('#access_resources').html(val);
            
            // Replace the text in the action div
            $('#access_actions').text('Please select a resource to view its action permissions.');
            
            // Make it so when we click on a resource its actions are displayed
            $('#access_resources span').click(function(){
                var group = $('#access_groups input[name="aro"]:checked').val();
                var resource = $(this).parent().attr('id');
                fetchViewActions(group,resource);
            });       
        });
    }
    
    // Function to fetch ajax actions
    function fetchViewActions(group,resource){
        $.post(base_url+'admin/auth/permissions/ajax_fetch_actions/'+group+'/'+resource,{'<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash(); ?>'},function(val){$('#access_actions').html(val); });
    }

    */
    $('#resources input[name="aco"]').click(function(){
        fetchViewActions($(this).val());
    });

    function fetchViewActions(resource){
        $.post(base_url+'admin/auth/permissions/ajax_fetch_actions_1/'+resource,{'<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash(); ?>'},function(val){
            $('#access_actions').html(val); 
        });
    }
    
});
</script>