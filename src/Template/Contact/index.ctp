<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller'=>'users','action' => 'index']) ?></li>
        <!--<li><?= $this->Html->link(__('New Contact'), ['action' => 'add']) ?></li>-->
    </ul>
</nav>
<div class="contact index large-9 medium-8 columns content">
    <h3><?= __('Contact') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('last_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('organization') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('reason') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contact as $contact): ?>
            <tr>
                <td><?= $this->Number->format($contact->id) ?></td>
                <td><?= h($contact->name) ?></td>
                <td><?= h($contact->last_name) ?></td>
                <td><?= h($contact->organization) ?></td>
                <td><?= h($contact->email) ?></td>
                <td><?= h($contact->reason) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $contact->id]) ?>
                    <!--<?= $this->Html->link(__('Edit'), ['action' => 'edit', $contact->id]) ?>-->
                    <!--<?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $contact->id], ['confirm' => __('Are you sure you want to delete # {0}?', $contact->id)]) ?>-->
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
