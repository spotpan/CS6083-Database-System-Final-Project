/**
 * Created by zunchenhuang on 5/5/2017.
 */

$("#projform").click(function(){
    $("#project").css('display','block');
    $("#user").css('display','none');
});

$("#userform").click(function(){
    $("#project").css('display','none');
    $("#user").css('display','block');
});
