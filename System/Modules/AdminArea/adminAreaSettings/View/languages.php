<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
    <form method="post">
        <?php echo $layout->input()->hidden('rmLang', 0) ?>
        <?php echo $layout->input()->hidden('setDefault', 0) ?>
        <?php echo $layout->input()->hidden('toggleLang', 0) ?>
        <div class="align_left">
            ISO<br>
            <input type="text" class="linktext" name="newIso" />
        </div>
        <div class="align_left">
            language<br>
            <input type="text" class="linktext" name="newLang" />
        </div>
        <div class="align_left">
            <input type="submit" name="add" class="button" value="Добавить язык">
        </div>

        <br style="clear:right">
        <table class="admintable">
            <thead>
            <tr>
                <th>language</th>
                <th>default</th>
                <th>publish</th>
                <th>delete</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($langs) foreach ($langs as $lang): ?>
            <tr>
                <td align="left"><?=$lang['name'] ?>(<?=$lang['lang'] ?>)</td>
                <td><input type="image" src="/img/star_<?php echo $lang['id']!=$layout->lang()->getDefault()?'no':'' ?>active.png" onclick="$('#setDefault').val(<?=$lang['id'] ?>)"></td>
                <td><input type="image" src="/img/<?php echo $lang['is_active']?'':'un' ?>publish.png" onclick="$('#toggleLang').val(<?=$lang['id'] ?>)"></td>
                <td><input type="image" src="/img/delete.png" onclick="$('#rmLang').val(<?=$lang['id'] ?>)"></td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <br style="clear:right">
    </form>