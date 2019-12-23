const form = $('#review-form');
const outputSum = $('output.summ');
form.submit(function (e) {
    // e.preventDefault();
    // console.log($(this).serialize());
});

form.on('input', function (e) {
    let sum = 0;
    form.find('input[type|="range"]').each(
        function () {
            $(this).parent().next('td').find('output').val($(this).val());
            sum += parseInt($(this).val())
        }
    );
    outputSum.val(sum);
});

const selWork = $('#review-id_w');
if (selWork !== undefined) {
    selWork.on('change', function () {
        var id_w = $(this).find("option:selected").val();
        var id_u = $(this).find("option:selected").data('univerId');
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {"id_w": id_w, "action": "work-description"},
            cache: false,
            success: function (response) {
                $('#descriptionWorks').children('p').html(response);
                //Заміна списку резензентів при зміні назви роботи
                console.log(id_u,id_w);
                $.ajax({
                    type: "POST",
                    url: "ajax.php",
                    data: {"action": "list-reviewer", "id_u": id_u, "id_w": parseInt(id_w)},
                    cache: false,
                    success: function (response) {
                        $('#review-review1').html(response);
                    }

                });
                //Зміна кнопки повернутися
                $('input:button[name="return"]').attr('onclick', "window.location='action.php?action=all_view#id_w" + id_w + '\'');
            }
        });
    });
}