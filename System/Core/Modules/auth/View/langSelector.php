<?php if (false) /**@var View**/ $layout = new View('','','') // for code completion ?>
<div style="display: none">
    <form method="post" name="currentUserLocale">
        <?php echo $layout->input()->hidden('setCurrentLocale', 0)?>
        
    </form>
</div>
<script type="text/javascript">
    function setLang(id) {
        $('#setCurrentLocale').val(id);
        document.forms['currentUserLocale'].submit();
    }
</script>
<?php $out = array();
foreach ($langs as $locale)
    if ($locale['is_active'])
        $out[] = '<a href="#" onclick="setLang(\''.$locale['id'].'\')"'.($currentLocale==$locale['id']?' class="active_language"':'').'>'.$locale['name'].'</a>';
?>
<div class="lang">
    <?php echo implode(' | ', $out) ?>
    
</div>