<aside class="right-side">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>POP UP History</h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- row -->
		<div class="row">
			<div class="col-xs-12 connectedSortable">

				<?php print displayStatus();?>

				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridTopupFilterClear"><?php echo lang('clear');?></button>
				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridTopupRefresh"><?php echo lang('refresh'); ?></button>
				

				<br /><br />
				<div id="jqxGridTopup"></div>
			</div><!-- /.col -->
		</div>
		<!-- /.row -->

	</section><!-- /.content -->
</aside><!-- /.right-side -->



<script language="javascript" type="text/javascript">


$(function(){

	var countryDataSource = {
		url : base_url + 'admin/country/combo_json',
        datatype: 'json',
        datafields: [ 
            { name: 'country', 	type: 'string' }, 
            { name: 'id', 		type: 'number' }, 
        ],
       async: false
	},

	countryDataAdapter = new $.jqx.dataAdapter(countryDataSource, { autoBind: true});
	var rechargeDataSource =
	{
		datatype: "json",
		datafields: [
			{ name: 'id', 						type: 'number' },
			{ name: 'account_id', 				type: 'string' },
			{ name: 'language', 				type: 'string' },
			{ name: 'location', 				type: 'string' },
			{ name: 'device', 					type: 'string' },
			{ name: 'status', 					type: 'string' },
			{ name: 'created_date', 			type: 'date' },
        ],
		url: '<?php echo site_url('admin/ads/json')?>',
		pagesize: defaultPageSize,
		root: 'rows',
		id : 'sn',
		cache: true,
		pager: function (pagenum, pagesize, oldpagenum) {
        	//callback called when a page or page size is changed.
        },
        beforeprocessing: function (data) {
        	rechargeDataSource.totalrecords = data.total;
        },
	    // update the grid and send a request to the server.
	    filter: function () {
	    	$("#jqxGridTopup").jqxGrid('updatebounddata', 'filter');
	    },
	    // update the grid and send a request to the server.
	    sort: function () {
	    	$("#jqxGridTopup").jqxGrid('updatebounddata', 'sort');
	    },
	    processdata: function(data) {
	   		filterscount = data.filterscount;
            if (data.filterscount > 0) {
                for(i = 0; i< filterscount; i++  ) {
                    key = 'filterdatafield' + i;
                    val = 'filtervalue' + i;
                    val1 = 'filtercondition' + i;
                    
                    if (data[key] == 'created_date') {
                        data[val] = Date.parse(data[val]).toString('yyyy-MM-dd');
                        data[val1] = 'CONTAINS';
                    } else if (data[key] == 'country_name') {
                        data[key] = 'country_id';
                        for (var j = 0; j < countryDataAdapter.records.length; j++){
                            v = 'filtervalue' + i;
                            if ( countryDataAdapter.records[j].country == data[val]) {
                                data[v] = countryDataAdapter.records[j].id;
                                break;
                            }
                        }
                    } 
                }
            }
	    }
	};

	var array_status = Array('LOCK', 'PENDING', 'FAILED', 'SUCCESSFUL');
	var array_country = new Array();
	$.each(countryDataAdapter.records, function(key,val) {
	        array_country.push(val.country);
	    }); 
	$("#jqxGridTopup").jqxGrid({
		theme: theme_grid,
		width: '100%',
		height: gridHeight,
		source: rechargeDataSource,
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
			{ text: '<?php echo lang("account_id");?>',		datafield: 'account_id', 			width: 125, filterable: true, renderer: gridColumnsRenderer, cellsalign: 'center'},
			{ text: 'language',				datafield: 'language', 				width: 100, filterable: true, renderer: gridColumnsRenderer, cellsalign: 'center'},
			{ text: 'location',			datafield: 'location', 				width: 125, filterable: true, renderer: gridColumnsRenderer, cellsalign: 'center'},
				{ text: 'Device',			datafield: 'device', 				width: 125, filterable: true, renderer: gridColumnsRenderer, cellsalign: 'center'},
			{ text: 'Created date', 		datafield: 'created_date', 		width: 150, filterable: true, filtertype: 'date', columntype: 'date', filtertype: 'date', cellsformat:  formatString_yyyy_MM_dd_HH_mm_ss, renderer: gridColumnsRenderer, cellsalign: 'center'}
		],
		rendergridrows: function (result) {
			return result.data;
		}
	});
	$("[data-toggle='offcanvas']").click(function(e) {
	    e.preventDefault();
	    $("#jqxGridTopup").jqxGrid('refresh');
	});
	$('#jqxGridTopupFilterClear').on('click', function () { 
		$('#jqxGridTopup').jqxGrid('clearfilters');
	});
});

</script>

