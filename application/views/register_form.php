<div class="container">

    <form role="form" method="post" action="<?php echo "$baseurl/$index/mmm/register" ?>" id="registerform" class="well">
        <h3>Please enter your credentials</h3>
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="edit" name="name" id="name" class="form-control" placeholder="Full Name" />
        </div>
        <div class="form-group">
            <label for="username" >Username</label>
            <input type="edit" name="username" id="username" class="form-control" placeholder="Username"/>
            <?php if (isset($error_username)): ?>
                <p class="help-block">Username Already Taken. Please choose another name</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password"/>
        </div>
        <input type="submit" value="register" name="registerbutton" id="registerbutton" class="btn" />
    </form>
</div>

<script type="text/javascript">
    $("#registerform").validate({
        rules: {
            name: "required",
            username: "required",
            password: "required"
        },
        messages: {
            name: "Please enter your name",
            username: "Please enter username",
            password: "Please enter a password"
        }
    });
</script>