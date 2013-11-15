$(document).ready(function(){
	$(".accord, .accord2, .accord3").find("h4").click(function(){
		if( $(this).is(".active") ){
			$(".accord h4, .accord2 h4, .accord3 h4")
				.removeClass("active")
				.next("div").hide(0);
		} else {
			$(".accord h4, .accord2 h4, .accord3 h4")
				.removeClass("active")
				.next("div").hide(0);
			$(this)
				.addClass("active")
				.next("div").show(0);
		}
	});
        
        //Login form tsc
        $("#login_form").submit(function(){
            if( /*validaRut($("#rut").val()) &&*/ validaNumber($("#nrotarj").val()) ){
                var data = $(this).serialize();
                $.ajax({
                    url: APP_JQ,
                    type:'POST',
                    data:'action=login_tsc&'+data,
                    dataType: 'json',
                    beforeSend: function () {
                        //$("#msgbox").empty().text("Verificando...").fadeIn().delay(1500).fadeOut();
                    },
                    success:function(results){
                        if( results.data.result == 1 ){
                            $("#msgbox").empty().text(results.data.msg).fadeIn("fast", function(){
                                location.href = $("#login_form").attr("action");
                            });
                        }else{
                            $("#msgbox").empty().text(results.data.msg).fadeIn().delay(1500).fadeOut();
                        }
                    }
                });
            }else{
                $("#msgbox").empty().text('Favor revise RUT y/o Número Tarjeta').fadeIn().delay(1500).fadeOut();
            }
            return false;
        });
        //Logout tsc
        $(".close_sesion").click(function(e){
            e.preventDefault();
            $.ajax({
                url: APP_JQ,
                type:'POST',
                data:'action=logout_tsc',
                beforeSend: function () {
                },
                success:function(results){
                    if( results == "OK" ){
                        location.href = $(".close_sesion").attr("href");
                    }
                }
            });
        });
});