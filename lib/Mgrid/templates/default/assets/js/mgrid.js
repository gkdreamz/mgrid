jQuery(document).ready(function(){
  
    // make filters' input enterable
    jQuery('form#mgrid-form input').keypress(function(e) {
        if(e.which == 13) {
            jQuery('form#mgrid-form').submit();
        }
    });
  
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
    
    // pagination
    jQuery('#mgrid-pager-select').change(function() {
        var target_url = jQuery( this ).val();
        window.location = target_url;
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