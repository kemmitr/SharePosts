<?php require APPROOT . '/views/inc/header.php'; ?>
    <a href="<?php echo URLROOT; ?>/posts" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
    <br>
    <h1>Title: <?php echo $data['post']->title; ?></h1>
<div class="bg-secondary text-white p-2">
    Written By <?php echo $data['user']->name; ?> on <?php echo $data['user']->created_at; ?>
</div>
<p><?php echo $data['post']->body;?></p>
<?php if ($data['post']->userd_id == $_SESSION['user_id']) :?>
    <hr>
    <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post']->id; ?>" class="btn btn-dark btn-sm">Edit</a>
    <form action="<?php echo URLROOT;?>/posts/delete/<?php echo $data['post']->id; ?>" method="post" class="float-right">
        <input type="submit" class="btn btn-danger btn-sm" value="Delete">
    </form>
<?php endif;?>
<?php require APPROOT . '/views/inc/footer.php'; ?>