{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div  id="page-header">
                <h2 class="subcategory">{#Account_Parameters#}</h2>
                <h3 class="headline">{#Account_Management_Payment_Methods#}</h3>
            </div>
            <div id="page-content" class="page-content">
                <p>{$smarty.config.Account_Management_Parameters_Wallet_Subheader|sprintf:$AppName}</p>
                <div class="wallet-container columns-2-1">
                    <div class="column column-left">
                        <div class="subtitle">
                            <h4 class="help-link-right" data-tooltip="{$smarty.config.Account_Management_Parameters_Wallet_MainPayment_Tooltip|sprintf:$AppName}">{#Account_Management_Parameters_Wallet_MainPayment#}</h4>
                            <div class="operations"></div>
                        </div>
                        <div class="primary-payment">
                            <h5>{#Account_Management_Wallet#} {$AppName}</h5>
                            <dl class="info-section name">
                                <dt>{#Account_Management_Parameters_Wallet_Owner#}</dt>
                                <dd>
                                    {$User.username}
                                </dd>
                            </dl>
                            <dl class="info-section" >
                                <dt>{#Account_Management_Parameters_Wallet_Number#}</dt>
                                <dd>{$User.id}</dd>
                            </dl>
                            <dl class="info-section">
                                <dt>{#Account_Management_Parameters_Wallet_ValidTill#}</dt>
                                <dd>{#Account_Management_Parameters_Wallet_ValidUnlimited#}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="column column-right">
                        <div class="subtitle">
                            <h4>
                                {#Account_Management_Wallet#}
                                <span class="help-link-right" data-tooltip="{$smarty.config.Account_Management_Parameters_Wallet_Wallet_Tooltip|sprintf:$AppName}"></span>
                            </h4>
                            <div class="operations"></div>
                        </div>
                        <div class="wallet-balance">
                            <h5>{$AccountBalance.balance|string_format:'%.2f'} USD</h5>
                            <dl class="info-section pending-balance-section  ">
                                <dt>
                                    {#Account_Management_Parameters_Wallet_Operation_Verification#}
                                    <span class="help-link-right" data-tooltip="{#Account_Management_Parameters_Wallet_Operation_Verification_Tooltip#}"></span>
                                </dt>
                                <dd>0.00 USD</dd>
                            </dl>
                            <a class="ui-button button1" href="/shop/product/balance">
                                <span class="button-left">
                                    <span class="button-right">{#Account_Management_Wallet_TopUP#}</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}