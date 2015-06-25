{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div  id="page-header">
                <h2 class="subcategory">{#Account_Parameters#}</h2>
                <h3 class="headline">{#Account_Management_Contacts#}</h3>
            </div>
            <div class="address-book columns-2-1">
                <div class="edit-addresses column column-left">
                    <div class="saved-addresses">
                        <div class="address-entry" id="wallet-0">
                            <h4 class="caption supporting-header">
                                {#Account_Management_Parameters_PersonalInformation#}
                                <span class="edit-delete">
                                    <a href="/account/management/settings/edit-address" tabindex="1">
                                        <span class="icon-16 icon-account-edit"></span>
                                        <span class="icon-16-label">{#Account_Management_Parameters_PersonalInformation_Edit#}</span>
                                    </a>
                                </span>
                            </h4>
                            <div class="columns-2">

                            </div>
                            <div class="address-actions">
                                <p class="activate-primary">
                                <div class="primary-address text-green">
                                    <span class="icon-16 icon-bullet-checkmark"></span>
                                    <span class="icon-16-label">{#Account_Management_Parameters_PersonalInformation_MainAddress#}</span>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add-addresses column column-right">
                    <div class="address-information">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}