var recaptcha1;
var recaptcha2;
var myCallBack = function() {
    if (document.getElementById("recaptcha1")) {
        //It  exist
        //Render the recaptcha1 on the element with ID "recaptcha1"
        recaptcha1 = grecaptcha.render('recaptcha1', {
            'sitekey': '6LdFUQ4UAAAAADrHrcxCEKu2m2eVm8FKt5-FjYiZ', //Replace this with your Site key
            'theme': 'dark'
        });
    }
    if (document.getElementById("recaptcha2")) {
        //Render the recaptcha2 on the element with ID "recaptcha2"
        recaptcha2 = grecaptcha.render('recaptcha2', {
            'sitekey': '6LdFUQ4UAAAAADrHrcxCEKu2m2eVm8FKt5-FjYiZ', //Replace this with your Site key
            'theme': 'dark'
        });
    }
};

// var div = document.getElementById('allreviewmain','mainsecondsection');
// var display = 0;
// function hideShow()
// {
//     if(display == 1)
//     {
//         div.style.display = 'block';
//         display = 0;
//     }
//     else
//     {
//         div.style.display = 'none';
//         display = 1;
//     }
// }
$("#allreviewmain").hide();
$("#mainimage").hide();
$(".allglobalsectionbtn1").click(function() {
    $("#mainsecondsection").show();
    $("#allreviewmain").hide();
    $("#mainimage").hide();
})
$(".allglobalsectionbtn2").click(function(){
    $("#allreviewmain").show();
    $("#mainsecondsection").hide();
    $("#mainimage").hide();
})
$(".allglobalsectionbtn3").click(function() {
    $("#mainimage").show();
    $("#mainsecondsection").hide();
    $("#allreviewmain").hide();
})