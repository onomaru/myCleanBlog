<?php $this->setLayoutVar('title', 'Edit - ID:'.$user['user_name']) ?>


<div id="statuses">
    <?php foreach ($statuses as $status): ?>
    <?php echo $this->render('status/status2', array('status' => $status)); ?>
    <?php endforeach; ?>
</div>