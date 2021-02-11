<?php $this->setLayoutVar('title', $user['user_name']) ?>


<div id="statuses">
    <?php foreach ($statuses as $status): ?>
    <?php echo $this->render('status/status', array('status' => $status)); ?>
    <?php endforeach; ?>
</div>