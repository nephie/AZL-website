{include file="form.tpl" form=$form}
{if !$new}
Deze optie selecteren resulteert in het activeren van de volgende optiegroepen:<br /><br />
{include file="mygrid.tpl" grid=$optionoptionsetgrid columns="array('Naam' => 'optionset' , 'Type' => 'optionsettype')"}
{/if}