//выполнять если документ полностью загрузился
$(document).ready(function () {

    const $body = $('body');
    $('#columnAutors').css("display", "none");
    $('#columnLeaders').css("display", "none");
//$('.loginForm').css("display","none");


    const disableObject = $('li').find('.special').parent();
    disableObject.hide();


    //Проверяем все select что бы не были равны -1
    //Действия для кнопки отправить
    $(':submit').on('click', function (e) {
        //Выбираем все select
        $(':selected').each(function (index, obj) {
            var required = $(this).parent().attr('required') || '';
            if (required !== '') {
                const value = obj.value;
                //если оно равно -1 значит не выбрано
                if (value === '-1' || value === null) {
                    //отменяем действие по умолчанию
                    e.preventDefault();
                    //сообщение пользователю
                    $(this).parent().notify('Поле не заповнене', 'error');
                    //Фокус на поле которое не заполнили
                    $(this).parent().focus();
                }
            }
            // console.log("Значение select"+val+' '+id);
        });
    });


    //Удаление автора или руководителя из реестра
    $('a[class|="delete"]').click(function (e) {
        e.preventDefault();
        //var id_w = $(this).parents('tr').children('td:first').text();
        const $this = $(this).parent('li');
        const id = $this.data('index');
        const table = $this.parent('ol').data('objectName');
        let action = ('delete-' + table).toString();

        const answer = confirm("Видалити\n запис?");
        if (answer) {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {action, id},
                cache: false,
                success: function (resp) {
                    try {
                        let response = JSON.parse(resp);

                        if (parseInt(response.code) > 0) {
                            $.notify(response.msg, 'error');
                        } else {
                            $.notify(response.msg, "success");
                            $this.remove();
                        }
                    } catch (e) {
                        console.log(e)
                    }
                }

            });
        }

    });


    //Поиск опреатора на странице
    $(function () {
        const operator = $('#operator span').text();
        if (operator.match('krupnik') || operator.match('roman')) {
            disableObject.show();
        }
    });


    /**
     * Изменение ВНЗ при печати приглашений для
     * */
    const SelectUniverInvitation = $('#seluniverinv');

    SelectUniverInvitation.on('change', function () {

        const val = $(this).find("option:selected").val() || '';
        // console.log(val);
        if (val.toString() !== '-1') {
            $('#letter1link').attr("href", "./invitation.php?id_u=" + val + "&letter=1");
            $('#letter2link').attr("href", "./invitation.php?id_u=" + val + "&letter=2");
        }
    });

    $(function () {
        const val = SelectUniverInvitation.find("option:selected").val() || '';
        //console.log(val);
        if (val.toString() !== '-1') {
            $('#letter1link').attr("href", "./invitation.php?id_u=" + val + "&letter=1");
            $('#letter2link').attr("href", "./invitation.php?id_u=" + val + "&letter=2");
        }
    });

    /*Запрос на формирование списка авторов и рукводителей для отметки о приезде*/
    $("#univer_reseption").on('change', function () {
        $('#columnLeaders').slideUp(100);
        $('#columnAutors').slideUp(100);
        var val = $(this).find("option:selected").val();

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {"action": "list_al", "id_u": val},
            cache: false,
            success: function (txt) {
                txt = txt.split("!");
                $('#columnAutors').slideDown(500);
                $("#selectAutors").empty().append(txt[0]).slideDown(500);
                $('#columnLeaders').slideDown(500);
                $("#selectLeaders").empty().append(txt[1]).slideDown(500);

            }
        });

    });


//обработка собития обновления отметки о прибытии работы при двойном клике на названии меню
    $('#update_arrival_works').on('dblclick', function () {
        //console.log('Дважды нажали');
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {"action": "update_arrival_works"},
            cache: false,
            success: function (txt) {
                //console.log('Запрос обработан! Оновлено:'+txt+' запис(ів)');
                alert('Оновлення записів виконано! Оновлено:' + txt + ' запис(ів)');
            }
        });
    });

//обработка запроса на назначение призового места
    var myPlace = $('#tableSetPlace :input[name="place"]');

    /*var summaryResult = $('#summaryResult');*/

    myPlace.on('change', function () {
        var place = $(this).find("option:selected").val();
        var id_a = $(this).parents('tr').children('td:first').text();
        var place2 = myPlace.find("option:selected");
        var p1 = 0, p2 = 0, p3 = 0, pd = 0;
        for (i = 0; i < myPlace.length; i++) {
            if (place2[i].value == 'I') p1++;
            else if (place2[i].value == 'II') p2++;
            else if (place2[i].value == 'III')
                p3++
            else
                pd++;

        }
        //console.log(p1+' '+p2+' '+p3+' '+pd);
        $('#summaryResult').text('I(' + p1 + ') II(' + p2 + ') III(' + p3 + ') D(' + pd + ')');
        $.ajax({
            type: "POST",
            url: "ajax.php", data: {"action": "setplace", "place": place, "id_a": id_a},
            success: function (txt) {
                //console.log('Злюкен собакен яйцен клац-клац!'+txt);
            }
        });
    });

