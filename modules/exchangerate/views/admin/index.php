<aside class="right-side">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><?php echo lang('exchangerate');?></h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- row -->
		<div class="row">
			<div class="col-xs-12 connectedSortable">

				<?php print displayStatus();?>

				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridInsert"><?php echo lang('create');?></button>
				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridExchangeRateFilterClear"><?php echo lang('clear');?></button>

				<br /><br />
				<div id="jqxGridExchangeRate"></div>
			</div><!-- /.col -->
		</div>
		<!-- /.row -->

	</section><!-- /.content -->
</aside><!-- /.right-side -->


<div id="jqxPopupWindow">
   <div class='jqxExpander-custom-div'>
        <span class='popup_title' id="window_poptup_title"></span>
    </div>
    <div class="form_fields_area">
        <?php echo form_open('', array('id' =>'form-exchangerate', 'onsubmit' => 'return false')); ?>
        	<input type="hidden" name="id" id="id" />
            <table class="form-table">
                <tr>
                    <td><label for="location"><?php echo lang('location')?><span class="mandatory">*</span></label></td>
                    <td><input id="location" class="text_input" name="location"></td>
                </tr>
                <tr>
                	<td valign="top"><label for="exchange_rate"><?php echo lang('exchange_rate')?><span class="mandatory">*</span></label></td>
                    <td>
                    	<div id="exchange_rate" name="exchange_rate" class="number_currency"></div>
                    	<div style="margin-top:5px; font-size:12px; color: #999; line-height:200%">
                    		Note: Exchange Rate are based on US Dollar. <br />
                    		E.g. USD 1 = NPR 100
                    	</div>
                    </td>
                </tr>
                <tr>
                    <td><label for="active"><?php echo lang('active')?><span class="mandatory">*</span></label></td>
                    <td>
                        <input type="radio" value="1" name="active" id="active1" />&nbsp;<?php echo lang("general_yes")?> &nbsp;
                        <input type="radio" value="0" name="active" id="active0" />&nbsp;<?php echo lang("general_no")?>
                    </td>
                </tr>
             
                <tr>
                    <th colspan="4">
                        <button type="button" class="btn btn-success btn-xs btn-flat" id="jqxExchangeRateSubmitButton"><?php echo lang('general_save'); ?></button>
                        <button type="button" class="btn btn-default btn-xs btn-flat" id="jqxExchangeRateCancelButton"><?php echo lang('general_cancel');?></button>
                    </th>
                </tr>
               
          </table>
        <?php print form_close()?>
    </div>
</div>

<script language="javascript" type="text/javascript">


