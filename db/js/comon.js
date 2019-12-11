/*Небходима для выбора работы при связывании*/
function selectWork(id_u, id_w) {
    var data = {
        "id_w": id_w,
        "id_u": id_u,
        "action": "select-works"
    };

    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: data,
        cache: false,
        success: function (txt) {
            $("#work").html(txt);
        }
    }).then(
        function () {
            // console.log('Done');
        }
    );

    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {"id_w": id_w, "id_u": id_u, "action": "selwork"},
        cache: false,
        success: function (response) {
            $("#table_la").fadeIn(400);
            // try {
            //     var txt = response.replace(/\\"/g, "");
            //     var objs = JSON.parse(txt.toString());
            //     $("#autor").html(objs.authors).fadeIn(400);
            //     const $selectAuthors = $("#select-link-author");
            //     $selectAuthors.chosen({max_selected_options: 2, disable_search: true, width: "100%"});
            //     $selectAuthors.trigger("chosen:updated");
            //     $("#leader").html(objs.leaders).fadeIn(400);
            //
            //     $selectAuthors.on('change', function (evt, params) {
            //         let id_w = parseInt($("#work").find(':selected').val());
            //         let ask = true;
            //         $data = {"id_w": id_w};
            //         if (params.selected !== undefined) {
            //             const id_a = parseInt(params.selected);
            //             $data = {...$data, "id_a": id_a, "action": "link-author"};
            //         } else if (params.deselected !== undefined) {
            //             ask = confirm('Ви впевнені');
            //             const id_a = parseInt(params.deselected);
            //             $data = {...$data, "id_a": id_a, "action": "unlink-author"};
            //         }
            //         if (ask) {
            //             console.log($data);
            //
            //             $.ajax({
            //                 type: "POST",
            //                 url: "ajax.php",
            //                 data: $data,
            //                 cache: false,
            //             })
            //                 .then(function (response) {
            //                     console.log('Обработан успешно', response);
            //
            //                 });
            //         } else {
            //             evt.preventDefault();
            //         }
            //     });
            //
            //     const $selectLeaders = $("#select-link-leader");
            //     $selectLeaders.trigger("chosen:updated");
            //     $selectLeaders.chosen({max_selected_options: 1, disable_search: true, width: "100%"});
            //     $selectLeaders.on('change', function (evt, params) {
            //         let id_w = parseInt($("#work").find(':selected').val());
            //         $data = {"id_w": id_w};
            //         if (params.selected !== undefined) {
            //             const id_a = parseInt(params.selected);
            //             $data = {...$data, "id_a": id_a, "action": "link-leader"};
            //         } else if (params.deselected !== undefined) {
            //             const id_a = parseInt(params.deselected);
            //             $data = {...$data, "id_a": id_a, "action": "unlink-leader"};
            //         }
            //         console.log($data);
            //     });
            //
            // } catch (e)
            {
                console.log(response);
                response = response.split("!");
                $("#leader").html(response[0]).fadeIn(400);
                $("#leaders").html(response[1]).fadeIn(400);
                $("#autor").html(response[2]).fadeIn(400);
                $("#autors").html(response[3]).fadeIn(400);
            }

        }
    }).then(
        function () {
            console.log('Done2');
        }
    );
}