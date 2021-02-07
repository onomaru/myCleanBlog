<?php $this->setLayoutVar('title', 'Edit') ?>

<form action="<?php echo $base_url; ?>/status/update/<?php echo $this->escape($status['user_name']); ?>/<?php echo $this->escape($status['id']); ?>" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />

    <?php if (isset($errors) && count($errors) > 0): ?>
    <?php echo $this->render('errors', array('errors' => $errors)) ?>
    <?php endif; ?>

    <h2>記事編集</h2>
    <!-- タイトル -->
    <div class="control-group">
            <div class="form-group floating-label-form-group controls">
              <label>Name</label>
              <input type="text" class="form-control" placeholder="title" id="name" name="title" required data-validation-required-message="Please enter your title." value="<?php echo $this->escape($status['title']); ?>">
              <p class="help-block text-danger"></p>
            </div>
          </div>
    <!-- 記事内容 -->
    <div class="control-group">
            <div class="form-group floating-label-form-group controls">
              <label>article</label>
              <textarea rows="5" class="form-control" placeholder="article" id="message" name="body" required data-validation-required-message="Please enter a message."><?php echo $this->escape($status['body']); ?></textarea>
              <p class="help-block text-danger"></p>
            </div>
    </div>
    <br>

    <p>
        <!-- <input type="submit"　class="btn btn-primary" id="sendMessageButton" value="発言" /> -->
        <button type="submit" class="btn btn-primary" id="sendMessageButton">Send</button>

    </p>

</form>