$(function(){

	var exchangerateDataSource =
	{
		datatype: "json",
		datafields: [
			{ name: 'id', 				type: 'number' },
			{ name: 'location', 		type: 'string' },
			{ name: 'exchange_rate', 	type: 'string' },
            { name: 'active',           type: 'bool' },
            { name: 'modified',         type: 'date' },
        ],
		url: '<?php echo site_url('admin/exchangerate/json')?>',
		pagesize: defaultPageSize,
		root: 'rows',
		id : 'id',
		cache: true,
		pager: function (pagenum, pagesize, oldpagenum) {
        	//callback called when a page or page size is changed.
        },
        beforeprocessing: function (data) {
        	exchangerateDataSource.totalrecords = data.total;
        },
	    // update the grid and send a request to the server.
	    filter: function () {
	    	$("#jqxGridExchangeRate").jqxGrid('updatebounddata', 'filter');
	    },
	    // update the grid and send a request to the server.
	    sort: function () {
	    	$("#jqxGridExchangeRate").jqxGrid('updatebounddata', 'sort');
	    },
	    processdata: function(data) {
			
	    }
	};

	
	$("#jqxGridExchangeRate").jqxGrid({
		theme: theme_grid,
		width: '100%',
		height: gridHeight,
		source: exchangerateDataSource,
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

			{ text: '<?php echo lang("location");?>', 			datafield: 'location', 			width: 150, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("exchange_rate");?>', 		datafield: 'exchange_rate', 	width: 150, filterable: true, renderer: gridColumnsRenderer},
            { text: '<?php echo lang("active");?>',             datafield: 'active',            width: 75, columntype: 'checkbox', filtertype: 'bool', filterable: true, renderer: gridColumnsRenderer },
            { text: '<?php echo lang("modified");?>', 			datafield: 'modified', 			width: 150, filterable: false, cellsformat:  formatString_yyyy_MM_dd_HH_mm_ss, renderer: gridColumnsRenderer, cellsalign: 'center'},
		],
		rendergridrows: function (result) {
			return result.data;
		}
	});


	$("[data-toggle='offcanvas']").click(function(e) {
	    e.preventDefault();
	    $("#jqxGridExchangeRate").jqxGrid('refresh');
	});

	$('#jqxGridExchangeRateFilterClear').on('click', function () { 
		$('#jqxGridExchangeRate').jqxGrid('clearfilters');
	});

	$('#jqxGridInsert').on('click', function(){
		$('#active1').prop('checked', 'checked');   
		openPopupWindow('<?php echo lang("general_add")  . "&nbsp;" .  $header; ?>');
    });

	// initialize the popup window
    $("#jqxPopupWindow").jqxWindow({ 
        theme: theme_window,
        width: 450,
        maxWidth: 450,
        height: 250,  
        isModal: true, 
        autoOpen: false,
        modalOpacity: 0.7,
        showCollapseButton: false 
    });

     $("#jqxExchangeRateCancelButton").on('click', function () {
        $('#id').val('');
        $('#form-exchangerate')[0].reset();
        $('#jqxPopupWindow').jqxWindow('close');
    });

    $('#form-exchangerate').jqxValidator({
        hintType: 'label',
        animationDuration: 500,
        rules: [
            { input: '#location', message: 'Required', action: 'blur', 
                rule: function(input) {
                    val = $("#location").val();
                    return (val == '' || val == null || val == 0) ? false: true;
                }
            },
            { input: '#exchange_rate', message: 'Required', action: 'blur', 
                rule: function(input) {
                    val = $("#exchange_rate").jqxNumberInput('val');
                    return (val == '' || val == null || val == 0) ? false: true;
                }
            },
            { input: '#location', message: 'Location already exists', action: 'blur', 
                rule: function(input, commit) {
                    val = $("#location").val();
                    $.ajax({
                        url: "<?php echo site_url('admin/exchangerate/check_duplicate'); ?>",
                        type: 'POST',
                        data: {field: 'location', value:val, id: $('input#id').val()},
                        success: function (result) {
                            var result = eval('('+result+')');
                            return commit(result.success);
                        },
                        error: function(result) {
                            return commit(false);
                        }
                    });
                }
            },
        ]
    });

      $("#jqxExchangeRateSubmitButton").on('click', function () {

        var validationResult = function (isValid) {
                if (isValid) {
                   saveRecord();
                }
            };

        $('#form-exchangerate').jqxValidator('validate', validationResult);
       
    });

});


function editRecord(index){

    var row =  $("#jqxGridExchangeRate").jqxGrid('getrowdata', index);
  	if (row) {
        $('#id').val(row.id);
        $('#location').val(row.location);
        $('#exchange_rate').jqxNumberInput('val', row.exchange_rate);
        if(row.active == true) {
            $('#active1').prop('checked', 'checked');   
        } else {
            $('#active0').prop('checked', 'checked');   
        }

       openPopupWindow('<?php echo lang("general_edit")  . "&nbsp;" .  $header; ?>');
    }
}

function saveRecord(){
    var data = $("#form-exchangerate").serialize();
   
    $.ajax({
        type: "POST",
        url: '<?php echo site_url("admin/exchangerate/save")?>',
        data: data,
        success: function (result) {
            var result = eval('('+result+')');
            if (result.success) {
                $('#id').val('');
                $('#form-exchangerate')[0].reset();
                $('#jqxGridExchangeRate').jqxGrid('updatebounddata');
                $('#jqxPopupWindow').jqxWindow('close');
            }

        }
    });
}

</script>

