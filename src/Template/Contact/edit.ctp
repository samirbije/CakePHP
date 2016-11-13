<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $contact->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $contact->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Contact'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="contact form large-9 medium-8 columns content">
    <?= $this->Form->create($contact) ?>
    <fieldset>
        <legend><?= __('Edit Contact') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('last_name');
            echo $this->Form->input('organization');
            echo $this->Form->input('email');
            echo $this->Form->input('text');
            echo $this->Form->input('reason');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
