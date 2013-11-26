<div class="container">
    <table class="table table-hover">
        <tr>
            <th>
                Name
            </th>
            <th>
                Artist
            </th>
            <th>
                Album
            </th>
            <th>
                Modify
            </th>
        </tr>
        <?php foreach ($songs as $item): ?>
            <tr>
                <td custom="editablefields">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $item->title ?></p>
                                <input type="edit" value="<?php echo $item->title ?>" class="hide" />
                            </div>
                            <div class="col-sm-2">
                                <a href="#">
                                    <span class="glyphicon glyphicon-pencil hide" title="Edit"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
                <td custom="editablefields">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $item->artist ?></p>
                                <input type="edit" value="<?php echo $item->artist ?>" class="hide" />
                            </div>
                            <div class="col-sm-2">
                                <a href="#">
                                    <span class="glyphicon glyphicon-pencil hide"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
                <td custom="editablefields">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10">
                                <p class="form-control-static"><?php echo $item->album ?></p>
                                <input type="edit" value="<?php echo $item->album ?>" class="hide" />
                            </div>
                            <div class="col-sm-2">
                                <a href="#">
                                    <span class="glyphicon glyphicon-pencil hide"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="editinfo/<?php echo $item->id ?>" class="btn btn-info" >EDIT</a>
                        <a href="deleteinfo/<?php echo $item->id ?>" class="btn btn-danger">DELETE</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>