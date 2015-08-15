<div id="item_accordion" class="endpoint_accordion">
    <h3>
        <span class="method_type">GET</span>
        <span class="endpoint_name">ITEM</span>
        <span class="method_uri">/API/ITEM/SET/:ID</span>
    </h3>
    <ul>
        <li class="method get">
            <form class="method_form" style="overflow: hidden; display: block;">
                <input name="dataName" id="dataName" type="hidden" value="itemset">
                <input name="methodName" id="methodName" type="hidden" value="item">
                <input name="httpMethod" id="httpMethod" type="hidden" value="GET">
                <input name="methodUri" id="methodUri" type="hidden" value="/api/item/set/:id">
                <table class="method_table_parameters" style="margin: 10px;">
                    <thead style="text-align: left;">
                    <tr>
                        <th>{#Developer_Form_Parameter#}</th>
                        <th>{#Developer_Form_Value#}</th>
                        <th>{#Developer_Form_Description#}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="type-pathReplace required ">
                        <td class="name">
                            :id
                        </td>
                        <td class="parameter">
                            <input type="text" name="params[:id]" id="id" value="901" class="input" placeholder="required" required>
                        </td>
                        <td class="description">
                            <p></p>
                        </td>
                    </tr>
                    <tr class="type-query  ">
                        <td class="name">
                            locale
                        </td>
                        <td class="parameter">
                            <select id="locale" class="input" name="params[locale]" placeholder="">
                                <option value="en_US" selected="selected">
                                    en_US
                                </option>
                                <option value="pt_BR">
                                    pt_BR
                                </option>
                                <option value="es_MX">
                                    es_MX
                                </option>
                                <option value="ru_RU">
                                    ru_RU
                                </option>
                            </select>
                        </td>
                        <td class="description">
                            <p>{#Developer_Data_Locale#}</p>
                        </td>
                    </tr>
                    <tr class="type-query  ">
                        <td class="name">
                            jsonp
                        </td>
                        <td class="parameter">
                            <input type="text" class="input" id="jsonp" name="params[jsonp]" value="" placeholder="">
                        </td>
                        <td class="description">
                            <p>{#Developer_Data_JSONP#}</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <button class="get-results" style="margin: 10px;" type="submit" onclick="return PerformJsonRequest($(this).parent('form'));">{#Developer_Form_TryIt#}</button>
                <button class="clear-results" style="margin: 10px; display: none;" type="submit">{#Developer_Form_Reset#}</button>

                <div class="request_result" id="itemset_response">

                </div>
            </form>

        </li>
    </ul>
</div>