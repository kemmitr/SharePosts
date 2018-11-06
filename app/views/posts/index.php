<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('post_message');?>
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="text-left">Posts</h1>
        </div>
        <div class="col-md-6">
            <a href="<?php echo URLROOT;?>/posts/add" class="btn btn-primary float-right">
                <i class="fa fa-pencil"></i>Add Post
            </a>
        </div>
    </div>
    <?php foreach ($data['posts'] as $p) :?>
          <div class="card card-body mb-3">
              <h4 class="card-title"><?php echo $p->title; ?></h4>
              <div class="bg-light p-2 mb-3">
                  <span class="float-left">Written by <?php echo $p->name;?></span>
                  <span class="float-right"><?php echo $p->date_written;?></span>
              </div>
              <p class="card-text">
                  <?php echo $p->body;?>
              </p>
              <div class="float-left">
                    <a href="<?php echo URLROOT;?>/posts/show/<?php echo $p->postId;?>" class="btn btn-dark">More</a>
              </div>
          </div>
    <?php endforeach ;?>
<?php require APPROOT . '/views/inc/footer.php'; ?>