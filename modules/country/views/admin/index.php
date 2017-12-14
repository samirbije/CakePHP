<aside class="right-side">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><?php echo lang('country');?></h1>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- row -->
		<div class="row">
			<div class="col-xs-12 connectedSortable">

				<?php print displayStatus();?>

				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridInsert"><?php echo lang('create');?></button>
				<button type="button" class="btn btn-success btn-flat btn-xs" id="jqxGridCountryFilterClear"><?php echo lang('clear');?></button>

				<br /><br />
				<div id="jqxGridCountry"></div>
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
        <?php echo form_open('', array('id' =>'form-country', 'onsubmit' => 'return false')); ?>
        	<input type="hidden" name="id" id="id" />
            <table class="form-table">
                <tr>
                    <td><label for="country"><?php echo lang('country')?><span class="mandatory">*</span></label></td>
                    <td><input id="country" class="text_input" name="country"></td>
                </tr>
                <tr>
                	<td><label for="code"><?php echo lang('code')?><span class="mandatory">*</span></label></td>
                    <td><div id="code" name="code" class="number_general"></div></td>
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
                        <button type="button" class="btn btn-success btn-xs btn-flat" id="jqxCountrySubmitButton"><?php echo lang('general_save'); ?></button>
                        <button type="button" class="btn btn-default btn-xs btn-flat" id="jqxCountryCancelButton"><?php echo lang('general_cancel');?></button>
                    </th>
                </tr>
               
          </table>
        <?php print form_close()?>
    </div>
</div>

<script language="javascript" type="text/javascript">


$(function(){

	var countryDataSource =
	{
		datatype: "json",
		datafields: [
			{ name: 'id', 				type: 'number' },
			{ name: 'country', 			type: 'string' },
			{ name: 'code', 			type: 'string' },
            { name: 'active',           type: 'bool' },
        ],
		url: '<?php echo site_url('admin/country/json')?>',
		pagesize: defaultPageSize,
		root: 'rows',
		id : 'sn',
		cache: true,
		pager: function (pagenum, pagesize, oldpagenum) {
        	//callback called when a page or page size is changed.
        },
        beforeprocessing: function (data) {
        	countryDataSource.totalrecords = data.total;
        },
	    // update the grid and send a request to the server.
	    filter: function () {
	    	$("#jqxGridCountry").jqxGrid('updatebounddata', 'filter');
	    },
	    // update the grid and send a request to the server.
	    sort: function () {
	    	$("#jqxGridCountry").jqxGrid('updatebounddata', 'sort');
	    },
	    processdata: function(data) {
			
	    }
	};

	
	$("#jqxGridCountry").jqxGrid({
		theme: theme_grid,
		width: '100%',
		height: gridHeight,
		source: countryDataSource,
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

			{ text: '<?php echo lang("country");?>', 			datafield: 'country', 			width: 150, filterable: true, renderer: gridColumnsRenderer},
			{ text: '<?php echo lang("code");?>', 				datafield: 'code', 				width: 150, filterable: true, renderer: gridColumnsRenderer},
            { text: '<?php echo lang("active");?>',             datafield: 'active',            width: 75, columntype: 'checkbox', filtertype: 'bool', filterable: true, renderer: gridColumnsRenderer },
		],
		rendergridrows: function (result) {
			return result.data;
		}
	});


	$("[data-toggle='offcanvas']").click(function(e) {
	    e.preventDefault();
	    $("#jqxGridCountry").jqxGrid('refresh');
	});

	$('#jqxGridCountryFilterClear').on('click', function () { 
		$('#jqxGridCountry').jqxGrid('clearfilters');
	});

	$('#jqxGridInsert').on('click', function(){
		openPopupWindow('<?php echo lang("general_add")  . "&nbsp;" .  $header; ?>');
    });

	// initialize the popup window
    $("#jqxPopupWindow").jqxWindow({ 
        theme: theme_window,
        width: 350,
        maxWidth: 350,
        height: 200,  
        isModal: true, 
        autoOpen: false,
        modalOpacity: 0.7,
        showCollapseButton: false 
    });

     $("#jqxCountryCancelButton").on('click', function () {
        $('#id').val('');
        $('#form-country')[0].reset();
        $('#jqxPopupWindow').jqxWindow('close');
    });

    $('#form-country').jqxValidator({
        hintType: 'label',
        animationDuration: 500,
        rules: [
            { input: '#country', message: 'Required', action: 'blur', 
                rule: function(input) {
                    val = $("#country").val();
                    return (val == '' || val == null || val == 0) ? false: true;
                }
            },
            { input: '#code', message: 'Required', action: 'blur', 
                rule: function(input) {
                    val = $("#code").jqxNumberInput('val');
                    return (val == '' || val == null || val == 0) ? false: true;
                }
            },
            { input: '#code', message: 'Code already exists', action: 'blur', 
                rule: function(input, commit) {
                    val = $("#code").val();
                    $.ajax({
                        url: "<?php echo site_url('admin/country/check_duplicate'); ?>",
                        type: 'POST',
                        data: {field: 'code', value:val, id: $('input#id').val()},
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

      $("#jqxCountrySubmitButton").on('click', function () {

        var validationResult = function (isValid) {
                if (isValid) {
                   saveRecord();
                }
            };

        $('#form-country').jqxValidator('validate', validationResult);
       
    });

});


function editRecord(index){

    var row =  $("#jqxGridCountry").jqxGrid('getrowdata', index);
  	if (row) {
        $('#id').val(row.id);
        $('#country').val(row.country);
        $('#code').jqxNumberInput('val', row.code);
        if(row.active == true) {
            $('#active1').prop('checked', 'checked');   
        } else {
            $('#active0').prop('checked', 'checked');   
        }

       openPopupWindow('<?php echo lang("general_edit")  . "&nbsp;" .  $header; ?>');
    }
}

function saveRecord(){
    var data = $("#form-country").serialize();
   
    $.ajax({
        type: "POST",
        url: '<?php echo site_url("admin/country/save")?>',
        data: data,
        success: function (result) {
            var result = eval('('+result+')');
            if (result.success) {
                $('#id').val('');
                $('#form-country')[0].reset();
                $('#jqxGridCountry').jqxGrid('updatebounddata');
                $('#jqxPopupWindow').jqxWindow('close');
            }

        }
    });
}

</script>

