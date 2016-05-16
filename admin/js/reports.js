$(document).ready(function(){ 
 $("form > div > select[name=filter]").change(function(){
    var value = $("form > div > select[name=filter]").val();
    if(value =='keyword'){
        $("input#new_value").attr('placeholder','Enter Keyword');
    }    
    else if(value =='date'){
        $("input#new_value").attr("placeholder","pick date");
        $("input#new_value").datepicker({dateFormat: 'yy-mm-dd'});
    }
    else if(value =='delivery_status'){
        $("input#new_value").attr('placeholder','Enter Delivery Status');
    }
    else if(value =='accstatus'){
        $("input#new_value").attr('placeholder','Enter Account Status');
    }
 });
});
 