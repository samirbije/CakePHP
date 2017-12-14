<aside class="right-side">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><?php echo lang('backendpro_tools');?><small><?php echo lang('backendpro_module_generator'); ?></small></h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
	        <!-- left column -->
	        <div class="col-md-12">
	            <!-- general form elements -->
	            <div class="box box-solid">
	                <div class="box-header">
	                    <h3 class="box-title">Module Generator</h3>
	                </div><!-- /.box-header -->
	                <!-- form start -->
	                <?php print form_open('', array('id'=>'form-generator','class' => 'horizontal')); ?>
	                    <div class="box-body">

	                        <div class="form-group">
	                            <label for="prefix">Table Prefix (if any)</label>
	                            <input type="text" class="form-control" id="prefix" name='prefix' placeholder="Table Prefix" autocomplete="off" style="display:inline-block; float:right; margin-left:10px; width: 85%;">
	                        </div>

	                        <div class="form-group">
	                        	<label for="tables">Select Language</label><br />
                        		<div class="checkbox" style="display:inline">
                                    <label>
                                    	<input type="hidden" name="language[]" value="english" />
                                    	<input type="checkbox" name="language[]" value="english" checked="checked" disabled />&nbsp;&nbsp;English (Default)&nbsp;&nbsp;
                                    	<input type="text" class="form-control" name="other_language" id="other_language" placeholder="Specify (if any other than English). Use Comma Seprated Values (E.g. japanese, french) for other Language Files"  autocomplete="off" style="display:inline-block; float:right; margin-left:10px; width: 85%;">
                                </div>
	                        </div>

	                        <div class="form-group">
	                        	<label for="tables">Select Database Tables</label><br />
                        		<?php foreach($tables as $table): ?>
								<div class="checkbox" style="display:inline">
                                    <label>
                                    	<input type="checkbox" name="tables[]" id="<?php echo $table?>" value="<?php echo $table?>" />&nbsp;
                                    	<a href="javascript:void()" onclick="getFields('<?php echo $table?>')"><?php echo $table?></a>&nbsp;&nbsp;
                                    </label>                                                
                                </div>
                            	<?php endforeach;?>
	                        </div>

	                        <div class="form-group">
	                        	<label for="tables">Discard Fields&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" style="float:right" onclick="$('#discard').val('');">Clear</a></label>
	                        	<textarea name="discard" id="discard" class="form-control" style="height: 125px;"></textarea>
	                        </div>


	                    </div><!-- /.box-body -->

	                    <div class="box-footer">
	                        <button type="button" class="btn btn-success btn-flat btn-sm" onclick="generate()";>Submit</button>
	                    </div>
	                <?php echo form_close(); ?>
	            </div><!-- /.box -->
	        </div><!--/.col (left) -->
	        
	    </div>

	    <div class="row" id="result-row">
	        <!-- left column -->
	        <div class="col-md-12">
				<div class="box box-solid">
	                <div class="box-header">
	                    <h3 class="box-title">Results</h3>
	                </div><!-- /.box-header -->
	                <div class="box-body" id="results">
	                </div>

	            </div>
	        </div>
	    </div>
	</section><!-- /.content -->
</aside><!-- /.right-side -->


<script>

$(function(){
	$('#result-row').hide();
});


function generate()
{
	// var checked=false;
	// $.each($('.tables'),function(i,o){
	// 	if($(this).is(':checked')){
	// 		checked=true;
	// 	}
	// });
	
	// if(!checked){
	// 	alert('Error: Please select table');
	// 	return false;
	// }
	
	$.ajax({
		url:'<?php echo site_url('admin/tools/mgenerator/generate')?>',
		type:'post',
		data:$('#form-generator').serialize(),
		dataType:'html',
		success:function(data){
			$('#result-row').show();
			$('#results').html(data);
		}
	});		
	
	return false;	
}

function getFields(tablename) {
	
	$.ajax({
		url:'<?php echo site_url('admin/tools/mgenerator/getFields')?>',
		type:'post',
		data:{table:tablename},
		dataType:'html',
		success:function(data){
		$('#discard').val(data);
		}
	});	
}
</script>