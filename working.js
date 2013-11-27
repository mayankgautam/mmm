$(document).ready(function ()
    {
        $("td[custom=editablefields] div div div.col-sm-2 a span").click(function ()
        {
            $(this).parent().parent().siblings().children("p").addClass("hide");
            $(this).parent().parent().siblings().children("input").removeClass("hide");
        });

        $("td[custom=editablefields] div div div.col-sm-10 input").blur(function ()
        {
            var thep= $(this).siblings("p");
            
            //Reset things
            thep.removeClass("hide");
            $(this).addClass("hide");
            
            //Save the given value
            var new_name = $(this).val();
            thep.text(new_name);
            
            //Now save the newly obtained value
            //What type of value is it
            var type_value= $(this).data('type');
            
            $.post("edit",{
                type:type_value,
                new_value: new_name,
                id: $(this).data('id')
            });
        
        });
        
        $("td[custom=editablefields]").mouseover(function ()
        {
            $(this).children("div.form-group").children("div.row").children("div.col-sm-2").children("a").children("span").removeClass("hide")
        });
        
        $("td[custom=editablefields]").mouseout(function ()
        {
            $(this).children("div.form-group").children("div.row").children("div.col-sm-2").children("a").children("span").addClass("hide");
        });
        
    });