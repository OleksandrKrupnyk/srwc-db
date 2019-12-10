/************Отдельные функции ************/
/*Небходима для выбора работы при связывании*/
function selectWork(id_u,id_w){
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {"select_id_w":id_w,"id_u":id_u,"action":"selunivers"},
        cache: false,
        success: function(txt){$("#work").html(txt);}
    });

    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {"id_w":id_w,"id_u":id_u,"action":"selwork"},
        cache: false,
        success: function(txt){
            //console.log('Получено \n'+txt);
            txt = txt.split("!");

            $("#table_la").fadeIn(400);
            $("#leader").html(txt[0]).fadeIn(400);
            $("#leaders").html(txt[1]).fadeIn(400);
            $("#autor").html(txt[2]).fadeIn(400);
            $("#autors").html(txt[3]).fadeIn(400);

        }
    });
}//function selectWork(id_u,id_w)*/