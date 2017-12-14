<aside class="right-side">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><?php echo lang('backendpro_system');?><small><?php echo lang('backendpro_users'); ?></small></h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- row -->
		<div class="row">
			<div class="col-xs-12 connectedSortable">

				<?php print displayStatus();?>

				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridUsersCreate" onClick="location.href='<?php echo site_url('admin/auth/users/form');?>'"><?php echo lang('create'); ?></button>
				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridUsersFilterClear"><?php echo lang('clear');?></button>
				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridUsersRefresh"><?php echo lang('refresh'); ?></button>
				
				<br /><br />
				<div id="jqxGridUsers"></div>
			</div><!-- /.col -->
		</div>
		<!-- /.row -->
	</section><!-- /.content -->
</aside><!-- /.right-side -->


<script language="javascript" type="text/javascript">

$(function(){

	
	var groupDataSource =
	{
		datatype: "json",
		datafields: [
		    { name: 'id', type: 'number' },
		    { name: 'name', type: 'string' },
		],
		url: '<?php echo site_url('admin/auth/users/group_combo_json')?>',
		async: false,
		root: 'records',
		id : 'id'
	},

	groupDataAdapter = new $.jqx.dataAdapter(groupDataSource, { autoBind: true }),

	userSource =
	{
		datatype: "json",
		datafields: [
			{ name: 'id', type: 'number' },
			{ name: 'username', type: 'string'} ,
			{ name: 'email', type: 'string'} ,
			{ name: 'group_id', value: 'group_id', values: { source: groupDataAdapter.records, value: 'id', name: 'name'}, type: 'string' }, 
			{ name: 'active', type: 'bool'} ,
			{ name: 'last_visit', type: 'date'}
			
        ],
		url: '<?php echo site_url('admin/auth/users/json')?>',
		pagesize: defaultPageSize,
		root: 'records',
		id : 'id',
		cache: true,
		pager: function (pagenum, pagesize, oldpagenum) {
        	//callback called when a page or page size is changed.
        },
        beforeprocessing: function (data) {
        	userSource.totalrecords = data.total;
        },
	    // update the grid and send a request to the server.
	    filter: function () {
	    	$("#jqxGridUsers").jqxGrid('updatebounddata', 'filter');
	    },
	    // update the grid and send a request to the server.
	    sort: function () {
	    	$("#jqxGridUsers").jqxGrid('updatebounddata', 'sort');
	    },
	    processdata: function(data) {
	        filterscount = data.filterscount;
	        if (data.filterscount > 0) {
	            for(i = 0; i< filterscount; i++  ) {
	                key = 'filterdatafield' + i;
	                val = 'filtervalue' + i;
	                
	                if (data[key] == 'group_id') {
	                    for (var j = 0; j < groupDataAdapter.records.length; j++){
	                    v = 'filtervalue' + i;
	                
	                        if ( groupDataAdapter.records[j].name == data[val]) {
	                            data[v] = groupDataAdapter.records[j].id;
	                            break;
	                        }
	                    }
	                }
	            }
	        }
	    }
	},

	userDataAdapter = new $.jqx.dataAdapter(userSource),

	array_group = new Array();

	$.each(groupDataAdapter.records, function(key,val) {
    	array_group.push(val.name);
	});

	var rowStyler = function (row, column, value, defaultHtml) {
	    if (row == 0 || row == 2 || row == 5) {
	        var element = $(defaultHtml);
	        element.css('color', '#999');
	        return element[0].outerHTML;
	    }

	    return defaultHtml;
	}

	$("#jqxGridUsers").jqxGrid({
		theme: theme_grid,
		width: '100%',
		height: gridHeight,
		source: userDataAdapter,
		altrows: true,
		pageable: true,
		sortable: true,
		rowsheight: 30,
		columnsheight:30,
		showfilterrow: true,
		filterable: true,
		columnsresize: true,
		autoshowfiltericon: true,
		columnsreorder: true,
		selectionmode: 'none',
		virtualmode: true,
		enableanimations: false,
		pagesizeoptions: pagesizeoptions,
		columns: [
			{ text: 'SN', width: 50, pinned: true, exportable: false,  columntype: 'number', cellclassname: 'jqx-widget-header', renderer: gridColumnsRenderer, cellsrenderer: rownumberRenderer , filterable: false},
			{
				text: 'Action', datafield: 'action', width:75, sortable:false,filterable:false, pinned:true, align: 'center' , cellsalign: 'center', cellclassname: 'grid-column-center', 
				cellsrenderer: function (index) {
					var e = '';
					e = '<a href="javascript:void(0)" onclick="editRecord(' + index + '); return false;" title="<?php echo lang("general_edit"); ?>"><i class="glyphicon glyphicon-edit"></i></a>';
					
					return '<div style="text-align: center; margin-top: 8px;">' + e + '</div>';
				}
			},
			<?php /*?>
			
			<?php */ ?>
			{ text: '<?php echo lang("username");?>', datafield: 'username', width: 150, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("email");?>', datafield: 'email', width: 200, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("group");?>', datafield: 'group_id', width: 150,  filtertype: 'list', filteritems: array_group, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("active");?>', datafield: 'active', width: 75, columntype: 'checkbox', filtertype: 'bool', filterable: true, renderer: gridColumnsRenderer },
			{ text: '<?php echo lang("last_visit");?>', datafield: 'last_visit', width: 150, filterable: true, filtertype:'date', renderer: gridColumnsRenderer, cellsformat: formatString_yyyy_MM_dd_HH_mm, cellsalign: 'center',  }
		],
		rendergridrows: function (result) {
			return result.data;
		}
	});


	$("[data-toggle='offcanvas']").click(function(e) {
	    e.preventDefault();
	    $("#jqxGridUsers").jqxGrid('refresh');
	});

	$('#jqxGridUsersFilterClear').on('click', function () { 
		$('#jqxGridUsers').jqxGrid('clearfilters');
	});

	$('#jqxGridUsersRefresh').on('click', function () { 
		$('#jqxGridUsers').jqxGrid('refresh');
	}); 


	

});

function editRecord(index) {
    var datarow = $("#jqxGridUsers").jqxGrid('getrowdata', index);
    window.location = '<?php echo site_url("admin/auth/users/form");?>' + '/' + datarow.id;
}

</script>