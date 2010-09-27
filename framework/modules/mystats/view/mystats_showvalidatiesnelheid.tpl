<h1>Statistieken validatiesnelheid: sinds {$startthis|date_format:"%d/%m/%Y"}</h1>
<div class="headerline">&nbsp;</div>
<p>
<table class="grid">
	<tr class="gridhead">
		<th>
			Dokter
		</th>
		<th>
			Gemiddelde
		</th>
		<th>
			Genorm. gem.
		</th>
		<th>
			# geval.
		</th>
		<th>
			# niet geval.
		</th>
		<th>
			% geval.
		</th>
		<th>
			Min.
		</th>
		<th>
			Max.
		</th>
	</tr>
{foreach from=$avg item=row name=avg}
{cycle values="gridrow_A,gridrow_B" assign=rowcycle}

	<tr class="gridrow {$rowcycle}">
		<td style="padding-left: 5px;">
			{$row.validerendeArtsNaam}
		</td>
		<td>
			{if $row.avg != ''}
				{math equation="x / (60 * 60)" x=$row.avg format="%.2f" assign=avg}
				{$avg|number_format:2:',':''}
			{else}

			{/if}
		 </td>
		 <td>
			{if $row.normavg != ''}
				{math equation="x / (60 * 60)" x=$row.normavg format="%.2f" assign=normavg}
				{$normavg|number_format:2:',':''}
			{else}

			{/if}
		 </td>
		 <td>
		 	{if $row.total != ''}
				{$row.total}
			{else}
				0
			{/if}
		 </td>
		 <td>
		 	{if $row.notvalidated != ''}
				{$row.notvalidated}
			{else}
				0
			{/if}
		 </td>
		 <td>
		 	{if $row.total == '' && $row.notvalidated == ''}
		 		100
		 	{elseif $row.total == ''}
		 		0
		 	{elseif $row.notvalidated == ''}
		 		100
		 	{else}
		 		{math equation="(x/(x+y))*100" x=$row.total y=$row.notvalidated format="%d"}
		 	{/if}
		 	%
		 </td>
		 <td>
		 	{if $row.min != ''}
				{math equation="x / (60 * 60)" x=$row.min format="%.2f" assign=min}
				{$min|number_format:2:',':''}
			{else}

			{/if}
		 </td>
		 <td>
		 	{if $row.max != ''}
				{math equation="x / (60 * 60)" x=$row.max format="%.2f" assign=max}
				{$max|number_format:2:',':''}
			{else}

			{/if}
		 </td>
	</tr>
{/foreach}
	<tr class="gridfoot">
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
		<td>

		</td>
	</tr>
</table>
</p>