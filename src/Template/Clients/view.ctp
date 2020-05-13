<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Client'), ['action' => 'edit', $client->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Client'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Clients'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Client'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="clients view large-10 medium-9 columns">
    <h2><?= h($client->id) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Companyname') ?></h6>
            <p><?= h($client->companyname) ?></p>
            <h6 class="subheader"><?= __('Contactname') ?></h6>
            <p><?= h($client->contactname) ?></p>
            <h6 class="subheader"><?= __('Contactphone') ?></h6>
            <p><?= h($client->contactphone) ?></p>
            <h6 class="subheader"><?= __('Contactemail') ?></h6>
            <p><?= h($client->contactemail) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Id') ?></h6>
            <p><?= $this->Number->format($client->id) ?></p>
        </div>
    </div>
</div>
