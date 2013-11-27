<div class="container well">
    <?php foreach ($suggestion as $key => $value): ?>
        <table class="table table-condensed">
            <tr>
                <td id="suggest<?php echo $value['id'] ?>">
                    <?php echo $value['view'] ?>
                    <p>Is this correct?</p>
                    <form role="form" method="post">
                        <input type="hidden" value="" name="action" id="action"/>
                        <input type="hidden" value="<?php echo $value['id'] ?>" name="id"/> 
                        <input type="button" value="Yes" class="btn btn-success" onclick="insertvalue(this,1)" />
                        <input type="button" value="No" class="btn btn-danger" onclick="insertvalue(this,0)"/>
                        <input type="hidden" value="submit" name="suggestionapproval" />
                    </form>
                </td>
            </tr>
        </table>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    function insertvalue(input, action_id)
    {

        $(input).siblings("#action").val(action_id);
        $(input).parent("form").submit();
        
        return false;
    }
    
</script>