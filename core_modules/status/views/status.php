<div class="alert status_box alert-<?php print ($type != 'error') ? $type : 'danger'; ?> alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong><?php print $this->lang->line('status_type_' . $type);?></strong><br /><?php print implode("<br />" , $messages); ?>
</div>


<script>
$(document).ready(function()
{
	$(".status_box").animate({opacity: 1.0}, 5000).fadeOut('slow');
	$('.status_box').click(function()
	{
		$(this).hide();
	});
});
</script>
