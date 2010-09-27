<h1>Mijn bestelling</h1>
<div class="headerline">&nbsp;</div>

<p>
	<table>
		<tr>
			<td>
				<strong>Uur van afhaling: </strong>
			</td>
			<td>
				{$order.uur|date_format:"%H:%M:%S - %d/%m/%Y"}
			</td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				<strong>Maaltijd: </strong>
			</td>
			<td>
				{$order.meal->getName()}
			</td>
		</tr>
		{foreach from=$order.optionsets item=optionset}
			<tr>
				<td>
					<strong>{$optionset.name}: </strong>
				</td>
				<td>
					{if count($optionset.options.wel) > 0}
						{if $optionset.type == 3}
							Ja
						{else}
							{foreach from=$optionset.options.wel item=welopt name="welopts"}
								{$welopt}{if !$smarty.foreach.welopts.last}, {/if}
							{/foreach}
						{/if}
					{else}
						Neen
					{/if}
				</td>
			</tr>
		{/foreach}
		<tr>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				<strong>Prijs: </strong>
			</td>
			<td>
				€{$order.price|string_format:"%.2f"}
			</td>
		</tr>
	</table>
</p>