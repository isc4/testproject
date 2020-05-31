<?php $this->layout('layout', ['title' => 'Add users']) ?>
<div class="container">
    <h1><?=$this->e($header)?></h1>
    <?php echo $output = flash()->display(); ?>
    <form action="/create" method="post">
        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="name" value="">
        </div>
        <div class="form-group">
            <input type="text" name="email" class="form-control" placeholder="email" value="">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Add user</button>
            <a href="/" class="btn btn-link">Go to back</a>
        </div>
    </form>
</div>



