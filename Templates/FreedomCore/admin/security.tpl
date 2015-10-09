{include file='header.tpl'}
<div id="content">
    <div class="content-top body-top">
        <div class="content-trail">
            <ol class="ui-breadcrumb">
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a>
                </li>
                <li itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
					<a href="/admin/dashboard/" rel="np" class="breadcrumb-arrow" itemprop="url">
						<span class="breadcrumb-text" itemprop="name">{#Administrator_Title#}</span>
					</a>
				</li>
                <li class="last children" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <a href="/admin/security/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Antivirus#}</span>
                    </a>
                </li>
            </ol>
        </div>
        <div class="content-bot clear">
            <div class="wod-no-banner"></div>
            <div id="wiki" class="wiki directory wiki-index">
                <div class="panel free-paid-services">
                    <div id="free-services" class="services-column">
                        <h2 class="header">
                            {#Administrator_Antivirus_New#}
                        </h2>

                        <ul>
                            {if count($ValidationResult.new) == 0}
                                {#Administrator_Antivirus_No_New#}
                            {else}
                                {foreach $ValidationResult.new as $Result}
                                    <li>
                                        <a class="paid-services-character-boost" data-tooltip="{$Result.file}<br />{$Result.changed|date_format:"%e/%m/%Y %H:%M:%S"}">
                                            <span style="font-size: 12px;">{$Result.name} ({$Result.extension})</span>
                                        </a>
                                    </li>
                                {/foreach}
                            {/if}
                        </ul>
                    </div>
                    <div id="paid-services" class="services-column">
                        <h2 class="header">
                            {#Administrator_Antivirus_Failed#}
                        </h2>
                        <ul>
                            {if count($ValidationResult.failed) == 0}
                                {#Administrator_Antivirus_No_Failed#}
                            {else}
                                {foreach $ValidationResult.failed as $Result}
                                    <li>
                                        <a class="paid-services-name-change" data-tooltip="{$Result.file}<br />{$Result.changed|date_format:"%e/%m/%Y %H:%M:%S"}">
                                            <span style="font-size: 12px; color:red;">{$Result.name} ({$Result.extension})</span>
                                        </a>
                                    </li>
                                {/foreach}
                            {/if}
                        </ul>
                    </div>
                    <div id="paid-services" class="services-column">
                        <h2 class="header">
                            {#Administrator_Antivirus_Options#}
                        </h2>
                        <ul>
                            <li>
                                <a class="paid-services-character-customization" data-tooltip="
                                {$NTouched = $ValidationResult.scanned - (count($ValidationResult.new) + count($ValidationResult.failed))}
                                <table>
                                    <tr>
                                        <td width='100px'>{#Administrator_Antivirus_TF#}</td><td>{$ValidationResult.scanned}</td>
                                    </tr>
                                    <tr>
                                        <td>{#Administrator_Antivirus_NF#}</td><td><font color='cyan'>{count($ValidationResult.new)}</font></td>
                                    </tr>
                                    <tr>
                                        <td>{#Administrator_Antivirus_CF#}</td><td><font color='red'>{count($ValidationResult.failed)}</font></td>
                                    </tr>
                                    <tr>
                                        <td>{#Administrator_Antivirus_NT#}</td><td><font color='green'>{$NTouched}</font></td>
                                    </tr>
                                </table>
                                ">
                                    <span>
                                        {#Administrator_Antivirus_Total#}: {$ValidationResult.scanned}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="free-services-security" data-tooltip="{#Administrator_Antivirus_Integrity_Tooltip#}">
                                    <span>
                                        {$Integrity = 100 - (((count($ValidationResult.failed) + count($ValidationResult.new)) / $ValidationResult.scanned) * 100)}
                                        {#Administrator_Antivirus_Integrity#}: {$Integrity|string_format:"%.2f"}%
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a class="paid-services-race-change" data-tooltip="{$ValidationResult.lastupdate|date_format:"%e/%m/%Y %H:%M:%S"}">
                                    <span>
                                        {#Administrator_Antivirus_LastScan#}: {$ValidationResult.lastupdate|relative_date}
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a onclick="RenewSecurityList();" class="free-services-item-restoration" data-tooltip="{#Administrator_Antivirus_Renew_Tooltip#}">
                                    <span>
                                        {#Administrator_Antivirus_Rescan#}
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}