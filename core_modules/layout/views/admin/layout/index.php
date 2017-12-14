<div region="center" border="false">
<div style="padding:20px">
<div id="search-panel" class="easyui-panel" title="<?php print lang('layout_search')?>" style="padding:5px" collapsible="true" iconCls="icon-search">
<?php echo form_open('', array('id'=>'layout-search-form')); ?>
<table width="100%" border="1" cellspacing="1" cellpadding="1">
<tr><td><label><?php print lang('name')?></label>:</td>
<td><input type="text" name="search[name]" id="search_name"  class="easyui-validatebox"/></td>
</tr>
  <tr>
    <td colspan="4">
    <a href="javascript:void(0)" class="easyui-linkbutton" id="search" iconCls="icon-search"><?php print lang('search')?></a>  
    <a href="javascript:void(0)" class="easyui-linkbutton" id="clear" iconCls="icon-clear"><?php print lang('clear')?></a>
    </td>
    </tr>
</table>

<?php echo form_close(); ?>
</div>
<br/>
<table id="layout-table" pagination="true" title="<?php print lang('layout')?>" pagesize="20" rownumbers="true" toolbar="#toolbar" collapsible="true"
			 fitColumns="true">
    <thead>
    <th field="checkbox" checkbox="true"></th>
    <th field="layout_id" sortable="true" width="30"><?php print lang('layout_id')?></th>
<th field="name" sortable="true" width="50"><?php print lang('name')?></th>

    <th field="action" width="100" formatter="getActions"><?php print lang('action')?></th>
    </thead>
</table>

<div id="toolbar" style="padding:5px;height:auto">
    <p>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="false" onclick="create()" title="<?php print lang('create_layout')?>"><?php print lang('create')?></a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="false" onclick="removeSelected()"  title="<?php print lang('delete_layout')?>"><?php print lang('remove_selected')?></a>
    </p>

</div> 

<!--for create and edit layout form-->
<div id="dlg" class="easyui-dialog" style="width:300px;height:auto;padding:10px 20px" data-options="closed:true,collapsible:true,modal:true,buttons:'#dlg-buttons'">
    <?php echo form_open('', array('id'=>'form-layout')); ?>
    <table>
		<tr>
		              <td width="34%" ><label><?php print lang('name')?>:</label></td>
					  <td width="66%"><input name="name" id="name" class="easyui-validatebox" required="true"></td>
		       </tr><input type="hidden" name="layout_id"/>
    </table>
    <?php echo form_close(); ?>
	<div id="dlg-buttons">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onClick="save()"><?php print lang('general_save')?></a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onClick="javascript:$('#dlg').window('close')"><?php print lang('general_cancel')?></a>
	</div>    
</div>
<!--div ends-->
   
</div>
</div>
<script language="javascript" type="text/javascript">
	$(function(){
		$('#clear').click(function(){
			$('#layout-search-form').form('clear');
			$('#layout-table').datagrid({
				queryParams:null
				});

		});

		$('#search').click(function(){
			$('#layout-table').datagrid({
				queryParams:{data:$('#layout-search-form').serialize()}
				});
		});		
		$('#layout-table').datagrid({
			url:'<?php print site_url('layout/admin/layout/json')?>',
			height:'auto',
			width:'auto',
			onDblClickRow:function(index,row)
			{
				edit(index);
			}
		});
	});
	
	function getActions(value,row,index)
	{
		var e = '<a href="javascript:void(0)" onclick="edit('+index+')" class="easyui-linkbutton l-btn" iconcls="icon-edit"  title="<?php print lang('edit_layout')?>"><span class="l-btn-left"><span style="padding-left: 20px;" class="l-btn-text icon-edit"></span></span></a>';
		var d = '<a href="javascript:void(0)" onclick="remove('+index+')" class="easyui-linkbutton l-btn" iconcls="icon-remove"  title="<?php print lang('delete_layout')?>"><span class="l-btn-left"><span style="padding-left: 20px;" class="l-btn-text icon-cancel"></span></span></a>';
		return e+d;		
	}
	
	function formatStatus(value)
	{
		if(value==1)
		{
			return 'Yes';
		}
		return 'No';
	}

	function create(){
		//Create code here
		$('#dlg').window('open').window('setTitle','<?php print lang('create_layout')?>');
		$('#form-layout').form('clear');
	}	

	function edit(index)
	{
		var row = $('#layout-table').datagrid('getRows')[index];
		if (row){
			$('#form-layout').form('load',row);
			$('#dlg').window('open').window('setTitle','<?php print lang('edit_layout')?>');
		}
		else
		{
			$.messager.alert('Error','<?php print lang('edit_selection_error')?>');				
		}		
	}
	
		
	function remove(index)
	{
		$.messager.confirm('Confirm','<?php print lang('delete_confirm')?>',function(r){
			if (r){
				var row = $('#layout-table').datagrid('getRows')[index];
				$.post('<?php print site_url('layout/admin/layout/delete_json')?>', {id:[row.layout_id]}, function(){
					$('#layout-table').datagrid('deleteRow', index);
					$('#layout-table').datagrid('reload');
				});

			}
		});
	}
	
	function removeSelected()
	{
		var rows=$('#layout-table').datagrid('getSelections');
		if(rows.length>0)
		{
			selected=[];
			for(i=0;i<rows.length;i++)
			{
				selected.push(rows[i].layout_id);
			}
			
			$.messager.confirm('Confirm','<?php print lang('delete_confirm')?>',function(r){
				if(r){				
					$.post('<?php print site_url('layout/admin/layout/delete_json')?>',{id:selected},function(data){
						$('#layout-table').datagrid('reload');
					});
				}
				
			});
			
		}
		else
		{
			$.messager.alert('Error','<?php print lang('edit_selection_error')?>');	
		}
		
	}
	
	function save()
	{
		$('#form-layout').form('submit',{
			url: '<?php print site_url('layout/admin/layout/form_json')?>',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				var result = eval('('+result+')');
				if (result.success)
				{
					$('#form-layout').form('clear');
					$('#dlg').window('close');		// close the dialog
					$.messager.show({title: '<?php print lang('success')?>',msg: result.msg});
					$('#layout-table').datagrid('reload');	// reload the user data
				} 
				else 
				{
					$.messager.show({title: '<?php print lang('error')?>',msg: result.msg});
				} //if close
			}//success close
		
		});		
		
	}
	
	
</script>