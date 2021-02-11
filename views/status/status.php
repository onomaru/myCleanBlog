

  <!-- Main Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-10 mx-auto">
        <div class="post-preview">
          <a href="post.html">
            <h2 class="post-title">
            <a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($status['user_name']);
            ?>/status/<?php echo $this->escape($status['id']); ?>"><?php echo $this->escape($status['title']); ?>
            </h2>
          </a>
          <p class="post-meta">Posted by
            <a href="#"><a href="<?php echo $base_url; ?>/general/user/<?php echo $this->escape($status['user_name']); ?>">
            <?php echo $this->escape($status['user_name']); ?>
        </a></a>
            <?php echo $this->escape($status['created_at']); ?></p>
        </div>
        <hr>
        
        <!-- Pager -->
        <!-- <div class="clearfix">
          <a class="btn btn-primary float-right" href="#">Older Posts &rarr;</a>
        </div> -->
      </div>
    </div>
  </div>
