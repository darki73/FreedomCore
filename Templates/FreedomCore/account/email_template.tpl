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
                        {$Account.username|ucfirst},<br />{#Account_Registration_ThankYou#}
                    </td>
                </tr>
                </tbody>
            </table>
            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        {#Account_Registration_JustRegistered#}
                    </td>
                </tr>
                </tbody>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                            <span style="padding: 14px 0; display: inline-block;">
                                <a href="http://{$Website}/account/activate?email={$Account.email}&username={$Account.username}&code={$Account.activation_code}"style="font-weight: 400; padding: 6px 40px; font-size: 15px; background-color: #098CC8; color: #fff; border-radius: 2px; background-image: linear-gradient; border: 0; box-shadow: 0 1px 1px rgba; text-decoration: none; -webkit-font-smoothing: antialiased;" target="_blank">
                                    &nbsp; {#Account_Registration_ActivateAccount#} &nbsp;
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
                        {#Account_Registration_ButtonNotWorking#}
                        <a href="http://{$Website}/account/activate?email={$Account.email}&username={$Account.username}&code={$Account.activation_code}" style="color: #00AEFF; text-decoration: none;" target="_blank">
                            http://{$Website}/account/activate?email={$Account.email}&username={$Account.username}&code={$Account.activation_code}
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        &nbsp;</td>
                </tr>
                </tbody>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        {#Account_Registration_WhyActivate#}
                    </td>
                </tr>
                </tbody>
            </table>

            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px 0; margin: 0px 0px 0px 0px; color: #BFC1C5; display: inline-block; -webkit-font-smoothing: antialiased;">
                        {#Account_Registration_HappyGame#} {$AppName}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

    <tr>
        <td style="font-size: 12px; color: #757B90; border-top: 1px solid #3E424E; padding: 30px;">
            <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
                <tbody>
                <tr>
                        <table border="0" cellpadding="0" cellspacing="0" class="" id="" width="100%">
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