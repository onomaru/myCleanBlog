<?php $this->setLayoutVar('title', 'Home') ?>



<!-- <form action="<?php echo $base_url; ?>/status/post" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />

    <?php if (isset($errors) && count($errors) > 0): ?>
    <?php echo $this->render('errors', array('errors' => $errors)) ?>
    <?php endif; ?>

    <textarea name="body" rows="2" cols="60"><?php echo $this->escape($body); ?></textarea>
    <p>
        <input type="submit" value="発言" />
    </p>
</form> -->




<div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-10 mx-auto">
        <p>
            <div id="statuses">
                <?php foreach ($statuses as $status): ?>
                <?php echo $this->render('status/status', array('status' => $status)); ?>
                <?php endforeach; ?>
            </div>
        </p>
      </div>
    </div>
  </div>

