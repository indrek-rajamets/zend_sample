$(document).ready(function(){

    $(".datePicker").each(function(){
        $(this).datepicker({
            dateFormat: 'dd.mm.yy',
            changeMonth: true,
            changeYear: true
        });
    });

});