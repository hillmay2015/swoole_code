/**
 * Created by may on 2019/6/19.
 */

$(function () {
    $('#discuss-box').keydown(function (event) {
        if (event.keyCode == 13) {
            var text = $(this).val();
            var url = "http://127.0.0.1:8816/?s=index/chart/index";
            var data = {'content': text, 'game_id': 1};

            $.post(url, data, function () {
                    $(this).val('');
                },
                'json');
        }
    })
})