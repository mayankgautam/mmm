<div class="container well">
    <?php if (isset($song)): ?>
        <h3>Search By Song Name</h3>
        <table class="table">
            <tr>
                <th>
                    Song
                </th>
                <th>
                    Artist
                </th>
                <th>
                    Album
                </th>
            </tr>
            <?php foreach ($song as $row): ?>
                <tr>
                    <td>
                        <?php echo $row->title ?>
                    </td>
                    <td>
                        <?php echo $row->artist ?>
                    </td>
                    <td>
                        <?php echo $row->album ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <?php if (isset($album)): ?>
        <h3>Search By Album Name</h3>
        <table class="table">
            <tr>
                <th>
                    Song
                </th>
                <th>
                    Artist
                </th>
                <th>
                    Album
                </th>
            </tr>
            <?php foreach ($album as $row): ?>
                <tr>
                    <td>
                        <?php echo $row->title ?>
                    </td>
                    <td>
                        <?php echo $row->artist ?>
                    </td>
                    <td>
                        <?php echo $row->album ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <?php if (isset($artist)): ?>
        <h3>Search By Artist Name</h3>
        <table class="table">
            <tr>
                <th>
                    Song
                </th>
                <th>
                    Artist
                </th>
                <th>
                    Album
                </th>
            </tr>
            <?php foreach ($artist as $row):
                ?>
                <tr>
                    <td>
                        <?php echo $row->title ?>
                    </td>
                    <td>
                        <?php echo $row->artist ?>
                    </td>
                    <td>
                        <?php echo $row->album ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>