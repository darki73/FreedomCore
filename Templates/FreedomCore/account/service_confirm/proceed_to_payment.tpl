    <div class="service-interior light">
        <fieldset class="ui-controls section-stacked override">
            <button class="ui-button button1" type="submit" tabindex="1">
                <span class="button-left">
                    <span class="button-right">
                        {#Account_Management_Service_Proceed_To_Payment#}
                    </span>
                </span>
            </button>
            <a class="ui-cancel " href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service=PFC&amp;servicecat=tos&amp;character={$Character.name}" tabindex="1">
                <span>
                    {#Account_Management_Service_ToS_Decline#}
                </span>
            </a>
        </fieldset>
    </div>
</form>