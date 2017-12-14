<aside class="right-side">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><?php echo lang('smartpit');?></h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- row -->
		<div class="row">
			<div class="col-xs-12 connectedSortable">

				<?php print displayStatus();?>

				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxBtnSmartpitFilterClear"><?php echo lang('clear');?></button>
				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxBtnSmartpitUploadFile">Upload Excel File</button>
				<a class="btn btn-success btn-flat btn-xs" id="download" href="<?php echo site_url('smartpit/admin/download'); ?>">Download Sample</a>

				<br /><br />
				<div id="jqxGridSmartpit"></div>
			</div><!-- /.col -->
		</div>
		<!-- /.row -->
	</section><!-- /.content -->
</aside><!-- /.right-side -->

<div id="jqxPopupFileUploadWindow">
    <div class='jqxExpander-custom-div'>
        <span class='popup_title'>Files Upload</span>
    </div>
    <div id="uploader">
        <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
    </div>
</div>


<script language="javascript" type="text/javascript">

$(function(){	
	var smartpitDataSource =
	{
		datatype: "json",
		datafields: [
			{ name: 'sn', 				type: 'number' },
			{ name: 'smartpit_number', 	type: 'string' },
			{ name: 'barcode_number', 	type: 'string' },
			{ name: 'password', 		type: 'string' },
			{ name: 'number_type', 		type: 'string' },
			{ name: 'status', 			type: 'string' },
			{ name: 'account_id', 	type: 'string' },
			{ name: 'facebook_id', 		type: 'string' },
			
        ],
		url: '<?php echo site_url('admin/smartpit/json')?>',
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
	    filter: function () {
	    	$("#jqxGridSmartpit").jqxGrid('updatebounddata', 'filter');
	    },
	    // update the grid and send a request to the server.
	    sort: function () {
	    	$("#jqxGridSmartpit").jqxGrid('updatebounddata', 'sort');
	    },
	    processdata: function(data) {

	    }
	};

	var array_status = Array('ACTIVE', 'INACTIVE'),
		array_number_type = Array('OFFLINE', 'ONLINE');


	$("#jqxGridSmartpit").jqxGrid({
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
			{ text: '<?php echo lang("smartpit_number");?>',	datafield: 'smartpit_number', 	width: 150, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("barcode_number");?>', 	datafield: 'barcode_number', 	width: 150, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("password");?>', 			datafield: 'password', 			width: 150, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("number_type");?>', 		datafield: 'number_type', 		width: 150, filterable: true, filtertype: 'list', filteritems: array_number_type, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("status");?>', 			datafield: 'status', 			width: 150, filterable: true, filtertype: 'list', filteritems: array_status, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("account_id");?>', 		datafield: 'account_id', 		width: 150, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("facebook_id");?>', 		datafield: 'facebook_id', 		width: 150, filterable: true, renderer: gridColumnsRenderer},
		],
		rendergridrows: function (result) {
			return result.data;
		}
	});

	$('#jqxBtnSmartpitFilterClear').on('click', function () { 
		$('#jqxGridSmartpit').jqxGrid('clearfilters');
	});

	$('#jqxBtnSmartpitUploadFile').on('click', function(){

        var uploader = $('#uploader').plupload('getUploader');
        var x = ($(window).width() - $("#jqxPopupFileUploadWindow").jqxWindow('width')) / 2 + $(window).scrollLeft(),
            y = ($(window).height() - $("#jqxPopupFileUploadWindow").jqxWindow('height')) / 2 + $(window).scrollTop();

        $("#jqxPopupFileUploadWindow").jqxWindow({ position: { x: x, y: y} });
        $("#jqxPopupFileUploadWindow").jqxWindow('open');
    });

    $("#jqxPopupFileUploadWindow").jqxWindow({ 
        theme: theme_window,
        width: '60%',
        maxWidth: '60%',
        height: 350,  
        isModal: true, 
        autoOpen: false,
        modalOpacity: 0.7,
        showCollapseButton: false 
    });

    var uploader = $("#uploader").plupload({
        // General settings
        runtimes : 'html5',
        url : '<?php echo site_url("admin/smartpit/uploadFiles");?>',

        // User can upload no more then 20 files in one go (sets multiple_queues to false)
       // max_file_count: 1,
        
        chunk_size: '10mb',
        
        filters : {
            // Maximum file size
            max_file_size : '10mb',
            // Specify what files to browse for
            mime_types: [
                {title : "CSV Files", extensions : "csv"}
            ]
        },

        // Rename files by clicking on their titles
        rename: true,
        
        // Sort files
        sortable: true,

        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
        dragdrop: true,

        // Views to activate
        views: {
            list: false,
            thumbs: false, // Show thumbs
            active: 'thumbs'
        },

        headers: {
            <?php /*'<?php echo $this->security->get_csrf_token_name();?>': '<?php echo $this->security->get_csrf_hash(); ?>'*/?>
        },

        init : {
            UploadComplete: function(up, files) {
                up.splice();
                $('#jqxGridSmartpit').jqxGrid('updatebounddata');
                $("#jqxPopupFileUploadWindow").jqxWindow('close');
            },
        }
    });

    $('#uploader_start').on('click', function(){
        $('#uploader').plupload('start');
    });

});
</script>