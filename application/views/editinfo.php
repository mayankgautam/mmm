<div class="container">
    <?php if ($error): ?>
        <div class="alert alert-success">Information Updated Successfully</div>
    <?php endif; ?>
    <form role="form" method="post" name="editinfo" id="editinfo" class="well">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="edit" id="name" name="name" class="form-control" value="<?php echo $song->title ?>"/>
        </div>
        <div class="form-group">
            <label for="album">Album</label>
            <input type="edit" id="album" name="album" class="form-control" value="<?php echo $song->album ?>"/>
        </div>
        <div class="form-group">
            <label for="artist">Artist</label>
            <input type="edit" id="artist" name="artist" class="form-control" value="<?php echo $song->artist ?>"/>
        </div>
        <button class="btn btn-success" name="submit">Save</button>
    </form>
</div>

<script type="text/javascript">
    $("#editinfo").validate({
        rules: {
            name: "required",
            album: "required",
            artist: "required"
        },
        messages: {
            name: "You cannot leave song name blank",
            album: "You cannot leave album name blank",
            artist: "You cannot leave artist field blank"
        }
    });
    
</script>