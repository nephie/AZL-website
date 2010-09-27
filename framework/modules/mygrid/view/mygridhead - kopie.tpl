{assign var=order value=$grid->getOrder()}
{if is_array($order)}
	{assign var=orderfield value=$order.fields.0}
	{assign var=ordertype value=$order.type}
{else}
	{assign var=orderfield value=''}
	{assign var=ordertype value=''}
{/if}
{browser_is vendor="ie" majorversion=6 assign=ie6}
{browser_is vendor="ie" maxversion=7 assign=ie}

{if $grid->isEditAllowed() || $grid->isAddAllowed() || $grid->isDeleteAllowed()}
	{assign var=extracols value=70}
{else}
	{assign var=extracols value=20}
{/if}


<thead>
<tr class="gridhead">
	{if $grid->isEditAllowed() || $grid->isAddAllowed() || $grid->isDeleteAllowed()}
	<th class="gridadd" style="vertical-align: middle; width: 50px; text-align:left; position: relative;">
		{if $grid->isAddAllowed()}
		{assign var=addrequest value=$grid->getRequest('-add-')}
			{if $addrequest instanceof ajaxrequest}
				<a href="#" onClick="{ajaxrequest request=$addrequest}">
					<span><img src="files/images/add.png" title="Toevoegen"  id="addbutton"/></span>
				</a>
			{/if}
		{/if}
	</th>
	{/if}
	<th style="vertical-align: middle;width: 20px;">
		#
	</th>

	{foreach from=$grid->getColumn() key=key item=column name=head}

	{if is_array($column)}
		{assign var=colname value=$column.column}
	{else}
		{assign var=colname value=$column}
	{/if}

	{assign var=colorderrequest value=$grid->getSetorderrequest($colname)}

	{math equation="(x - z)/ y" x=727 y=$smarty.foreach.head.total z=$extracols assign=colwidth}

	<th style="vertical-align: middle; {if $ie}width: {$colwidth}px;{/if}">
	{if !in_array($colname,$grid->getNosortfield())}<a href="#" onClick="{ajaxrequest request=$colorderrequest}">{/if}
		{if $key != ''}
			{$key}
		{else}
			{$column}
		{/if}
		{if !in_array($colname,$grid->getNosortfield())}</a>{/if}


		{if $ie6}
			{assign var=ext value="gif"}
		{else}
			{assign var=ext value="png"}
		{/if}

		{if $orderfield == $colname}
			{if $ordertype == 'ASC'}
				<img src="files/images/triangle_up_white.{$ext}">
			{else}
				<img src="files/images/triangle_down_white.{$ext}">
			{/if}
		{else}
			<img src="files/images/triangle_none.{$ext}">
		{/if}


	</th>
	{/foreach}
	{if $grid->getOrderfield() != ''}
	<th>
		{assign var=colorderrequest value=$grid->getSetorderrequest($grid->getOrderfield())}
		<a href="#" onClick="{ajaxrequest request=$colorderrequest}">
			Orde
		</a>

		{browser_is vendor="ie" majorversion=6 assign=ie6}
		{if $ie6}
			{assign var=ext value="gif"}
		{else}
			{assign var=ext value="png"}
		{/if}

		{if $orderfield == $grid->getOrderfield()}
			{if $ordertype == 'ASC'}
				<img src="files/images/triangle_up_white.{$ext}">
			{else}
				<img src="files/images/triangle_down_white.{$ext}">
			{/if}
		{else}
			<img src="files/images/triangle_none.{$ext}">
		{/if}
	</th>
	{/if}

</tr>
</thead>