//выполнять если документ полностью загрузился
$(document).ready(function() {

    var SelectUniverInvitation = $('#seluniverinv');

    SelectUniverInvitation.on('change',function(){

        var val = $(this).find("option:selected").val();
        console.log(val);
        if(val != '-1' ){
            $('#letter1link').attr("href","./invitation.php?id_u=" + val + "&letter=1");
            $('#letter2link').attr("href","./invitation.php?id_u=" + val + "&letter=2");
        }
    })

    $(function(){
        var val = SelectUniverInvitation.find("option:selected").val();
        console.log(val);
        if(val != '-1'){
            $('#letter1link').attr("href","./invitation.php?id_u=" + val + "&letter=1");
            $('#letter2link').attr("href","./invitation.php?id_u=" + val + "&letter=2");
        }
    })
}); // окончание загрузки документа