// Статистика по местам в конце таблицы на странице определения мест
    /* TODO */


    //Обработка отметки о прибытии автора
    $('#selectAutors').on('dblclick', 'li', function (e) {
        e.preventDefault();
        this.blur();
        var val = $(this).attr("alt");
        var action, answer;
        //Какая клавиша нажата
        if (e.ctrlKey) {//Нажата Shift значит удалить из приглашенных
            action = "rem_arrival";
            answer = confirm('Виправити помилку?\n\tВи впевнені?');
            if (!answer) return false;
            $(this).removeClass();
        } else {//Без Shift добавить приглашение
            action = "add_arrival";
            answer = confirm('Відмитити приїзд на конференцію?\n\tВи впевнені?');
            if (!answer) return false;
            $(this).addClass("option-arrival");
        }
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {"action": action, "id_a": val},
            cache: false,
            success: function (txt) {
            }
        });
    });


    //Обработка отметки о прибытии руководителя или сопровождающего
    $('#selectLeaders').on('dblclick', 'li', function (eventObject) {
        eventObject.preventDefault();
        this.blur();
        var val = $(this).attr("alt");
        var action, answer;
        //Какая клавиша нажата
        if (eventObject.ctrlKey) {//Нажата Shift значит удалить из проглашенных
            action = "rem_arrival";
            answer = confirm('Виправити помилку?\n\tВи впевнені?');
            if (!answer) return false;
            $(this).removeClass();
        } else {//Без Shift добавить приглашение
            answer = confirm('Відмитити приїзд на конференцію?\n\tВи впевнені?');
            if (!answer) return false;
            action = "add_arrival";
            $(this).addClass("option-arrival");
        }
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {"action": action, "id_l": val},
            cache: false,
            success: function (txt) {
                //console.log('Запрос обработан\n'+txt);
            }
        });
    });


    /*
    * Обработка странички приглашения для авторов
    * */
    $("#shortlistunivers").on('change', function () {
        var val = $(this).find("option:selected").val();
        console.log('Запрос обработан\n' + val);
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {"action": "getlistinvitationleaders", "id_u": val},
            cache: false,
            success: function (txt) {
                console.log('Запрос обработан\n' + txt);
                $("#listleaders").hide().html(txt).slideDown(400);
                var myChangechk = $("#listleaders li input:checkbox");

                myChangechk.click(function () {
                    var id_l = $(this).prev('input').attr("value");
                    var invit = ($(this).is(':checked')) ? "1" : "0";
                    console.log("Знайдено : " + invit + "\n id_l : " + id_l);

                    $.ajax({
                        type: "POST",
                        url: "ajax.php",
                        data: {"action": "invitationLeader", "id_l": id_l, "invitation": invit},
                        cache: false,
                        success: function (txt) {
                            console.log('Изменено приглашение !\n' + txt);
                        }
                    })//ajax

                    //console.log(id_l);
                });


            }
        });//ajax


    });

    /*
    * Обработка отметки в списке приглашений для руководителей /представителей внз
    * */


    /* Изменение выбора университета */
    $("#selunivers").on('change', function () {
        var id_u = $(this).find("option:selected").val();
        var data = {
            "id_u": id_u,
            "action": "select-works"
        };
        $("#table_la").hide();
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: data,
            cache: false,
            success: function (txt) {
                $("#work").hide().html(txt).slideDown(400);

            }
        });

    });//$("#selunivers").change


    $("#table_la").hide();
    $("#work").on('change', '#selwork', function () {
        var id_w = $(this).find("option:selected").val();
        var id_u = $("#selunivers").find("option:selected").val();
        //console.log('Work:'+id_w+' Univer:'+id_u);

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {"id_w": id_w, "id_u": id_u, "action": "selwork"},
            cache: false,
            success: function (txt) {
                //console.log('Получено \n'+txt);
                txt = txt.split("!");
                $("#table_la").fadeIn(400);
                $("#leader").html(txt[0]).fadeIn(400);
                $("#leaders").html(txt[1]).fadeIn(400);
                $("#autor").html(txt[2]).fadeIn(400);
                $("#autors").html(txt[3]).fadeIn(400);
            },
            error: function () {
                console.log('Что-то не то');
            }
        });
    });


    //Запрос на удаление работы из реестра
    var removeWork = $('a[href*="delete_work"]');
    removeWork.on('click', function () {
        if ($('#operator span').text().toString() == 'krupnik') {
            answerWork = confirm('Ви впевнені що хочете видалити роботу?');
            if (answerWork == true) {
                return true;
            } else {
                return false;
            }
        } else {
            alert('Дія заборонена!');
            return false;
        }
    });


    //удаление файла работы
    const delete_file = $('a[href*="delete_file"]');
    delete_file.on('click', function () {
        if ($('#operator span').text().toString() === 'krupnik') {
            return confirm('Ви впевнені що хочете видалити файл роботи?');
        } else {
            alert('Дія заборонена!');
            return false;
        }
    });


    //Запрос на редактирование данных университета
    var editUniver = $('a[href*="univer_edit"]');
    editUniver.on('click', function () {
        const message = 'Перейти до редагування данних університету?';
        return confirm(message);

    });


    /**
     * Запрос на отсоедиение автора или руководителя от работы
     */
    $body.on('click', 'a[href*="work_unlink"]', function () {
        const operator = $('#operator span').text();
        const message = operator + '\n Відокремити автора/керівника від роботи?';
        return confirm(message);
    });

