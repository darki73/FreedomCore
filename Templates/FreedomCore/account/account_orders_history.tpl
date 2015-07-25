{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div  id="page-header">
                <h2 class="subcategory">{#Account_Management_Operations#}</h2>
                <h3 class="headline">{#Account_Management_Orders_History#}</h3>
            </div>
            <div id="page-content" class="page-content">
                <div  id="order-history">
                    <div class="data-container type-table">
                        <table style="font-size:12px;">
                            <thead>
                            <tr class="">
                                <th align="left" width="9%">{#Account_Management_Payment_Order#}</th>
                                <th align="left" width="8%">{#Date#}</th>
                                <th align="center" width="42%">{#Account_Management_Payment_ItDesc#}</th>
                                <th align="center" width="14%">{#Account_Management_Payment_Price#}</th>
                                <th algin="center" width="5%">{#Account_Management_Payment_Amount#}</th>
                                <th align="center" width="9%">{#Status#}</th>
                                <th align="center" width="13%">{#Account_Management_Payment_Total#}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $Payments as $Payment}
                                <tr class="parent-row" data-click="/account/management/orders/order-detail?orderID={$Payment.id}">
                                    <td valign="top">
                                        <a href="/account/management/orders/order-detail?orderID={$Payment.id}">{$Payment.id}</a>
                                    </td>
                                    <td valign="top" data-raw="{$Payment.date|date_format:"%Y%m%d%H%M"}">
                                        <span>
                                            <time datetime="{$Payment.date|date_format:"%d/%m/%Y"}">{$Payment.date|date_format:"%d/%m/%Y"}</time>
                                        </span>
                                    </td>
                                    <td valign="top">
                                        {if strlen($Payment.service) == 3}
                                            {assign 'ServiceName' 'Account_Management_Service_'|cat:strtoupper($Payment.service)}
                                            <strong data-service-id="null">{#Account_Management_Service#}: {$smarty.config.$ServiceName}</strong>
                                        {else}
                                            <strong data-service-id="null">{$Payment.item_data.category_desc} {$Payment.item_data.item_name}</strong>
                                        {/if}
                                    </td>
                                    <td valign="top" class="align-right item-price">
                                        {$Payment.price|string_format:"%.2f"} USD
                                    </td>
                                    <td valign="top" class="align-center">1</td>
                                    <td valign="top" class="align-center status-success">
                                        {$Payment.status}
                                    </td>
                                    <td valign="top" class="align-right" data-raw="{$Payment.price}">{$Payment.price|string_format:"%.2f"} USD</td>
                            {/foreach}
                            </tr>

                            </tbody>
                            <script>
                                $(function() {
                                    $('[data-click]').on('mousedown', 'td', function(e) {
                                        $(this).data('clickstart', e.timeStamp);
                                    });
                                    $('[data-click]').on('mouseup', 'td', function(e) {
                                        if(e.which != 1 || e.metaKey || e.ctrlKey || e.altKey) {
                                            return false;
                                        }
                                        if(e.timeStamp - $(this).data('clickstart') > 500) {
                                            return false;
                                        }
                                        document.location.href = $(this).closest('[data-click]').data('click');
                                    });
                                });
                            </script>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}