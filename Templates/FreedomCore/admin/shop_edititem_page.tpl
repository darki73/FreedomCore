{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div  id="page-header">
                <h2 class="subcategory">
                    <a href="/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{$AppName}</span>
                    </a> >
                    <a href="/admin/dashboard/" rel="np" class="breadcrumb-arrow" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Title#}</span>
                    </a> >
                    <a href="/admin/shop/" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Shop#}</span>
                    </a> >
                    <a href="/admin/shop/edit-item" rel="np" itemprop="url">
                        <span class="breadcrumb-text" itemprop="name">{#Administrator_Shop_EditItem#}</span>
                    </a>
                </h2>
                <br />
                <h3 class="headline">{$ItemData.item_name}</h3>
                <h2 class="subcategory">{#Administrator_Shop_EditItem#}</h2>
            </div>
            <div class="dashboard service">
                <form method="post" action="/admin/shop/edit-item-complete" id="edit-item" novalidate="novalidate">
                <div id="page-content" class="page-content">
                    <div class="columns-2-1 settings-content">
                        <div class="column column-left">
                            <div class="item-change">
                                <span class="clear"></span>
                                    <input type="hidden" id="item_id" name="item_id" value="{$ItemData.id}">
                                    <div class="input-row input-row-text">
                                    <span class="input-left">
                                        <label for="item_name">
                                            <span class="label-text">
                                                {#Administrator_Shop_Edit_Item_Name#}:
                                            </span>
                                        </label>
                                    </span>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="text" name="item_name" value="{$ItemData.item_name}" id="item_name" class="small border-5 glow-shadow-2" autocomplete="off" onpaste="return false;" maxlength="319" tabindex="1" required="required" />
                                        </span>
                                    </span>
                                    </div>
                                    <div class="input-row input-row-text" style="margin-top: 15px;">
                                    <span class="input-left">
                                        <label for="item_price">
                                            <span class="label-text">
                                                {#Administrator_Shop_Edit_Item_Price#}:
                                            </span>
                                        </label>
                                    </span>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="text" name="item_price" value="{$ItemData.price}" id="item_price" class="small border-5 glow-shadow-2" autocomplete="off" onpaste="return false;" maxlength="319" tabindex="1" required="required" />
                                        </span>
                                    </span>
                                    </div>

                                    <div class="submit-row" id="submit-row">
                                        <div class="input-left"></div>
                                        <div class="input-right">
                                            <button class="ui-button button1" type="submit" id="password-submit" tabindex="1">
                                            <span class="button-left">
                                                <span class="button-right">{#Administrator_Shop_Edit_Item_Save#}</span>
                                            </span>
                                            </button>
                                            <a class="ui-cancel " href="/admin/dashboard/" tabindex="1">
                                            <span>
                                                {#Account_Management_Parameters_Cancel#}
                                            </span>
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="column column-right">
                            <div class="email-information">
                                <p class="caption">{#Administrator_Shop_Edit_Item_Template#}</p>
                                <span class="input-right">
                                    <span class="input-select input-select-small">
                                    <select name="background_template" id="background_template" class="small border-5 glow-shadow-2" style="width: 80%;" tabindex="1" required="required">
                                        {foreach $ItemData.backgrounds.types as $BG}
                                            {if $ItemData.item_background == $BG.image}
                                                <option selected value="{$BG.image}" data-background-type="{$BG.name}">
                                                    {$BG.name}
                                                </option>
                                            {else}
                                                <option value="{$BG.image}" data-background-type="{$BG.name}">
                                                    {$BG.name}
                                                </option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                    <img class="border-10 glow-shadow-2" id="template_preview" name="template_preview" style="width: 250px; margin-top: 20px;">
                                </span>
                                <p></p>
                                <p class="caption">{#Administrator_Shop_Edit_Item_Color#}</p>
                                <p>
                                    <span class="input-right">
                                        <span class="input-text input-text-small">
                                            <input type="text" id="color_template" name="color_template" class="small border-5 glow-shadow-2" style="width: 80%;" readonly="readonly"/>
                                        </span>
                                    </span>
                                </p>
                                    <script type="text/javascript">
                                        var TemplatePreview = $('#template_preview');
                                        var TemplateColor = $('#color_template');
                                        var ImagePath = "{$ItemData.backgrounds.images_folder}";
                                        var Sizes = [{$ItemData.backgrounds.sizes|implode: ','}];
                                        var Extension = ".jpg";
                                        $('#background_template').on('change', function() {
                                            var Selected = this.value;
                                            var Path = ImagePath+Selected+"_1024.jpg";
                                            var Color = "";
                                            TemplatePreview.attr('src', Path);
                                            if(Selected == 'item_lk')
                                                Color = "#050933";
                                            else if(Selected == 'item_before_lk')
                                                Color = "#39100d";
                                            else
                                                Color = "#240a08";
                                            TemplateColor.attr('value', Color);
                                            TemplateColor.css("background-color", Color);
                                        });
                                        $(document).ready(function() {
                                            var SelectedTempalte = $('#background_template').find(":selected").val();
                                            var Path = ImagePath+SelectedTempalte+"_1024.jpg";
                                            var Color = "";
                                            if(SelectedTempalte == 'item_lk')
                                                Color = "#050933";
                                            else if(SelectedTempalte == 'item_before_lk')
                                                Color = "#39100d";
                                            else
                                                Color = "#240a08";
                                            TemplatePreview.attr('src', Path);
                                            TemplateColor.attr('value', Color);
                                            TemplateColor.css("background-color", Color);
                                        });
                                    </script>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>




<pre>
   {$ItemData|print_r}
</pre>

{include file='account/account_footer.tpl'}