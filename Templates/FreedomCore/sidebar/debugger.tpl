<div class="sidebar-module" id="sidebar-gear-store">
    <div class="sidebar-title">
        <h3 class="header-3 title-gear-store">{#Sidebar_Debugger#}</h3>
    </div>

    <div class="sidebar-content">
        <table width="100%" style="margin-top: 10px; font-size: 13px;">
            {if !is_null($PageLoadTime)}
                <tr>
                    <td>
                        <strong>Page load time:</strong>
                    </td>
                    <td>
                        {$PageLoadTime} ms
                    </td>
                </tr>
            {/if}
            {if !is_null($MemoryUsage)}
                <tr>
                    <td>
                        <strong>Memory used:</strong>
                    </td>
                    <td>
                        {$MemoryUsage} MB
                    </td>
                </tr>
            {/if}
            <tr>
                <td>
                    <strong>Smarty: </strong>
                </td>
                <td>
                    {$smarty.version}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Revision: </strong>
                </td>
                <td>
                    <a href='https://github.com/darki73/FreedomCore/commit/{$FreedomNetRevision}' target="_blank">{$FreedomNetRevision|truncate:10}</a>
                </td>
            </tr>
        </table>
    </div>
</div>