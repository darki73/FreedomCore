function PerformJsonRequest(form)
{
    var FormData = form.form();
    var ResponseBox = $('#'+FormData['dataName']+'_response');
    ResponseBox.empty();
    $('.clear-results').fadeIn();
    ResponseBox.css({
        display: 'none'
    });
    $('.clear-results').click(function (event) {
        event.preventDefault();
        $(this).fadeOut();
        $(ResponseBox).slideUp(function () {
            ResponseBox.innerHTML = '';
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
    ResponseBox.appendTo(form).slideDown();
    var APIUri = FormData['methodUri'].replace(':id', FormData['params[:id]'])+'?key='+$('#apikey').val();
    $.ajax({
        type: 'GET',
        url: APIUri,
        data: form.serialize(),
        beforeSend: function(){
            ResponseBox.children('pre').text('Loading...').removeClass('error');
        },
        success: function(data, statusText, request){
            var Headers = request.getAllResponseHeaders();
            var HeadersArray = parseResponseHeaders(Headers);
            ResponseBox.find('pre.call').text(APIUri);

            ResponseBox.find('pre.responseStatus').text(request.status + ' ' + statusText).toggleClass('error', statusText !== 'success');
            ResponseBox.find('.responseStatus').toggle((request.status > 0 || statusText) ? true : false);
            ResponseBox.find('pre.headers').text(Headers).toggleClass('error', data.statusText !== 'success');
            switch (HeadersArray['Content-Type'].split(';')[0]) {
                // Parse types as JSON
                case 'application/javascript':
                case 'application/json':
                case 'application/x-javascript':
                case 'application/x-json':
                case 'text/javascript':
                case 'text/json':
                case 'text/x-javascript':
                case 'text/x-json':
                    try {
                        formattedText = js_beautify(formattedText, { 'preserve_newlines': false });
                    } catch (err) {
                        formattedText = JSON.stringify(data, null, 2);
                    }
                    break;

                // Parse types as XHTML
                case 'application/xml':
                case 'text/xml':
                case 'text/html':
                case 'text/xhtml':
                    formattedText = formatXML(formattedText) || '';
                    break;
                default:
                    break;
            }
            ResponseBox.find('pre.response').text(formattedText).toggleClass('error', data.statusText !== 'success');
        },
        error: function(request, statusText, errorThrown)
        {
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