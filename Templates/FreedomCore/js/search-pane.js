var SearchPane = {

    load: function(target, query, customRenderFunction) {
        var displayType = SearchPane.defaultDisplayType,
            targetElement = $(target),
            renderFunction = customRenderFunction;
        if (!renderFunction) {
            renderFunction = SearchPane.defaultRenderFunction;
        }
        if (query.type) {
            displayType = query.type;
        }
        $.ajax({
            type: 'GET',
            url: jsonSearchHandlerUrl + '/' + Core.language + SearchPane.searchUrl,
            dataType: SearchPane.dataType,
            async: true,
            cache: true,
            data: {
                q: query.query,
                f: query.type,
                a: query.author,
                r: query.results,
                k: query.keyword,
                community: query.community,
                sort: query.sortBy,
                dir: query.sortOrder
            },
            success: function(data) {
                targetElement.html(renderFunction(data, displayType));
            },
            error: function(xhr) {
                targetElement.text(Msg.ui.unexpectedError);
            }
        });
    },

    defaultRenderFunction: function(response, displayType) {
        var typeResults = response.results[displayType],
            container = $('<ul/>');
        container.attr('class', SearchPane.defaultResultListElementClass);
        if (!typeResults) {
            container = $('<div/>');
            container.text(Msg.search.noResults);
        } else {
            for (var i = 0; i < typeResults.length; i++) {
                var result = $('<li/>');
                var link = $('<a/>');
                link.attr('href', typeResults[i].url);
                link.text(typeResults[i].title);
                result.append(link);
                container.append(result);
            }
        }
        return container;
    }
}