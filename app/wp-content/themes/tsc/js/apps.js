<<<<<<< HEAD
$(document).ready(function(){
    var minimoCarga = 10;

    $(".submitForm").click(function(e){
        e.preventDefault();
        if(validaFormulario()){
            $("#data").submit();
        }
    });
    $("#send_single").click(function(e){
        e.preventDefault();
        var singleValue = $("#single_value").val();
        if( validaNumber(singleValue) && parseInt(singleValue) >= minimoCarga && parseInt(singleValue) <= 999999 ){
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
        var returnMulti = true;
        $('.multi_value').each(function(index) {
            var multiValueTmp = $(this).val();
            if( validaNumber(multiValueTmp) && parseInt(multiValueTmp) >= minimoCarga && parseInt(multiValueTmp) <= 999999 ){
                returnMulti = true;
            }else{
                returnMulti = activaErrorClass(false, "Debe ingresar monto, solo números.", $(this), "errorMulti");
                return returnMulti;
            }
        });
        
        if(returnMulti == true){
            $("#datosMulti").submit();
        }
    });
    $('.multi_value').focus(function(){
        $(this).removeClass("errored");
    });
    $("#multiDarIgual").click(function(e){
        e.preventDefault();
        var multiMontoFijo = $("#multiMontoFijo").val();
        var totalMulti = 0;
        if( validaNumber(multiMontoFijo) && parseInt(multiMontoFijo) >= minimoCarga && parseInt(multiMontoFijo) <= 999999 ){
            $('.multi_value').each(function(index) {
                totalMulti += parseInt(multiMontoFijo);
                $(this).val(multiMontoFijo);
                $(this).removeClass("errored");
            });
            $(".multiPriceTotal").empty().text( "$"+totalMulti );
            $("#multiTotalVaue").val(totalMulti);
        }else{
            return activaErrorClass(false, "Debe ingresar monto, solo números.", $("#multiMontoFijo"), "errorMontoFijo");
        }
    });
    var tmpTotal, tmpVal, tmpValFocus;
    $('.multi_value').change(function(){
        tmpTotal = parseInt($("#multiTotalVaue").val());
        tmpVal = parseInt($(this).val());
        var totalMulti = tmpTotal - tmpValFocus + tmpVal;
        $(".multiPriceTotal").empty().text( "$"+totalMulti );
        $("#multiTotalVaue").val(totalMulti);
    });
    $('.multi_value').focus(function(){
        tmpValFocus = parseInt($(this).val());
    });
    $('#imprimir_webpay').click(function(e){
	    e.preventDefault();
		$('#comprobante').printArea();
    });
=======
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
        var returnMulti = true;
        $('.multi_value').each(function(index) {
            var multiValueTmp = $(this).val();
            if( validaNumber(multiValueTmp) && parseInt(multiValueTmp) >= 6000 && parseInt(multiValueTmp) <= 999999 ){
                returnMulti = true;
            }else{
                returnMulti = activaErrorClass(false, "Debe ingresar monto, solo números.", $(this), "errorMulti");
                return returnMulti;
            }
        });
        
        if(returnMulti == true){
            $("#datosMulti").submit();
        }
    });
    $('.multi_value').focus(function(){
        $(this).removeClass("errored");
    });
    $("#multiDarIgual").click(function(e){
        e.preventDefault();
        var multiMontoFijo = $("#multiMontoFijo").val();
        var totalMulti = 0;
        if( validaNumber(multiMontoFijo) && parseInt(multiMontoFijo) >= 6000 && parseInt(multiMontoFijo) <= 999999 ){
            $('.multi_value').each(function(index) {
                totalMulti += parseInt(multiMontoFijo);
                $(this).val(multiMontoFijo);
                $(this).removeClass("errored");
            });
            $(".multiPriceTotal").empty().text( "$"+totalMulti );
            $("#multiTotalVaue").val(totalMulti);
        }else{
            return activaErrorClass(false, "Debe ingresar monto, solo números.", $("#multiMontoFijo"), "errorMontoFijo");
        }
    });
    var tmpTotal, tmpVal, tmpValFocus;
    $('.multi_value').change(function(){
        tmpTotal = parseInt($("#multiTotalVaue").val());
        tmpVal = parseInt($(this).val());
        var totalMulti = tmpTotal - tmpValFocus + tmpVal;
        $(".multiPriceTotal").empty().text( "$"+totalMulti );
        $("#multiTotalVaue").val(totalMulti);
    });
    $('.multi_value').focus(function(){
        tmpValFocus = parseInt($(this).val());
    });
    $('#imprimir_webpay').click(function(e){
	    e.preventDefault();
		$('#comprobante').printArea();
    });
>>>>>>> 51091f05633421a29193c6361bf1f81a11c155c1
});