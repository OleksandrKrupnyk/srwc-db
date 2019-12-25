/**
 * Обработка события при добавлении всей информации по работе
 */
const
    selectUniver = $("#select-univer"),
    selectAuthors = $("#select-authors"),
    selectLeaders = $("#select-leaders"),
    dataAuthor = $('#data-author'),
    dataLeader = $('#data-leader'),
    authorIdU = $('#author-id_u'),
    leaderIdU = $('#leader-id_u')
;

function getSelector(data) {
    let id = parseInt($(this).find("option:selected").val());
    if (id !== 888) {
        data.fadeOut(300)
            .find(':input').removeAttr('required')
            .find("option:selected").attr("disabled", false);
    } else {
        data.fadeIn(150);
    }
}

function extracted() {
    let id_u = parseInt($(this).find("option:selected").val());
    authorIdU.val(id_u);
    leaderIdU.val(id_u);
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {"action": "lists-authors-and-leaders", "id_u": id_u},
        cache: false,
        success: function (response) {
            let txt = response.split("!");
            //Записать все в тело документа
            selectAuthors.empty()
                .html(txt[0])
                .on('change', function () {
                    getSelector.call(this, dataAuthor);
                });
            selectLeaders.empty()
                .html(txt[1])
                .on('change', function () {
                    getSelector.call(this, dataLeader);
                });
        }
    });
}

extracted.call(selectUniver);
selectUniver.on('change', function () {
    extracted.call(this);
});