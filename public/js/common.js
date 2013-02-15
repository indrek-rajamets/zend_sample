$(document).ready(function(){

    $(".datePicker").each(function(){
        console.log($(this)[0].attributes);
        $(this).datepicker({
            dateFormat: 'dd.mm.yy',
            changeMonth: true,
            changeYear: true
        });
    });

});