$(document).ready(function () {
    let isLoading = false;

    const paramsChkBox = $('.params :checkbox'),
        paramsInput = $('.params input[type="text"]');
    paramsChkBox.on('click',
        function () {
            if (!isLoading) {
                isLoading = true;
                let paramName = $(this).parents('tr').data('key'),
                    type = $(this).parents('tr').data('type'),
                    value = $(this).is(':checked') ? 1 : 0;
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {
                        "param": paramName,
                        "action": "change-param",
                        "value": value,
                        "_SNRCRF": _SNRCRF,
                        "type": type
                    },
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

    $('.btn').on('click', function (e) {
        e.preventDefault();
        if (!isLoading) {
            const btn = $(this);
            isLoading = true;
            let input = btn.siblings('input[type="text"]');
            let value = input.val() || '',
                paramName = btn.parents('tr').data('key'),
                type = btn.parents('tr').data('type');

            input.attr('disabled', true);
            btn.attr('disabled', true);
            console.log(value);
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    "param": paramName,
                    "action": "change-param",
                    "value": value,
                    "_SNRCRF": _SNRCRF,
                    "type": type
                },
                cache: false,
                success: function (response) {
                    isLoading = false;
                    btn.removeAttr('disabled');
                    input.removeAttr('disabled');
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

    })
});