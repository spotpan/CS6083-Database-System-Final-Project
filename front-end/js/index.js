/**
 * Created by admin on 5/5/2017.
 */

$("#loginform").click(function(){
    $("#login").css('display','block');
    $("#register").css('display','none');
});

$("#registerform").click(function (){
    $("#login").css('display','none');
    $("#register").css('display','block');
});



// $(document).mouseup(function (e)
// {
//     var container = $(".login");
//
//     if (!container.is(e.target) // if the target of the click isn't the container...
//         && container.has(e.target).length === 0) // ... nor a descendant of the container
//     {
//         container.toggle();
//         $('#loginform').removeClass('green');
//     }
// });