<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        
         <li><?= $this->Html->link(__('List Users'), ['controller'=>'users','action' => 'index']) ?></li>
         <li><?= $this->Html->link(__('List Contact'), ['action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="contact view large-9 medium-8 columns content">
    <h3><?= h($contact->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($contact->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Name') ?></th>
            <td><?= h($contact->last_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Organization') ?></th>
            <td><?= h($contact->organization) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($contact->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Reason') ?></th>
            <td><?= h($contact->reason) ?></td>
        </tr>
        
    </table>
    <div class="row">
        <h4><?= __('Text') ?></h4>
        <?= $this->Text->autoParagraph(h($contact->text)); ?>
    </div>
     <?php if($contact->specify) :?>
    <div class="row">
        <h4><?= __('specify') ?></h4>
        <?= $this->Text->autoParagraph(h($contact->specify)); ?>
    </div>
    <?php  endif;?>
</div>
