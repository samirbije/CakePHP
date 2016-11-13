<br>
<div class="index large-4 medium-4 large-offset-4 medium-offset-4 columns">
    <div class="panel">
    	<h2 class="text-center"> Login </h2>
    	<?php echo $this->Form->create();?>
		<?php echo $this->Form->input('email'); ?>
		<?php echo $this->Form->input('password',array('type'=>'password')); ?>
		<?php echo $this->Form->Submit('login',array('class'=>'button')); ?>
		<?php echo $this->Form->end; ?>
	</div>
</div>
