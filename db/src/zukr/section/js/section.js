$(document).ready(function () {
        let isLoading = false;
        $('.editable').editable(function (value) {
            console.log(value);
            let id = parseInt($(this).parent('li').data('key') || 0);
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {"section": value, "action": "change-section", "id_sec": id},
                cache: false,
                success: function (response) {
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
            return (value);
        }, {
            submit: '&#128190;',
            cancel: '&#10060;',
            submitcssclass: 'btn',
            cancelcssclass: "btn",
            tooltip: "Клацніть для редагування",
            size: "75"
        });
        $('.editable-connect-meet').editable(function (value) {
            let id = parseInt($(this).parent('li').data('key') || 0);
            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: {
                    "link": value,
                    "action": "change-section-connect-meet",
                    "id_sec": id
                },
                cache: false,
                success: function (response) {
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
            return (value);
        }, {
            submit: '&#128190;',
            cancel: '&#10060;',
            placeholder: "No Link",
            submitcssclass: 'btn',
            cancelcssclass: "btn",
            tooltip: "Клацніть для редагування",
            size: "1024",
            type: "url"
        });
        $('.js-delete-list-item').on('click', function (e) {
            e.preventDefault();
            let $this = $(this),
                id = parseInt($this.parent('li').data('key') || 0);
            if (!isLoading) {
                isLoading = true;
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"action": "delete-section", "id": id},
                    cache: false,
                    success: function (response) {
                        try {
                            const data = JSON.parse(response);
                            $.notify(data.message || 'No message', data.type || 'error');
                            if (data.type !== 'error') {
                                $this.parent('li').fadeOut(400).remove();
                            }
                            isLoading = false;
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
    }
);