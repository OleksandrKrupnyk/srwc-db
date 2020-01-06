$(document).ready(function () {
    let isLoading = false;

    const myChangeRoom = $('#tableSelectRoom :input[name="room"]');
    myChangeRoom.on('change',
        function () {
            if (!isLoading) {
                isLoading = true;
                let room = $(this).find("option:selected").val(),
                    id_sec = parseInt($(this).parents('tr').data('key'));
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"room": room, "action": "change-room", "id_sec": id_sec},
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