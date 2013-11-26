<?php ?>
<div class="container">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">Unable to Sign In. Please check your username and password</div>
    <?php endif; ?>
    <form class="well form-signin" role="form" method="post" action="<?php echo "$baseurl/$index/mmm/login" ?>" >
        <h3>Please Login</h3>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="edit" name="username" placeholder="Username" class="form-control" autofocus/>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" class="form-control"/>
        </div>
        <button name="submit" type="submit" value="submit" class="btn btn-success">SignIn</button>
        <a href="<?php echo "$baseurl/$index/mmm/register_form" ?>" class="btn btn-primary">Register </a>

    </form>
</div>