$(document).ready(function(){
    var date = new Date();
    date.setDate(date.getDate()-1);
    $('input.date').datepicker({
         minDate: date,
         dateFormat: 'dd/mm/yy'
    });
    
    $('.annonces .link').click(function(e){
        e.preventDefault();
        window.location = $(this).attr('href'); 
    });
});
