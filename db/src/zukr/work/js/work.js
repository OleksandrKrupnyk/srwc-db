$(document).ready(function () {
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
    let isLoading = false;

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

    /*Добавление всей работы*/
    if (selectUniver.length === 1) {
        extracted.call(selectUniver);
        selectUniver.on('change', function () {
            extracted.call(this);
        });
    }
    /* Изменение приглашения */
    const invitations = $('#tableInvitationSection :checkbox[name=invitation]');

    if (invitations.length > 0) {
        invitations.on('click', function () {
            if (!isLoading) {
                isLoading = true;
                let id_w = parseInt($(this).parents('tr').data('key')),
                    invitation = ($(this).is(':checked')) ? "1" : "0",
                    $ckb = $(this);
                $ckb.attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"id_w": id_w, "action": "change-work-invitation", "invitation": invitation},
                    cache: false,
                    success: function (response) {
                        isLoading = false;
                        try {
                            const data = JSON.parse(response);
                            $.notify(data.message || 'No message', data.type || 'error');
                            $ckb.removeAttr('disabled');
                        } catch (e) {
                            console.log(e);
                        }
                    },
                    error: function (e) {
                        console.log(e);
                        $ckb.removeAttr('disabled');
                    }
                });
            }
        });
    }
    //Изменение секции
    const sections = $('#tableInvitationSection select[name="sections"]');
    if (sections.length > 0) {
        sections.on('change', function () {
            if (!isLoading) {
                isLoading = true;
                let id_sec = parseInt($(this).find("option:selected").val()),
                    id_w = parseInt($(this).parents('tr').data('key')),
                    $select = $(this);
                $select.attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"id_w": id_w, "action": "change-work-section", "id_sec": id_sec},
                    cache: false,
                    success: function (response) {
                        isLoading = false;
                        try {
                            const data = JSON.parse(response);
                            $.notify(data.message || 'No message', data.type || 'error');
                            $select.removeAttr('disabled');
                        } catch (e) {
                            console.log(e);
                        }

                    },
                    error: function (e) {
                        console.log(e);
                        $select.removeAttr('disabled');
                    }
                });
            }
        });//Окончание Изменение секции
    }


});