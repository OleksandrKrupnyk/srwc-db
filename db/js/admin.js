//выполнять если документ полностью загрузился
$(document).ready(function () {

    const $body = $('body'),
        disableObject = $('li').find('.special').parent();
    disableObject.hide();

    $('#columnAutors').css("display", "none");
    $('#columnLeaders').css("display", "none");


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
        const $this = $(this).parent('li'),
            id = $this.data('index'),
            table = $this.parent('ol').data('objectName');
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
        if (operator.match('krupnik') || operator.match('roman') || operator.match('shramko')) {
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
        var id_w = $(this).find("option:selected").val(),
            id_u = $("#selunivers").find("option:selected").val();
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
            error: function (e) {
                console.log(e, 'Что-то не то');
            }
        });
    });


    const operator = $('#operator span').text().toString();
    //Запрос на удаление работы из реестра
    const removeWork = $('a[href*="delete_work"]');
    removeWork.on('click', function () {
        if (operator.match('krupnik')) {
            return confirm('Ви впевнені що хочете видалити роботу?');
        } else {
            alert('Дія заборонена!');
            return false;
        }
    });
    //удаление файла работы
    const delete_file = $('a[href*="delete_file"]');
    delete_file.on('click', function () {
        if (operator.match('krupnik')) {
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


    //Обработка переключателей на форме редактирования сведений о работе
    var chkBoxInvitation = $('.editWork :checkbox[name=invitation]'),
        chkBoxDead = $('.editWork :checkbox[name=dead]');
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
    //Get the button:
    mybutton = document.getElementById("btnUp");
    if (mybutton !== null) {
        window.onscroll = function () {
            scrollFunction()
        };
    }

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

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
