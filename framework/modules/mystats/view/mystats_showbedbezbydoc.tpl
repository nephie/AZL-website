<h1>Statistieken bedbezetting: Per dokter</h1>
<div class="headerline">&nbsp;</div>
<p>
<table class="grid">
	<tr class="gridhead">
		<th>
			Dokter
		</th>
		{foreach from=$diensten item=dienstnr}
		<th>
			{$dienstnr}
		</th>
		{/foreach}
		<th>
			Totaal
		</th>
	</tr>
{foreach from=$stats key=ognummer item=dokter}
{cycle values="gridrow_A,gridrow_B" assign=rowcycle}
	<tr class="gridrow {$rowcycle}">
		<td>
			{if $dokter.naam != ''}
				{$dokter.naam}
			{else}
				{$ognummer}
			{/if}
		</td>
		{foreach from=$diensten item=dienstnr}
		<td>
			{if $dokter.$dienstnr > 0}
				<strong>{$dokter.$dienstnr}</strong>
			{else}
				-
			{/if}
		</td>
		{/foreach}
		<td>
			{$dokter.all}
		</td>
	</tr>
{/foreach}
	<tr class="printgridfoot">
		<td>
			Totaal
		</td>
		{foreach from=$diensten item=dienstnr}
		<td>
			{if $ve.$dienstnr > 0}
				{$ve.$dienstnr}
			{else}
				-
			{/if}
		</td>
		{/foreach}
		<td>
			{$ve.all}
		</td>
	</tr>
</table>
</p>