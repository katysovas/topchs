$(document).ready(function(){ 
 $("form > div > select[name=acctype]").change(function(){
    var value = $("form > div > select[name=acctype]").val();
    window.location.href = value;
 });
});
 