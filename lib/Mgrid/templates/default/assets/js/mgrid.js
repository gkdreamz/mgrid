jQuery(document).ready(function(){
  
    // remove ordering
    jQuery('#mgrid-remove-ordering').click(function() {
        window.location = window.location.pathname + '?mgrid[removeOrder]=1';
    });
    
    // add filtering
    jQuery('#mgrid-add-filter').click(function() {
        jQuery('form#mgrid-form').submit();
    });
    
    // remove filtering
    jQuery('#mgrid-remove-filter').click(function() {
        window.location = window.location.pathname + '?mgrid[removeFilter]=1';
    });
    
  
//    $('.grid .massaction-button').click(function() {
//        var values = new Array();
//        var checked = $('.check-all').parents('.grid').find(':checkbox[class!=check-all][checked=true]');
//
//        $.each(checked, function (index, value){
//            values[index] = $(value).val();
//        });
//
//        $('.massaction-values').val(values.join(','));
//        $('.massaction-form').submit();
//    });
        
});