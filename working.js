$(document).ready(function ()
    {
        $("td[custom=editablefields] div div div.col-sm-2 a span").click(function ()
        {
            $(this).parent().parent().siblings().children("p").addClass("hide");
            $(this).parent().parent().siblings().children("input").removeClass("hide");
        });

        $("td[custom=editablefields] div div div.col-sm-10 input").blur(function ()
    {
        //Reset things
        //Save the given value
        //When saved change the given value in the page
    })
        $("td[custom=editablefields]").mouseover(function ()
        {
            $(this).children("div.form-group").children("div.row").children("div.col-sm-2").children("a").children("span").removeClass("hide")
        });
        
        $("td[custom=editablefields]").mouseout(function ()
        {
            $(this).children("div.form-group").children("div.row").children("div.col-sm-2").children("a").children("span").addClass("hide");
        });
        
    });