$(document).ready(function () {
    let isLoading = false;

    const myPlace = $('#tableSetPlace :input[name="place"]');

    function updatePlaceCounters() {
        let place2 = myPlace.find("option:selected"),
            p1 = 0, p2 = 0, p3 = 0, pd = 0;
        place2.each(function (index, item) {
            let value = $(item).val();
            if (value === 'I') p1++;
            else if (value === 'II') p2++;
            else if (value === 'III') p3++;
            else pd++;
        });
        $('#summaryResult').text('I(' + p1 + ') II(' + p2 + ') III(' + p3 + ') D(' + pd + ')');
    }

    updatePlaceCounters();
    myPlace.on('change', function () {
        if (!isLoading) {
            isLoading = true;
            let place = $(this).find("option:selected").val(),
                id_a = parseInt($(this).parents('tr').data('key'));
            $.ajax({
                type: "POST",
                url: "ajax.php", data: {"action": "set-place", "place": place, "id_a": id_a},
                success: function (response) {
                    isLoading = false;
                    try {
                        const data = JSON.parse(response);
                        $.notify(data.message || 'No message', data.type || 'error');
                        updatePlaceCounters();
                    } catch (e) {
                        console.log(e);
                    }
                },
                'error': function (e) {
                    console.log(e)
                }
            });
        }
    });
});