<table border="0" cellpadding="0" cellspacing="0" id="outerTable" style="font-size: 14px; font-weight: 400; text-align: left; background-color: #202835; color: #BFC1C5; -webkit-font-smoothing: antialiased;" width="720">
    <tbody>
    <tr>
        <td align="left" height="60" id="header" width="720">
            <img alt="{$AppName} Logo" height="60" src="http://{$Website}/Templates/FreedomCore/images/logos/bnet-default.png" style="display: block; border: none; padding-left: 24px;" width="187"></td>
    </tr>

    <tr>
        <td style=
            "text-align: left; background-color: #242D3D; border-top-width: 1px; border-top-style: solid; border-top-color: #3E424E; color: #BFC1C5; padding: 0 30px 0 30px;">
            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="font-weight: 200; font-size: 28px; color: #FFFFFF; padding: 40px 0; margin: 0px 0px 0px 0px; display: block;">
                        {$Account.username|ucfirst},<br />{#Shop_Thanks_For_Purchase#}
                    </td>
                </tr>
                </tbody>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        {#Shop_Email_Item_Purchased#|sprintf:$ItemData['item_name']}<br />
                        {#Shop_Email_Activation_Code#}<br />
                        <span style="height: auto;display: inline-block; vertical-align: middle; height: 34px; padding: 6px 6px 10px 10px; margin-top: 3px; margin-bottom: 3px; font-size: 15px; line-height: 20px; border: 1px solid rgba(255,255,255,0.3); background-color: rgba(0,0,0,0.5); color: rgba(255,255,255,0.7); -moz-box-sizing: border-box; box-sizing: border-box;border-radius: 2px;">
                            {$ActivationCode}
                        </span>
                        <br />
                        {#Shop_Email_CodeOnlyFor#} <strong><span style="color: #BFC1C5;">WoW{$Account.id}</span></strong>
                    </td>
                </tr>
                </tbody>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                            <span style="padding: 14px 0; display: inline-block;">
                                <a href="http://{$Website}/account/management/claim-code" style="font-weight: 400; padding: 6px 40px; font-size: 15px; background-color: #098CC8; color: #fff; border-radius: 2px; background-image: linear-gradient; border: 0; box-shadow: 0 1px 1px rgba; text-decoration: none; -webkit-font-smoothing: antialiased;" target="_blank">
                                    &nbsp; {#Shop_Email_Use_Code#} &nbsp;
                                </a>
                            </span>
                    </td>
                </tr>
                </tbody>
            </table>


            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        {#Shop_Button_ButtonNotWorking#}
                        <a href="http://{$Website}/account/management/claim-code" style="color: #00AEFF; text-decoration: none;" target="_blank">
                            http://{$Website}/account/management/claim-code
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" class="" id=
            "" width="100%">
                <tbody>
                <tr>
                    <td style=
                        "padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        &nbsp;</td>
                </tr>
                </tbody>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" class="" id=
            "" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        {#Account_Registration_WhyActivate#}
                    </td>
                </tr>
                </tbody>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" class="" id=
            "" width="100%">
                <tbody>
                <tr>
                    <td style=
                        "padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        {#Shop_Email_Thank_You_Footer#} {$AppName}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td style=
            "font-size: 12px; color: #757B90; border-top: 1px solid #3E424E; padding: 30px;">
            <table border="0" cellpadding="0" cellspacing="0" class="" id=""
                   width="100%">
                <tbody>
                <tr>
                    <table border="0" cellpadding="0" cellspacing=
                    "0" class="" id="" width="100%">
                        <tbody>
                        <tr>
                            <td style="font-size: 12px; color: #757B90; margin: 0px 0px 0px 0px; padding-bottom: 5px; color: #757B90; display: block; -webkit-font-smoothing: antialiased;">
                                &copy; 2015 {$AppName}, All Rights Reserved
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>