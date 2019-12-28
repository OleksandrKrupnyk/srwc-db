$(document).ready(function () {
    let isLoading = false;

    const myChange = $('#tableInviteUnivers :checkbox[name=invitation]');
    myChange.on('click',
        function () {
            if (!isLoading) {
                isLoading = true;
                let id_u = parseInt($(this).parents('tr').data('key')),
                    invite = $(this).is(':checked') ? 1 : 0;
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"action": "invitation-univer", "invite": invite, "id_u": id_u,},
                    cache: false,
                    success: function (response) {
                        isLoading = false;
                        try {
                            const data = JSON.parse(response);
                            $.notify(data.message || 'No message', data.type || 'error');
                        } catch (e) {
                            console.log(e);
                        }
                    },
                    error: function (e) {
                        console.log(e)
                    }
                });
            }
        });
});