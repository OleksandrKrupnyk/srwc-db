$(document).ready(function () {
    let isLoading = false;
    const univerList = $("#shortlistunivers"),
        countLeaders = $('#countLeaders');

    univerList.on('change', function () {
        if (!isLoading) {
            isLoading = true;
            let id_u = $(this).find("option:selected").val();
            console.log(parseInt(countLeaders.text()));
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {"action": "list-invitation-leaders", "id_u": id_u},
                cache: false,
                success: function (response) {
                    isLoading = false;
                    $("#listleaders").hide().html(response).slideDown(400);
                    let listCheckBox = $("#listleaders li input:checkbox");

                    listCheckBox.on('click', function () {
                        if (!isLoading) {
                            isLoading = true;
                            let id_l = parseInt($(this).parents('li').data('key')),
                                invitation = $(this).is(':checked') ? "1" : "0",
                                sign = (invitation === '1') ? 1 : -1;
                            $.ajax({
                                type: "POST",
                                url: "ajax.php",
                                data: {"action": "invitationLeader", "id_l": id_l, "invitation": invitation},
                                cache: false,
                                success: function (response2) {
                                    isLoading = false;
                                    try {
                                        const data = JSON.parse(response2);
                                        $.notify(data.message || 'No message', data.type || 'error');
                                        countLeaders.text(parseInt(countLeaders.text()) + sign);
                                    } catch (e) {
                                        console.log(e);
                                    }
                                },
                                error: function (e) {
                                    console.log(e);
                                }
                            })//ajax
                        }
                        //console.log(id_l);
                    });
                },
                error: function (e) {
                    console.log(e);
                }
            });//ajax


        }

    });


});