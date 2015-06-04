<div class="summary-bottom-right">
    <div class="summary-talents" id="summary-talents">
        <h3 class="category ">
            <span class="title">Talents</span>
            <a name="talents" href="#" target="_blank" id="export-build" class="talent-export">
                View in Talent Calculator<span class="arrow"></span>
            </a>
        </h3>

        <div class="profile-box-simple">
            <div class="talent-specs" data-class-name="{$Character.class_data.name}">
                {foreach $Specializations as $Spec}
                            {if $Spec.spec == $Spec.activespec}
                            <a data-spec-id="{$Spec.spec}" class="spec-button spec-{$Spec.spec} selected active" href="javascript:;" data-spec-name="{$Spec.name}" data-tooltip="">
                                    <span class="inner">
                                    <span class="checkmark"></span>
                            {else}
                                <a data-spec-id="{$Spec.spec}" class="spec-button spec-{$Spec.spec}" href="javascript:;" data-spec-name="{$Spec.name}" data-tooltip="">
                                    <span class="inner">
                            {/if}
                            <span class="frame">
                                <span class="icon"><img src="/Templates/{$Template}/images/icons/medium/spec_{$Character.class_data.name}_{$Spec.name}.jpg" alt="" /></span>
                            </span>
                            {*<span class="roles">*}
                                    {*<span class="icon-tank"></span>*}
                            {*</span>*}
                            <span class="name-build">
                                <span class="name ">{$Spec.Description}</span>
                            </span>
                        </span>
                    </a>
                {/foreach}

                <span class="clear"><!-- --></span>
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
