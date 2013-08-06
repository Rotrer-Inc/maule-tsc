$(document).ready(function(){
    $(".submitForm").click(function(e){
        e.preventDefault();
        if(validaFormulario()){
            $("#data").submit();
        }
    });
});