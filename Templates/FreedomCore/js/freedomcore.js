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