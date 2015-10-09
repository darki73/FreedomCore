<tr class="normal">
    <td></td>
    {foreach $Raids.Classic as $ClassicRaids}
        {$ClassicRaids.html}
    {/foreach}
    {foreach $Raids.TBC as $TBCRaids}
        {$TBCRaids.html}
    {/foreach}
    {foreach $Raids.WotLK as $WotLKRaids}
        {$WotLKRaids.html}
    {/foreach}

    {*<td data-raid="bh" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="bd" class="status status-completed"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="bot" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="tfw" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="fl" class="status status-completed"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="ds" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="mv" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="hf" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="tes" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="tot" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="soo" class="status status-incomplete"><div></div></td>*}
    {*<td></td>*}
    {*<td data-raid="hm" class="status status-in-progress"><div></div></td>*}
    {*<td></td>*}
    <td data-raid="brf" class="status status-incomplete"><div></div></td>
</tr>