<div class="summary-bottom-right">
    <div class="summary-talents" id="summary-talents">
        <h3 class="category ">
            <span class="title">{#MSG_FanSite_talentcalc#}</span>
        </h3>

        <div class="profile-box-simple">
            <div class="talent-specs" data-class-name="{$Character.class_data.name}">
                {foreach $Specializations as $Spec}
                            {if $Spec.talentGroup == $Spec.activeTalentGroup}
                            <a data-spec-id="{$Spec.talentGroup}" class="spec-button spec-{$Spec.talentGroup} selected active" href="javascript:;" data-spec-name="{$Spec.name}" data-tooltip="">
                                    <span class="inner">
                                    <span class="checkmark"></span>
                            {else}
                                <a data-spec-id="{$Spec.talentGroup}" class="spec-button spec-{$Spec.talentGroup}" href="javascript:;" data-spec-name="{$Spec.name}" data-tooltip="">
                                    <span class="inner">
                            {/if}
                            <span class="frame">
                                <span class="icon"><img src="/Templates/{$Template}/images/icons/medium/spec_{$Character.class_data.name}_{$Spec.name}.jpg" alt="" /></span>
                            </span>
                            <span class="name-build">
                                <span class="name ">{$Spec.Description}</span>
                            </span>
                        </span>
                    </a>
                {/foreach}

                <span class="clear"><!-- --></span>
            </div>
            <div class="talent-build selected" id="talent-build-0">
                <div class="talents">

                </div>
                <div class="glyphs">
                    <ul>
                        <li>
                            <h3>
                                {#Profile_Character_Profession_Glyphs_Big#}
                            </h3>
                        </li>
                        {for $i = 1; $i < 7; $i++}
                            {assign 'glyphid' 'glyph'|cat:$i}
                            {if $i == 1 || $i == 4 || $i == 6}
                                {if $Glyphs.0.$glyphid != 0}
                                    <li>
                                        <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Glyphs.0.$glyphid.icon}.jpg&quot;);" data-spell="{$Glyphs.0.$glyphid.SpellID}"></span> Glyph of {$Glyphs.0.$glyphid.Name}
                                    </li>
                                {/if}
                            {/if}
                        {/for}
                        <li>
                            <h3>
                                {#Profile_Character_Profession_Glyphs_Small#}
                            </h3>
                        </li>
                        {for $i = 1; $i < 7; $i++}
                            {assign 'glyphid' 'glyph'|cat:$i}
                            {if $i == 2 || $i == 3 || $i == 5}
                                {if $Glyphs.0.$glyphid != 0}
                                    <li>
                                        <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Glyphs.0.$glyphid.icon}.jpg&quot;);" data-spell="{$Glyphs.0.$glyphid.SpellID}"></span> Glyph of {$Glyphs.0.$glyphid.Name}
                                    </li>
                                {/if}
                            {/if}
                        {/for}
                    </ul>
                </div>
            </div>

            <div class="talent-build" id="talent-build-1" style="display: none;">
                <div class="talents">

                </div>
                <div class="glyphs">
                    <ul>
                        <li>
                            <h3>
                                {#Profile_Character_Profession_Glyphs_Big#}
                            </h3>
                        </li>
                        {for $i = 1; $i < 7; $i++}
                            {assign 'glyphid' 'glyph'|cat:$i}
                            {if $i == 1 || $i == 4 || $i == 6}
                                {if $Glyphs.1.$glyphid != 0}
                                    <li>
                                        <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Glyphs.0.$glyphid.icon}.jpg&quot;);" data-spell="{$Glyphs.0.$glyphid.SpellID}"></span> Glyph of {$Glyphs.1.$glyphid.Name}
                                    </li>
                                {/if}
                            {/if}
                        {/for}

                        <li>
                            <h3>
                                {#Profile_Character_Profession_Glyphs_Small#}
                            </h3>
                        </li>
                        {for $i = 1; $i < 7; $i++}
                            {assign 'glyphid' 'glyph'|cat:$i}
                            {if $i == 2 || $i == 3 || $i == 5}
                                {if $Glyphs.1.$glyphid != 0}
                                    <li>
                                        <span class="icon-frame frame-18 " style="background-image: url(&quot;/Templates/{$Template}/images/icons/small/{$Glyphs.0.$glyphid.icon}.jpg&quot;);" data-spell="{$Glyphs.0.$glyphid.SpellID}"></span> Glyph of {$Glyphs.1.$glyphid.Name}
                                    </li>
                                {/if}
                            {/if}
                        {/for}
                    </ul>
                </div>
            </div>

        </div>
        <script  type="text/javascript">
            //<![CDATA[
            $(document).ready(function() {

                var specLinks = {};

                specLinks[0] = "/wow/en/tool/talent-calculator#Zb!0011220!cHMYpO";
                specLinks[1] = "/wow/en/tool/talent-calculator#ZZ!0010020!cQMKLp";

                Summary.Talents(specLinks);
            });
            //]]>
        </script>
    </div>
</div>
