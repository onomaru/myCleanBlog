<!-- ログインユーザ用 -->

<div class="status">
    <div class="status_content">
    <a href="<?php echo $base_url; ?>/status/edit/<?php echo $this->escape($status['user_name']);?>/<?php echo $this->escape($status['id']); ?>">
        <?php echo $this->escape($status['title']); ?>
    </a>
    </div>
    <div>

            <?php echo $this->escape($status['created_at']); ?>
        <a href="<?php echo $base_url; ?>/status/delete/<?php echo $this->escape($status['id']); ?>">削除</a>
    </div>
</div>