//Изменение в Области textarea
    var textAC1 = $('#letter2autors');
    textAC1.keypress(function () {
        $('#previewletter2autors').html(textAC1.val());
    });
    textAC1.change(function () {
        $('#previewletter2autors').html(textAC1.val());
        //console.log("Изменеие зафиксированы");
    });
    //Изменение в Области textarea
    var textAC2 = $('#letter2leaders');
    textAC2.keypress(function () {
        $('#previewletter2leaders').html(textAC2.val());
    });
    textAC2.change(function () {
        $('#previewletter2leaders').html(textAC2.val());
        //console.log("Изменеие зафиксированы");
    });

    //Обработка переключателей на форме редактирования сведений о работе
    var chkBoxInvitation = $('.editWork :checkbox[name=invitation]');
    var chkBoxDead = $('.editWork :checkbox[name=dead]');
    //Обработка при загрузке формы
    $(function () {
        if (chkBoxDead.is(':checked')) {
            chkBoxInvitation.attr("disabled", true);
        } else chkBoxInvitation.removeAttr("disabled");
        if (chkBoxInvitation.is(':checked')) {
            chkBoxDead.attr("disabled", true);
        } else chkBoxDead.removeAttr("disabled");
    });
    //Обработка по клику
    chkBoxDead.on('click', function () {
        if ($(this).is(':checked')) {
            chkBoxInvitation.attr("disabled", true);
        } else chkBoxInvitation.removeAttr("disabled");

    });
    chkBoxInvitation.on('click', function () {
        if ($(this).is(':checked')) {
            chkBoxDead.attr("disabled", true);
        } else chkBoxDead.removeAttr("disabled");
        //console.log('Нашлась!');
    });
//Окончание Обработка переключателей на форме редактирования сведений о работе

//$('td').on('dblclick','a[name*="id_w"]',function(){
//alert('1');location.href="http://siteaddress"
//});

    //Формируем меню слевой части стороны при просмотре списка работ
    const list_short = $('div[id^="id_u"]');
    $(function () {
        const $barUnivers = $('#barUnivers');
        list_short.each(function () {
            let object = $(this).text();
            let id_u = $(this).attr("id");
            $barUnivers.append("<li><a href=#" + id_u + ">" + object + "</a></li>");
        })

    });


// Телефонный номер
    var phone_list = $('span[id^="phone"]');
    $(function () {
            phone_list.each(function () {
                    var phone = $(this).text();
                    var operator = (phone != 'відсутній') ? phone[0] + phone[1] + phone[2] : "000";

                    switch (operator) {/*МТС*/
                        case '050':
                        case '066':
                        case '095':
                        case '099': {
                            $(this).addClass("mobo-mts-16");
                        }
                            break;
                        case '067':/*Киевстар*/
                        case '068':
                        case '097':
                        case '098': {
                            $(this).addClass("mobo-kyivstar-16");
                        }
                            break;
                        case '091': {
                            $(this).addClass("mobo-utel-16");
                        }
                            break;
                        case '092': {
                            $(this).addClass("mobo-peoplenet-16");
                        }
                            break;
                        case '093':
                        case '063': {/*Лайф*/
                            $(this).addClass("mobo-life-16");
                        }
                            break;
                        case '094': {
                            $(this).addClass("mobo-intertelecom-16");
                        }
                            break;
                        case '096': {
                            $(this).addClass("mobo-kyivstar-16");
                        }
                            break;
                        case '031':
                        case '059':
                        case '061':
                        case '062':
                        case '032':
                        case '033':
                        case '034':
                        case '035':
                        case '036':
                        case '037':
                        case '038':
                        case '039':
                        case '041':
                        case '042':
                        case '043':
                        case '044':
                        case '045':
                        case '046':
                        case '047':
                        case '048':
                        case '049':
                        case '051':
                        case '052':
                        case '053':
                        case '054':
                        case '055':
                        case '056':
                        case '057':
                        case '058':
                        case '064':
                        case '065':
                        case '069': {
                            $(this).addClass("mobo-home-16");
                        }
                            break;
                        default: {
                            $(this).addClass("mobo-default-16");
                        }
                            break;
                    }

                }
            );
        }
    );


}); // окончание загрузки документа
/*-------------- Окончание файла ----------------------------*/
