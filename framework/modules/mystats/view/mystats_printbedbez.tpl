<table class="grid printtable">
	<tr class="printtablehead">
		<th width="200px">
			Dienst
		</th>
		<th >
			Bezet
		</th>
		<th >
			Totaal
		</th>
		<th >
			%
		</th>
		<th width="200px">
			Vrij
		</th>
		<th width="200px">
			Ontslagen
		</th>
	</tr>
{foreach from=$diensten item=dienstarr name=diensten}
	{cycle values="gridrow_A,gridrow_B" assign=rowcycle}
	{math equation="(bezet / totaal) * 100 " bezet=$dienstarr.count totaal=$dienstarr.dienst->getAantalbedden() format="%.0f" assign="procent"}

		<tr class="gridrow {$rowcycle}">
			{if $dienstarr.dienst->getDienstnr() != '007' && $dienstarr.dienst->getDienstnr() != '008' && $dienstarr.dienst->getDienstnr() != '012' && $dienstarr.dienst->getDienstnr() != '009' && $dienstarr.dienst->getDienstnr() != '099' && $dienstarr.dienst->getDienstnr() != '999'}
				<td style="padding:48px 0px ;">
			{else}
				<td>
			{/if}
				{$dienstarr.dienst->getName()}
			</td>
			<td >
			 	{$dienstarr.count}
			 </td>
			 <td>
			 	{$dienstarr.dienst->getAantalbedden()}
			 </td>
			 <td>



			 	<span {$procentcolor}>
			 		{$procent}%
			 	</span>
			 </td>
			 <td>
			 	&nbsp;
			 </td>
			 <td>
			 	&nbsp;
			 </td>
		</tr>
{/foreach}
	<tr class="printtablefoot">
		<td>
			Totaal
		</td>
		<td>
			{$total}
		</td>
		<td>
			{$totalmax}
		</td>
		<td>
			{math equation="(bezet / totaal) * 100 " bezet=$total totaal=$totalmax format="%.0f" assign="procent"}


		 		{$procent}%
		</td>
		 <td>
		 	&nbsp;
		 </td>
		 <td>
		 	&nbsp;
		 </td>
	</tr>
</table>