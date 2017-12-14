<aside class="right-side">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>POPUP</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<!-- row -->
		<div class="row">
			<div class="col-xs-12 connectedSortable">

				<?php print displayStatus();?>

				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridUsersCreate" onClick="location.href='<?php echo site_url('admin/advertise/form');?>'"><?php echo lang('create'); ?></button>
				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridPopupRefresh"><?php echo lang('refresh'); ?></button>
				<br /><br />
				<div id="jqxGridPopup"></div>
			</div><!-- /.col -->
		</div>
		<!-- /.row -->
	</section><!-- /.content -->
</aside><!-- /.right-side -->

<div id="jqxPopupWindow" class="content-header">
    <div class='jqxExpander-custom-div'>
        <span class='popup_title' id="window_poptup_title">Bulk Upload</span>
    </div>
</div>

<script language="javascript" type="text/javascript">

$(function(){	
	var smartpitDataSource =
	{
		datatype: "json",
		datafields: [
			{ name: 'id', 				type: 'number' },
			{ name: 'location_name', 	type: 'string' },
			{ name: 'image', 			type: 'string' },
			{ name: 'language_name', 	type: 'string' },
			{ name: 'created_date', 	type: 'string' },
			{ name: 'xml', 				type: 'string' },
        ],
		url: '<?php echo site_url('admin/advertise/json')?>',
		pagesize: defaultPageSize,
		root: 'rows',
		id : 'sn',
		cache: true,
		pager: function (pagenum, pagesize, oldpagenum) {
        	//callback called when a page or page size is changed.
        },
        beforeprocessing: function (data) {
        	smartpitDataSource.totalrecords = data.total;
        },
	    // update the grid and send a request to the server.
	    sort: function () {
	    	$("#jqxGridPopup").jqxGrid('updatebounddata', 'sort');
	    },
	    processdata: function(data) {

	    }
	};

	var array_status = Array('ACTIVE', 'INACTIVE'),
		array_number_type = Array('OFFLINE', 'ONLINE');


	$("#jqxGridPopup").jqxGrid({
		theme: theme_grid,
		width: '100%',
		height: gridHeight,
		source: smartpitDataSource,
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
					e = '<a href="javascript:void(0)" onclick="deleteRecord(' + index + '); return false;" title="delete"><i class="glyphicon glyphicon-edit"></i></a>';
					return '<div style="text-align: center; margin-top: 8px;">' + e + '</div>';
				}
			},
			{ text: 'Location Name',	datafield: 'location_name', 	width: 150, filterable: true, renderer: gridColumnsRenderer},
				{ text: 'Images/MP4', 			datafield: 'image', 			width: 150, filterable: true,  renderer: gridColumnsRenderer},
			{ text: 'Created Date', 	datafield: 'created_date', 	width: 150, filterable: true, renderer: gridColumnsRenderer},
			{ text: 'Language Name', 			datafield: 'language_name', 			width: 150, filterable: true,  renderer: gridColumnsRenderer},
			{ text: 'XML', 			datafield: 'xml', 			width: 150, filterable: true,  renderer: gridColumnsRenderer},
		],
		rendergridrows: function (result) {
			return result.data;
		}
	});
	$("[data-toggle='offcanvas']").click(function(e) {
	    e.preventDefault();
	    $("#jqxGridPopup").jqxGrid('refresh');
	});
    // initialize the popup window
    $("#jqxPopupWindow").jqxWindow({ 
        theme: theme_window,
        width: 700,
        maxWidth: 700,
        height: 525,  
        isModal: true, 
        autoOpen: false,
        modalOpacity: 0.7,
        showCollapseButton: false 
    });
	$('#jqxGridPopupRefresh').on('click', function () {
		$('#jqxGridPopup').jqxGrid('refresh');
	});
});
function editRecord(index){
    var datarow = $("#jqxGridPopup").jqxGrid('getrowdata', index);
    window.location = '<?php echo site_url("admin/advertise/form");?>' + '/' + datarow.id;
}
function deleteRecord(index){
		if (confirm('Are you sure you want to Delete this?')) {
	var datarow = $("#jqxGridPopup").jqxGrid('getrowdata', index);
		$.ajax({
        type: "POST",
        url: '<?php echo site_url("admin/advertise/delete")?>/' +  datarow.id,
        cache: false,
        success: function (result) {
                $('#jqxGridPopup').jqxGrid('updatebounddata');
        }
    });
    }
};
</script>