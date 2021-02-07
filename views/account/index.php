<?php $this->setLayoutVar('title', 'Account - ID:'.$this->escape($user['user_name'])) ?>


<div class="container">
  <div class="row row-cols-4">
    <div class="col"><h4><a href="<?php echo $base_url; ?>/">Home</a></h4></div>
    <div class="col"><h4><a href="<?php echo $base_url; ?>/account/signout">ログアウト</a></h4></div>
    <div class="col"><h4><a href="<?php echo $base_url; ?>/status/postView">記事投稿</a></h4></div>
    <div class="col"><h4><a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($user['user_name']); ?>">記事編集</a></h4></div>
  </div>
</div>
