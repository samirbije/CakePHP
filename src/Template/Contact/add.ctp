
<div class="contact form large-9 medium-8 columns content">
    <?= $this->Form->create($contact) ?>
    <fieldset>
        <legend><?= __('Add Contact') ?></legend>
        <?php
        $options = array('Feedback' => 'Feedback', 'Help' => 'Help','HR'=>'HR','Other'=>'Other');
            echo $this->Form->input('name',array('value'=>isset($this->request->data['name'])?htmlspecialchars($this->request->data['name'],ENT_QUOTES):''));
            echo $this->Form->input('last_name',array('value'=>isset($this->request->data['last_name'])?htmlspecialchars($this->request->data['last_name'],ENT_QUOTES):''));
            echo $this->Form->input('organization',array('value'=>isset($this->request->data['organization'])?htmlspecialchars($this->request->data['organization'],ENT_QUOTES):''));
            echo $this->Form->input('email',array('value'=>isset($this->request->data['email'])?htmlspecialchars($this->request->data['email'],ENT_QUOTES):''));
            echo $this->Form->input('text',array('value'=>isset($this->request->data['text'])?htmlspecialchars($this->request->data['name'],ENT_QUOTES):''));
            echo $this->Form->input('reason', array(
			    'options' => $options,
			    'type' => 'select',
			    'empty' => 'Select the Reason',
			    'label' => 'Reason'
			   )
			);
            echo  isset($this->request->data['reason']) && $this->request->data['reason']=='Other'?'<div id="specified" style="display:block;" class="input select required error">':'<div id="specified" style="display:none;" class="input select required error">';
			echo $this->Form->input('specify',array('value'=>isset($this->request->data['specify'])?htmlspecialchars($this->request->data['specify'],ENT_QUOTES):''));
            echo '</div>';
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<?php echo $this->Html->script('http://code.jquery.com/jquery.min.js');?>
<?php echo $this->Html->script('custom');?>