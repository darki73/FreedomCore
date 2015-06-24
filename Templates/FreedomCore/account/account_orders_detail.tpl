{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div id="page-header">
                <h2 class="subcategory">{#Account_Management_Operations#}</h2>
                <h3 class="headline">{#Account_Management_Payment_Order#} №{$Payment.id}</h3>
            </div>
            <div id="page-content" class="page-content">
                <p>
                    <a href="/account/management/orders" class="float-right" style="margin-left:12px;">{#Account_Management_FreedomTag_Warning_Close#}</a>
                    <a href="javascript:;" onclick="window.print()" class="float-right">{#Account_Management_Payment_Print#}</a>
                    {#Account_Management_Payment_By#} {$User.username} <span><time datetime="{$Payment.date|date_format:"%d/%m/%Y %H:%M"}">{$Payment.date|date_format:"%d/%m/%Y %H:%M"}</time></span>
                </p>
                <table class="invoice" style="font-size:13px;">
                    <thead>
                    <tr>
                        <th class="invoice-type"><span class="digital-goods">{#Account_Management_Payment_Digital_Goods#}</span></th>
                        <th class="align-center">{#Account_Management_Payment_Amount#}</th>
                        <th class="align-center">{#Account_Management_Payment_Price#}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="item-info">
                        <td class="item">
                            {assign 'ServiceName' 'Account_Management_Service_'|cat:strtoupper($Payment.service)}
                            <strong data-service-id="null">{#Account_Management_Service#}: {$smarty.config.$ServiceName}</strong>
                        </td>
                        <td class="align-center">1</td>
                        <td class="align-right">
                            {$Payment.price|string_format:"%.2f"} USD
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="invoice-box">
                    <h4>{#Account_Management_Payment_Status_Full#}: </h4>
                    {$Payment.status}
                </div>
                <div class="invoice-box" style="width: 650px"></div>
                <span class="clear"><!-- --></span>
                <br /><br />
                <div id="invoice-meta">
                    <div class="invoice-box">
                        <h4>Способ оплаты:</h4>
                        <strong>
                            {$AppName} {#Account_Management_Wallet#}
                        </strong>
                        <br />{$User.username}
                        <br />{$User.email}
                    </div>
                    <span class="clear"><!-- --></span>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}