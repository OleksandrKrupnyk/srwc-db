/*Небходима для выбора работы при связывании*/
function selectWork(id_u, id_w) {
    let data = {
            "id_w": id_w,
            "id_u": id_u,
            "action": "select-works"
        },
        isLoading = false;

    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: data,
        cache: false,
        success: function (response) {
            $("#work").html(response);
        }
    }).then(
        function () {
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {"id_w": id_w, "id_u": id_u, "action": "authors-leaders"},
                cache: false,
                success: function (response) {
                    let list = response.replace(/\\"/g, ""),
                        objs = JSON.parse(list.toString());
                    $("#table_la").fadeIn(400);
                    $("#leader").html(objs.linkedLeaders || 'Сталася помилка').fadeIn(400);
                    $("#leaders").html(objs.listLeaders || 'Сталася помилка').fadeIn(400);
                    $("#autor").html(objs.linkedAuthors || 'Сталася помилка').fadeIn(400);
                    $("#autors").html(objs.listAuthors || 'Сталася помилка').fadeIn(400);
                }
            }).then(
                function () {
                    console.log('Done2');
                }
            );
        }
    );
}