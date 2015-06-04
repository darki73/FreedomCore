<div id="summary-raid" class="summary-raid">
    <h3 class="category">Рейдовый прогресс</h3>
    <div class="profile-box-full">

        <div class="prestige"><div>Самое престижное рейдовое звание:
                <strong>
                    <a href="/character/kazzak/Russianfur/achievement#168:15107:a8680" data-achievement="8680"> освободитель Оргриммара</a>
                </strong>
            </div></div>

        <div class="summary-raid-wrapper">

            <div class="summary-raid-wrapper-left">

                <a id="summary-raid-arrow-left" class="arrow-left" href="javascript:;"></a>
                <a id="summary-raid-arrow-right" class="arrow-right" href="javascript:;"></a>

                <div class="lfr"><span>СПР</span></div>
                <div class="normal"><span>Нормальный ур.</span></div>
                <div class="heroic"><span>Героический ур.</span></div>
                <div class="mythic"><span>Эпохальный</span></div>

            </div>

            <div id="summary-raid-wrapper-table" class="summary-raid-wrapper-table">
                <table cellspacing="0">
                    <thead>
                    <tr>
                        <th class="spacer-left"><div></div></th>
                        <th class="trivial" colspan="63">
                            <div class="name-anchor">
                                <div class="name" id="summary-raid-head-trivial">Легко</div>
                            </div>
                            <div class="marker"></div>
                        </th>
                        <th></th>
                        <th class="optimal" colspan="3">
                            <div class="name-anchor">
                                <div class="name">Средне</div>
                            </div>
                            <div class="marker"></div>
                        </th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    {include file='parts/Character_Raid_Raids.tpl'}
                    {include file='parts/Character_Raid_LFR.tpl'}
                    {include file='parts/Character_Raid_Normal.tpl'}
                    {include file='parts/Character_Raid_Heroic.tpl'}
                    {include file='parts/Character_Raid_Mythic.tpl'}
                    </tbody>
                </table>
            </div>
            <span class="clear"><!-- --></span>
        </div>
        <div class="summary-raid-legend">
            <span class="completed">Завершено</span>
            <span class="in-progress">В процессе</span>
        </div>
        {include file='parts/Character_Raid_Progression.tpl'}
    </div>
</div>