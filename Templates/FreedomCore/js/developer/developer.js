function PerformJsonRequest(form, endpoint)
{
    if (typeof endpoint === 'undefined')
        endpoint = null;
    var Form = '#'+$(form).attr('id');
    var ResponseBox = $(Form).find('.request_result');
    ResponseBox.empty();
    $(Form).find('.clear-results').fadeIn();
    ResponseBox.css({
        display: 'none'
    });
    $('.clear-results').click(function (event) {
        event.preventDefault();
        $(this).fadeOut();
        $(ResponseBox).slideUp(function () {
            ResponseBox.empty();
        });
    });

    // Add request uri
    ResponseBox.append(
        $('<h4 class="call">Request URI</h4>'),
        $('<pre class="call" />'));

    // Add request headers
    ResponseBox.append(
        $('<h4 class="requestHeaders">Request Headers</h4>').hide(),
        $('<pre class="requestHeaders" />').hide());

    // Add response status
    ResponseBox.append(
        $('<h4 class="responseStatus">Response Status</h4>'),
        $('<pre class="responseStatus" />'));

    // Add response headers
    ResponseBox.append(
        $('<h4 class="headers">Response Headers</h4>'),
        $('<pre class="headers" />'));

    // Add response body
    ResponseBox.append(
        $('<h4 class="response">Response Body</h4>'),
        $('<pre class="response prettyprint" />'));

    // Add response box to form and show it
    ResponseBox.appendTo(Form).slideDown();
    if(endpoint == null)
        var IDData = $(Form).find('#id').val();
    else if(endpoint == 'account')
    {
        var Username = $(Form).find('#username').val();
        var Password = $(Form).find('#password').val();
    }
    var LocaleData = $(Form).find('#locale').val();
    var JSONPData = $(Form).find('#jsonp').val();
    var MethodURI  = $(Form).find('#methodUri').val();
    if(endpoint == null)
        var APIUri = MethodURI.replace(':id', IDData)+'?locale='+LocaleData+'&jsonp='+JSONPData+'&key='+$('#apikey').val();
    else if(endpoint == 'armory')
        var APIUri = MethodURI+'?locale='+LocaleData+'&jsonp='+JSONPData+'&key='+$('#apikey').val();
    else if(endpoint == 'account')
        var APIUri = MethodURI+'?username='+Username+'&password='+Password+'&locale='+LocaleData+'&jsonp='+JSONPData+'&key='+$('#apikey').val();
    var DataType = '';
    if(JSONPData == '')
        DataType = 'json';
    else
        DataType = 'jsonp';
    $.ajax({
        type: 'GET',
        url: APIUri,
        data: {},
        cache: false,
        dataType: DataType,
        beforeSend: function(){
            ResponseBox.children('pre').text('Loading...').removeClass('error');
        },
        success: function(data, statusText, request){
            var Headers = request.getAllResponseHeaders();
            var HeadersArray = parseResponseHeaders(Headers);
            ResponseBox.find('pre.call').text(APIUri);

            ResponseBox.find('pre.responseStatus').text(request.status + ' ' + statusText).toggleClass('error', statusText !== 'success');
            ResponseBox.find('.responseStatus').toggle((request.status > 0 || statusText) ? true : false);
            ResponseBox.find('pre.headers').text(Headers).toggleClass('error', statusText !== 'success');
            var formattedText = JSON.stringify(data, null, 2);
            ResponseBox.find('pre.response').text(formattedText).toggleClass('error', statusText !== 'success');
        },
        error: function(request, statusText, errorThrown){
            var Headers = request.getAllResponseHeaders();
            var HeadersArray = parseResponseHeaders(Headers);
            ResponseBox.find('pre.call').text(APIUri);
            ResponseBox.find('pre.responseStatus').text(request.status + ' ' + errorThrown).toggleClass('error', errorThrown !== 'success');
            ResponseBox.find('.responseStatus').toggle((request.status > 0 || errorThrown) ? true : false);
            ResponseBox.find('pre.headers').text(Headers).toggleClass('error', statusText !== 'success');
            ResponseBox.find('pre.response').text(JSON.stringify(request.responseText, null, 2)).toggleClass('error', statusText !== 'success');
        }
    });
    return false;
}

function parseResponseHeaders(headerStr) {
    var headers = {};
    if (!headerStr) {
        return headers;
    }
    var headerPairs = headerStr.split('\u000d\u000a');
    for (var i = 0; i < headerPairs.length; i++) {
        var headerPair = headerPairs[i];
        // Can't use split() here because it does the wrong thing
        // if the header value has the string ": " in it.
        var index = headerPair.indexOf('\u003a\u0020');
        if (index > 0) {
            var key = headerPair.substring(0, index);
            var val = headerPair.substring(index + 2);
            headers[key] = val;
        }
    }
    return headers;
}

$.fn.form = function() {
    var formData = {};
    this.find('[name]').each(function() {
        formData[this.name] = this.value;
    })
    return formData;
};