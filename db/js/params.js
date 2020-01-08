$(document).ready(function () {
    let isLoading = false;

    const params = $('.params :checkbox');
    params.on('click',
        function () {
            if (!isLoading) {
                isLoading = true;
                let paramName = $(this).parents('tr').data('key'),
                    value = $(this).is(':checked') ? 1 : 0;
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"param": paramName, "action": "change-param", "value": value, "_SNRCRF": _SNRCRF},
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