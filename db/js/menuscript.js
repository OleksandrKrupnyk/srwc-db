function setActiveTable(tabs_id) {
    $('.tabs li').removeClass('active');
    let $li = $('#' + tabs_id);
    $li.addClass('active');
    $('.nav')
        .hide()
        .eq(parseInt($li.index())).show();
    return false;
}

$(document).ready(function () {
    let $tabs_id = localStorage.getItem('tabs_id');
    if ($tabs_id !== null) {
        setActiveTable($tabs_id.toString());
    }

    $('.tabs li a').on('click', function () {
        let $tabs_id = $(this).parent().prop('id');
        setActiveTable($tabs_id);
        localStorage.setItem('tabs_id', $tabs_id);
    });

    $('.nav li').has('ul').hover(function () {
        $(this).addClass('current').children('ul').fadeIn();
    }, function () {
        $(this).removeClass('current').children('ul').hide();
    });
});
