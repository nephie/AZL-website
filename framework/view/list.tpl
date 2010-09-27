<table class="list fullWidth" cellpadding="0px" cellspacing="0px">
    <tr class="listHead">
        {include file=$callbackHead}
    </tr>
    {foreach from=$data item=row name=$name}
    <tr class="{cycle name="browseList" values="alternateOne,alternateTwo"}">
        {include file=$callbackRow row=$row name=$name}
    </tr>
    {/foreach} 
    {if $footer}
    <tr class="listFooter">
        {include file=$callbackFooter footer=$footer}
    </tr>
    {/if}
</table>