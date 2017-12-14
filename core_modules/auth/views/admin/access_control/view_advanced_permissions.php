<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo lang('backendpro_access_control');?><small><?php echo lang('access_permissions'); ?></small></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php print displayStatus();?>
        

        <table width="100%" cellspacing="0">
            <tr>
                <td width="33%"><b><?php print $this->lang->line('access_groups')?></b></td>
                <td width="33%"><b><?php print $this->lang->line('access_resources')?></b></td>
                <td width="33%"><b><?php print $this->lang->line('access_actions')?></b></td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="col-md-4 advanced_view_tree"><ul id="access_groups" class="treeview treeview-famfamfam"><?php print $this->access_control_model->buildGroupSelector()?></ul></div>
                    <div class="col-md-4 advanced_view_tree"><ul id="access_resources" class="treeview treeview-famfamfam"></ul></div>
                    <div class="col-md-4 advanced_view_tree" id="access_actions"><?php print $this->lang->line('access_advanced_select')?></div>
                </td>
            </tr>
        </table>

        <a href="<?php print site_url('admin/auth/permissions')?>" class="btn btn-success btn-flat btn-xs no-shadow">
            <?php print $this->lang->line('general_cancel')?>
        </a>



    </section><!-- /.content -->
</aside><!-- /.right-side -->


<script type="text/javascript">
$(document).ready(function(){    

    /********************************************* USED TO MANAGE PERMISSIONS */

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


});
</script>