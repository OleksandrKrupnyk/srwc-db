<?php


use zukr\base\html\HtmlHelper;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$template = (new \zukr\template\TemplateRepository())->getById($id);
if (empty($template) || !$id) {
    Go_page('error');
}
?>

<header><a href="action.php?action=template_list">Список блоків</a></header>
<header>Редагування блоку сторінки</header>
<form class="editAutor form" method="post" action="action.php">

    <label>Ім'я блоку : <?=$template['name']?></label><br/>
    <label>Версія блоку</label>
    <input type="text"
           name="Template[version]"
           title="Версія блоку"
           value="<?= $template['version'] ?>" required/>
    <br/>
    <label for="editor">Вміст блоку</label>
    <textarea name="Template[content]"
              id="editor"
              placeholder=""
              class="w-100"><?= $template['content'] ?></textarea>
    <br/>
    <label>Доступні параметри</label>
    <input type="text"
           name="Template[params]"
           title="Доступні параметри"
           value="<?= $template['params'] ?>">
<br/>
    <label>Опубліковано</label>
    <?= HtmlHelper::checkbox('Template[published]', 'Опубліковано', $template['published']) ?>
    <br>
    <input type="submit" value="Зберегти та вийти" name="save+exit">
    <input type="submit" value="Зберегти" name="save">
    <input type="hidden" name="action" value="template_edit">
    <input type="hidden" name="Template[id]" value="<?= $template['id'] ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
</form>
<script>

    if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
        CKEDITOR.tools.enableHtml5Elements( document );

    // The trick to keep the editor in the sample quite small
    // unless user specified own height.
    CKEDITOR.config.height = 300;
    CKEDITOR.config.width = 'auto';
    CKEDITOR.config.font_names =
        'Helvetica, Arial, sans-serif;' +
        'Times New Roman/Times New Roman, Times, serif;' +
        'Verdana';
    var initSample = ( function() {
        var wysiwygareaAvailable = isWysiwygareaAvailable(),
            isBBCodeBuiltIn = !!CKEDITOR.plugins.get( 'bbcode' );

        return function() {
            var editorElement = CKEDITOR.document.getById( 'editor' );

            // :(((
            if ( isBBCodeBuiltIn ) {
                editorElement.setHtml(
                    'Hello world!\n\n' +
                    'I\'m an instance of [url=https://ckeditor.com]CKEditor[/url].'
                );
            }

            // Depending on the wysiwygarea plugin availability initialize classic or inline editor.
            if ( wysiwygareaAvailable ) {
                CKEDITOR.replace( 'editor' ,{
                    language: 'uk-ua'
                });
            } else {
                editorElement.setAttribute( 'contenteditable', 'true' );
                CKEDITOR.inline( 'editor' );

                // TODO we can consider displaying some info box that
                // without wysiwygarea the classic editor may not work.
            }
        };

        function isWysiwygareaAvailable() {
            // If in development mode, then the wysiwygarea must be available.
            // Split REV into two strings so builder does not replace it :D.
            if ( CKEDITOR.revision == ( '%RE' + 'V%' ) ) {
                return true;
            }

            return !!CKEDITOR.plugins.get( 'wysiwygarea' );
        }
    } )();
    initSample();
</script>