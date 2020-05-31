<?php $this->layout('layout', ['title' => 'Users']); ?>

<div class="container">
    <h1><?=$this->e($header)?></h1>
    <?php echo $output = flash()->display(); ?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($users as $user):?>
            <tr>
                <td><?php echo $user['id']?></td>
                <td><?php echo $user['name']?></td>
                <td><?php echo $user['email']?></td>
                <td><a onclick="return confirm('Are you sure?')" href="/delete/<?php echo $user['id']?>" class="btn btn-danger">Delete</a></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>



