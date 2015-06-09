function Load3DModel(Show)
{
    if(Show)
    {
        $('#3DModel').load('/render/dynamic/character/'+$('#charactername').text());
        $('#show3dmodel').hide();
        $('#hide3dmodel').show();
    }
    else
    {
        $('#3DModel').empty();
        $('#show3dmodel').show();
        $('#hide3dmodel').hide();
    }
}

var Localization = {

    update: function(Language, File)
    {
        var LocalizationArray = [];
        $("textarea").each( function()
            {
                LocalizationArray.push({
                    variable: $(this).attr('name'),
                    value: $(this).val()
                });
            }
        );
        var JsonString = JSON.stringify(LocalizationArray);
        $.ajax({
            type: "POST",
            url: "/admin/localization/"+Language+'/'+File,
            data: {data : JsonString},
            cache: false,
            success: function(data){
                console.log(data)
            }
        });
    }
}