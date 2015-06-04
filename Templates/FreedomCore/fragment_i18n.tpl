

<div id="international">
    <h3>{#Language_Change_To#}</h3>
		<div class="column">
			<ul>
			    <li>
			        <a href="/changelanguage/en" {if $Language == 'en'}class="selected"{/if}
                                tabindex="100" onclick="Locale.trackEvent('Change Language', '{$Language} to en'); return true;">
							   English
                    </a>
                </li>
				<li>
				    <a href="/changelanguage/es" {if $Language == 'es'}class="selected"{/if}
								tabindex="100" onclick="Locale.trackEvent('Change Language', '{$Language} to es'); return true;">
							   Español
				    </a>
				</li>
			</ul>
		</div>
    <div class="column">
        <ul>
            <li>
                <a href="/changelanguage/ru" {if $Language == 'ru'}class="selected"{/if}
                   tabindex="100" onclick="Locale.trackEvent('Change Language', '{$Language} to ru'); return true;">
                    Русский
                </a>
            </li>
            <li>
                <a href="/changelanguage/kr" {if $Language == 'kr'}class="selected"{/if}
                   tabindex="100" onclick="Locale.trackEvent('Change Language', '{$Language} to kr'); return true;">
                    한국어
                </a>
            </li>
        </ul>
    </div>

    <div class="column">
        <ul>
            <li>
                <a href="/changelanguage/pt" {if $Language == 'pt'}class="selected"{/if}
                   tabindex="100" onclick="Locale.trackEvent('Change Language', '{$Language} to pt'); return true;">
                    Português
                </a>
            </li>
            <li>
                <a href="/changelanguage/fr" {if $Language == 'fr'}class="selected"{/if}
                   tabindex="100" onclick="Locale.trackEvent('Change Language', '{$Language} to fr'); return true;">
                    Français
                </a>
            </li>
        </ul>
    </div>

    <div class="column">
        <ul>
            <li>
                <a href="/changelanguage/de" {if $Language == 'de'}class="selected"{/if}
                   tabindex="100" onclick="Locale.trackEvent('Change Language', '{$Language} to de'); return true;">
                    Deutsch
                </a>
            </li>
            <li>
                <a href="/changelanguage/it" {if $Language == 'it'}class="selected"{/if}
                   tabindex="100" onclick="Locale.trackEvent('Change Language', '{$Language} to it'); return true;">
                    Italiano
                </a>
            </li>
        </ul>
    </div>

		<script type="text/javascript">
		//<![CDATA[
		Locale.trackEvent('Open Menu', '{$Language}');
		//]]>
		</script>

	<span class="clear"><!-- --></span>
</div>

