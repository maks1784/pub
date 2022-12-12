$(function() {

    var start = moment().startOf('month');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('DD.MM.YYYY') + ' — ' + end.format('DD.MM.YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        "autoApply": true,
        "applyButtonClasses": "btn-success",
        "locale": {
        "format": "MM/DD/YYYY",
        "separator": " - ",
        "applyLabel": "ОК",
        "cancelLabel": "Отмена",
        "fromLabel": "From",
        "toLabel": "To",
        "customRangeLabel": "Выбрать период",
        "weekLabel": "W",
        "daysOfWeek": [
            "Вс",
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб"
        ],

        "firstDay": 1
    },
        ranges: {
           'Сегодня': [moment(), moment()],
           'Вчера': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Последние 7 дней': [moment().subtract(6, 'days'), moment()],
           'Последние 30 дней': [moment().subtract(29, 'days'), moment()],
           'Этот месяц': [moment().startOf('month'), moment().endOf('month')],
           'Предыдущий месяц': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

});