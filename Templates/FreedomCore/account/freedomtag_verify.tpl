{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div id="page-content" class="page-content">
                <h3 class="headline subpage">{$smarty.config.Account_Management_FreedomTag_Created_Greetings|sprintf:$User.username}</h3>
                <div  class="full-freedomtag">
                    <span class="freedomtag-name">{$FreedomTag.tag}</span><span class="freedomtag-number">#{$FreedomTag.id}</span>
                </div>
                <div  class="black-line-note">
                    <div class="black-line-note-arrow"></div>
                    <div class="black-line-note-content border-3">
                        <p class="desc">{#Account_Management_FreedomTag_Code_Desc#}</p>
                    </div>
                </div>
                <span class="clear"></span>
                <a class="ui-button button1" href="/account/management/">
                    <span class="button-left">
                        <span class="button-right">
                            {#Account_Management_FreedomTag_Back_To_Management#}
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
<div id="layout-bottom-divider"></div>
{include file='account/account_footer.tpl'}