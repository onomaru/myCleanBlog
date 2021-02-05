<?php $this->setLayoutVar('title', $status['user_name']) ?>


<div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-10 mx-auto">
        <p><?php echo $this->escape($status['title']); ?></p>
        <p><?php echo $this->escape($status['body']); ?></p>
        <p>        <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($status['user_name']);
        ?>/status/<?php echo $this->escape($status['id']); ?>">
            <?php echo $this->escape($status['created_at']); ?>
        </a></p>
      </div>
    </div>
  </div>
