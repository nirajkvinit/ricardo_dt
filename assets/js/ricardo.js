jQuery.noConflict($);

var ricardo_dt = null;
jQuery(document).ready(function($){
	ricardo_dt = $('#ricardo_dt').DataTable( {		
        "processing": true,
        "serverSide": true,        
        "ajax": {
            "url": RICARDOAJAX.ajaxurl,
            "data": function ( d ) {
                d.action = 'fn_process_ricardo_dt_ajax';
            }
        }
    } );
});
