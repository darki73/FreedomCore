{include file='account/account_header.tpl'}
<div id="layout-middle">
    <div class="wrapper">
        <div id="content">
            <div class="dashboard service">
                <div class="primary">
                    <div  class="header">
                        <h2 class="subcategory">{#Account_Management_Service_CS#}</h2>
                        <h3 class="headline">{$Service.title} - {$Character.name}</h3>
                        <a href="/account/management/dashboard?accountName=WoW{$Account.id}"><img src="/Templates/{$Template}/images/game-icons/wowx5.png" alt="World of Warcraft" width="48" height="48" /></a>
                    </div>
                    <div class="service-wrapper">
                        <p class="service-nav">
                            <a href="/account/management/services/character-services?accountName=WoW{$Account.id}&amp;service={$Service.service}" class="active">{#Account_Management_Service#}</a>
                            <a href="/account/management/dashboard?accountName=WoW{$Account.id}">{#Account_Management_Back_To_Account#}</a>
                        </p>
                        <div id="page-content">
                            <form method="post" action="" id="item-selection-form">
                                <div class="item-selection">
                                    <div class="outer-item-selection-container">
                                        <div id="deleted-item-dataset" class="item-selection-container">
                                            <div id="item-filter">
                                                <div class="filter-field">
                                                    <label class="filter-field-label">Название</label>
                                                    <span class="input-text input-text-small">
                                                        <input type="text" id="filter-name" class="small border-5 glow-shadow-2" maxlength="50" />
                                                    </span>
                                                </div>
                                                <div class="filter-field">
                                                    <label class="filter-field-label">Категория</label>
                                                    <span class="input-select input-select-small">
                                                        <select id="filter-category" class="small border-5 glow-shadow-2">
                                                            <option value="-1">Все</option>
                                                            <option value="2">Оружие</option>
                                                            <option value="4">Доспехи</option>
                                                            <option value="1">Сумки</option>
                                                            <option value="0">Расходуемые</option>
                                                            <option value="7">Хозяйственные товары</option>
                                                            <option value="9">Рецепты</option>
                                                            <option value="3">Самоцветы</option>
                                                            <option value="15">Разное</option>
                                                            <option value="12">Задания</option>
                                                        </select>
                                                    </span>
                                                </div>
                                                <div class="filter-field">
                                                    <label class="filter-field-label">Уровень</label>
                                                    <div class="ilvl-range">
                                                        <span class="input-text">
                                                            <input type="text" id="filter-range-min" class="small border-5 glow-shadow-2" maxlength="4" />
                                                        </span>-
                                                        <span class="input-text">
                                                            <input type="text" id="filter-range-max" class="small border-5 glow-shadow-2" maxlength="4" />
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="filter-field">
                                                    <label class="filter-field-label">Редкость</label>
                                                    <span class="input-select input-select-small">
                                                        <select id="filter-rarity" class="small border-5 glow-shadow-2">
                                                            <option value="0">Низкое</option>
                                                            <option value="1">Среднее</option>
                                                            <option value="2">Необычное</option>
                                                            <option value="3" selected="selected">Редкое</option>
                                                            <option value="4">Превосходное</option>
                                                            <option value="5">Легендарное</option>
                                                        </select>
                                                    </span>
                                                </div>
                                                <div class="filter-field">
                                                    <button class="ui-button button-small" type="button" id="filter-apply">
                                                        <span>
                                                            <span>
                                                                Фильтр
                                                            </span>
                                                        </span>
                                                    </button>
                                                    <a href="javascript:;" id="filter-cancel">Сброс</a>
                                                </div>
                                                <span class="clear"><!-- --></span>
                                            </div>
                                            <table id="deleted-item-list" class="data-container type-table" style="display: table; font-size: 13px;">
                                                <thead>
                                                <tr>
                                                    <th scope="col" class="item-id hide"></th>
                                                    <th scope="col" class="item-name">
                                                        <a href="#" class="sort-link">
                                                            <span class="arrow">
                                                                НАЗВАНИЕ
                                                            </span>
                                                        </a>
                                                    </th>
                                                    <th scope="col" class="item-ilvl"
                                                    ><a href="#" class="sort-link numeric">
                                                            <span class="arrow">
                                                                Уровень
                                                            </span>
                                                        </a>
                                                    </th>
                                                    <th scope="col" class="item-deleted">
                                                        <a href="#" class="sort-link">
                                                            <span class="arrow down">
                                                                Удалено
                                                            </span>
                                                        </a>
                                                    </th>
                                                    <th scope="col" class="item-slot">
                                                        <a href="#" class="sort-link">
                                                            <span class="arrow">
                                                                Категория
                                                            </span>
                                                        </a>
                                                    </th>
                                                    <th scope="col" class="item-qty">
                                                        <a href="#" class="sort-link">
                                                            <span class="arrow">
                                                                Количество
                                                            </span>
                                                        </a>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    {assign "RowID" "1"}
                                                    {foreach $DItems as $Item}
                                                        <tr class="item-row rarity-{$Item.data.Quality}" data-instance="{$Item.item_id}_{$Item.sell_time}" data-rowid="{$RowID}">
                                                            <td  class="hide">
                                                                <input type="checkbox" name="items" value="{$Item.item_id}_{$Item.sell_time}" data-instanceid="{$Item.item_id}_{$Item.sell_time}" class="hide" />
                                                            </td>
                                                            <td data-raw="{$Item.data.name}" class="item-name item-block item">
                                                                <div class="item grid-container" data-instance="{$Item.item_id}_{$Item.sell_time}">
                                                                    <div  class="item-icon grid-20">
                                                                        <a href="/item/{$Item.itemid}" onclick="Tooltip.hide(); return false;" class="icon-frame frame-36" style="background-image: url('/Templates/FreedomCore/images/icons/medium/{$Item.data.icon}.jpg');" data-item="s=1" data-itemid="{$Item.data.entry}" data-itemcc="0"></a>
                                                                    </div>
                                                                    <div class="item-details grid-80">
                                                                        <a href="javascript:;" class="item-link color-q{$Item.data.Quality}" data-item="s=1" data-itemid="{$Item.data.entry}" data-itemcc="11" onclick="Tooltip.hide();">
                                                                            <strong>{$Item.data.name}</strong>
                                                                        </a>
                                                                        <br />
                                                                        <span class="deleted-reason" onmouseover="Tooltip.show(this, 'Этот предмет сразу появится у вас в почтовом ящике в игре. Чтобы восстановить этот предмет, нужно будет оплатить его наложенным платежом по цене, установленной владельцем.', {ldelim}location: 'mouse'{rdelim});" onclick="Tooltip.hide();">
                                                                            <span class="reason">Продано (оплата наложенным платежом):</span>
                                                                            <span class="item-cod-price">
                                                                                <span class="icon-gold" data-gold="{$Item.price.gold}">{$Item.price.gold}</span>
                                                                                <span class="icon-silver" data-silver="{$Item.price.silver}">{$Item.price.silver}</span>
                                                                                <span class="icon-copper" data-copper="{$Item.price.copper}">{$Item.price.copper}</span>
                                                                            </span>
                                                                        </span>
                                                                        <div class="remove"><a class="remove-link" href="javascript:Restoration.removeItem('{$Item.item_id}_{$Item.sell_time}');" onmouseover="Tooltip.show(this, 'Удалить', {ldelim}location: 'mouse'{rdelim});" onclick="Tooltip.hide();"></a></div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="align-center" data-raw="{$Item.data.ItemLevel}">
                                                                {$Item.data.ItemLevel}
                                                            </td>
                                                            <td data-raw="{$Item.sell_time|date_format:'Y-m-d H:i:s'}">
                                                                <time datetime="{$Item.sell_time|date_format:'Y-m-d H:i:s'}">
                                                                    {$Item.sell_time|date_format:'d/m/Y H:i:s'}
                                                                </time>
                                                            </td>
                                                            <td data-raw=" {$Item.data.class.translation} ({$Item.data.class.translation} ) "> {$Item.data.subclass.translation}
                                                                <em>({$Item.data.subclass.translation} )</em>
                                                            </td>
                                                            <td data-raw="1">1</td>
                                                            <td style="display: none;">{$Item.data.Quality}</td>
                                                            <td style="display: none;" class="selected-row-filter">1</td>
                                                            <td data-raw="{$Item.data.class.class}" style="display: none;">{$Item.data.class.class}</td>
                                                        </tr>
                                                        {$RowID = $RowID + 1}
                                                    {/foreach}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="selected-items-container">
                                        <div id="added-items">
                                            <h3 class="selected-items-title">Выбранные предметы</h3>
                                            <div id="added-item-list">

                                            </div>
                                            <div id="selected-items-hidden" style="display: none"></div>
                                            <div id="added-item-total" class="hide">
                                                <div>
                                                    <strong>Total COD:</strong>
                                                </div>
                                                <span class="icon-gold">0</span>
                                                <span class="icon-silver">0</span>
                                                <span class="icon-copper">0</span>
                                                <div class="disenchant-needed-materials"></div>
                                            </div>
                                            <button class="ui-button button-small" type="button" id="selected-continue" onclick="$('#item-selection-form').submit();" style="display: none;">
                                                <span>
                                                    <span>
                                                        Continue
                                                    </span>
                                                </span>
                                            </button>
                                        </div>
                                        <div id="hidden-items" style="display: none;"></div>
                                    </div>
                                    <span class="clear"></span>
                                </div>
                            </form>
                            <script type="text/javascript">
                                //<![CDATA[
                                $(document).ready(function() {
                                    setTimeout(function() {
                                        Restoration.updateSelectedItems([]);
                                    }, 1);
                                });
                                //]]>
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file='account/account_footer.tpl'}