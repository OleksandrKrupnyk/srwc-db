<?php

use zukr\base\html\Html;
use zukr\univer\UniverHelper;

$uh = UniverHelper::getInstance();
$univers = $uh->getDropDownListShotFull(
    $uh->getTakePartUniversDropDownList(
        $uh->getUniversIdWhoSendWork()
    )
);
?>
<!-- Отметки на регистрации в 3-м корпусе -->
<!-- Отметки о прибытии на конкурс  -->
<div class="layout">
    <header><a href="action.php">Меню</a></header>
    <header id="update_arrival_works" class="header pointer"
            title="Подвійне клацання для оновлення відміток у таблиці робіт">Реєстрація
        учасників конференції
    </header>
    <?= Html::select('id_u', null, $univers, ['class' => 'w-100', 'size' => 10, 'id' => 'univer_reseption']) ?>
    <div style="display : flex;justify-content: space-around;align-items: stretch">
        <div id="columnAutors">
            <label class="header">Автори (тільки запрошені)</label>
            <div id="selectAutors"></div>
        </div>
        <div id="columnLeaders">
            <label class="header">Супроводжуючі (усі)</label>
            <div id="selectLeaders"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        const listUnivers = $('#univer_reseption'),
            leadersList = $('#columnLeaders'),
            authorsList = $('#columnAutors');
        leadersList.hide();
        authorsList.hide();

        let isLoading = false;
        listUnivers.on('change', function () {
            if (!isLoading) {
                isLoading = true;
                let id_u = parseInt($(this).find("option:selected").val());
                leadersList.slideUp(500);
                authorsList.slideUp(500);
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"action": "list-li-authors-and-leaders", "id_u": id_u},
                    cache: false,
                    success: function (response) {
                        isLoading = false;
                        response = response.split("!");
                        authorsList.slideDown(500);
                        leadersList.slideDown(500);
                        $("#selectAutors").html(response[0]).slideDown(500);
                        $("#selectLeaders").html(response[1]).slideDown(500);
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }
        });

        function changeInvitation(e) {
            console.log(e);
            e.preventDefault();
            if (!isLoading) {
                isLoading = true;
                this.blur();
                let id = parseInt($(this).data("key")),
                    person = $(this).data("person"),
                    value, answer;
                if (e.ctrlKey) {//Нажата Shift значит удалить из приглашенных
                    value = 0;
                    answer = confirm('Виправити помилку?\nВи впевнені?');
                    if (!answer) {
                        isLoading = false;
                        return false;
                    }

                } else {//Без Shift добавить приглашение
                    value = 1;
                    answer = confirm('Відмитити приїзд на конференцію?\nВи впевнені?');
                    if (!answer) {
                        isLoading = false;
                        return false;
                    }
                }
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"action": 'change-arrival', "id": id, "person": person, "value": value},
                    cache: false,
                    success: function (response) {
                        isLoading = false;
                        try {
                            const data = JSON.parse(response);
                            $.notify(data.message || 'No message', data.type || 'error');
                            if (value) {
                                $(this).addClass("option-arrival");
                            } else {
                                $(this).removeClass('option-arrival');
                            }
                        } catch (e) {
                            console.log(e);
                        }
                    }.bind($(this)),
                    error: function (e) {
                        console.log(e);
                    }
                });
            }
        }

        $('#selectAutors').on('dblclick', 'li', function (e) {
            changeInvitation.call(this, e)
        });
        $('#selectLeaders').on('dblclick', 'li', function (e) {
            changeInvitation.call(this, e)
        });

        //обработка собития обновления отметки о прибытии работы при двойном клике на названии меню
        $('#update_arrival_works').on('dblclick', function () {
            if (!isLoading) {
                isLoading = true;
                //console.log('Дважды нажали');
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"action": "update-arrival-works"},
                    cache: false,
                    success: function (txt) {
                        isLoading = false;
                        //console.log('Запрос обработан! Оновлено:'+txt+' запис(ів)');
                        alert('Оновлення записів виконано! Оновлено:' + txt + ' запис(ів)');
                    }
                });
            }
        });
    });
</script>
