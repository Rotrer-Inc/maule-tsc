$(document).ready(function(){
    $(".submitForm").click(function(e){
        e.preventDefault();
        if(validaFormulario()){
            $("#data").submit();
        }
    });
    $("#send_single").click(function(e){
        e.preventDefault();
        var singleValue = $("#single_value").val();
        if( validaNumber(singleValue) && parseInt(singleValue) >= 6000 && parseInt(singleValue) <= 999999 ){
            $("#datosSingle").submit();
        }else{
            return activaErrorClass(false, "Debe ingresar monto, solo números.", $("#single_value"), "errorSingle");
        }
    });
    $("#single_value").change(function(){
        $(".singlePriceTotal").empty().text( "$"+$(this).val() );
    });
    $("#single_value").focus(function(){
        $(this).removeClass("errored");
    });
    
    $("#send_multi").click(function(e){
        e.preventDefault();
        $('.multi_value').each(function(index) {
            var multiValueTmp = $(this).val();
            if( validaNumber(multiValueTmp) && parseInt(multiValueTmp) >= 6000 && parseInt(multiValueTmp) <= 999999 ){
                $("#datosMulti").submit();
            }else{
                return activaErrorClass(false, "Debe ingresar monto, solo números.", $(this), "errorMulti");
            }
        });
    });
    $('.multi_value').focus(function(){
        $(this).removeClass("errored");
    });
});