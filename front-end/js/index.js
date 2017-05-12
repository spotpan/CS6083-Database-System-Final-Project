/**
 * Created by zunchenhuang on 5/5/2017.
 */

$("#loginform").click(function(){
    $("#login").css('display','block');
    $("#register").css('display','none');
});

$("#registerform").click(function (){
    $("#login").css('display','none');
    $("#register").css('display','block');
});
