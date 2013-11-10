<div class="container">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $errmsg ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success">Data has been successfully submitted. Thank You!</div>
    <?php endif; ?>
    <form role="form" class="well" enctype="multipart/form-data" method="post">
        <p>Please upload a music file to submit its data</p>
        <div class="form-group">
            <label for="musicfile">Music File</label>
            <input class="form-control" type="file" name="musicfile" id="musicfile" />
            <p class="help-block">Allowed File Formats: MP3, Max Size: 20M</p>
        </div>
        <button type="submit" name="submit" class="btn btn-success">Submit</button>
    </form>
</div>