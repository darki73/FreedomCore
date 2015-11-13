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
var typingTimer;
var doneTypingInterval = 1500;

$('#oldPassword').keyup(function(){
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
});
$('#oldPassword').keydown(function(){
    clearTimeout(typingTimer);
});

function ReloadCaptcha()
{
    var CaptchaDiv = $('#captcha-image');
    CaptchaDiv.empty();
    var Image = $('<img/>');
    Image.attr('align', 'middle');
    Image.attr('id', 'sec-string');
    Image.attr('alt', 'Renew');
    Image.attr('src', '/account/captcha.jpg');

    CaptchaDiv.append(Image);

    return false;
}

function doneTyping () {
    $.ajax({
        url: '/account/management/settings/verify-old-password?username='+$('#Username').val()+'&oldPassword='+$('#oldPassword').val(),
        type: 'POST',
        success: function (data) {
            if(data != 'true')
            {
                $('#content').prepend(makeErrorBox(FormMsg.oldpasswordError));
                window.location.href = "#form-errors";
                $('#password-submit').addClass('disabled').attr('disabled', 'disabled');
            }
            else
            {
                $('#password-submit').removeClass('disabled').removeAttr('disabled');
                $('#oldPasswordErrorBox').remove();
            }
        }
    });
}

function makeErrorBox(errorMsgs) {
    $('#content .alert').remove();
    var errorCount = errorMsgs.length;
    var errorHtml = ''
        + '<div class="alert error closeable border-4 glow-shadow" id="oldPasswordErrorBox">'
        + '<div class="alert-inner">'
        + '<div class="alert-message">'
        + '<p class="title"><strong><a name="form-errors">' + FormMsg[((errorCount>1)?"headerMultiple":"headerSingular")] + '</a></strong></p>';
    errorHtml += '<p>' +errorMsgs+ '</p>';
    errorHtml += ''
    +'</div>'
    + '</div>'
    + '</div>';
    return errorHtml;
}

var Installation = {

    configcreate: function()
    {
        var DatabaseData = $('#database_settings').serialize();
        $.ajax({
            type: 'POST',
            url: '/install?category=createconfig',
            data: DatabaseData,
            cache: false,
            success: function(data){
                if(data == 1)
                {
                    document.getElementById('filestoimport').style.display = 'block';
                    $('#filestoimport')[0].scrollIntoView(true);
                }
                else
                {
                    console.log(data);
                    alert('Unhandled error occured!');
                }
            }
        });
        return false;
    },

    import: function(link, name)
    {
        var SpanName = 'installation_status_'+name;
        document.getElementById(SpanName).innerHTML = "<img height='15px' width='15px' src='/Templates/FreedomCore/images/loaders/uber-loading.gif'>";
        $.ajax({
            type: 'POST',
            url: '/install?category=import&link='+encodeURIComponent(link),
            data: null,
            cache: false,
            success: function(data)
            {
                if(data == 1)
                {
                    document.getElementById(SpanName).innerHTML = "";
                    document.getElementById(SpanName).innerHTML = "<img height='15px' width='15px' src='/Templates/FreedomCore/images/icons/arrow-done-plain.gif'>";
                }
                else
                {
                    document.getElementById(SpanName).innerHTML = "";
                    document.getElementById(SpanName).innerHTML = "<img height='15px' width='15px' src='/Templates/FreedomCore/images/icons/cross.png'>";
                }
            }
        });
        return false;
    }
};

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
};