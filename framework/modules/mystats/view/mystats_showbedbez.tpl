<h1>Statistieken bedbezetting</h1>
<div class="headerline">&nbsp;</div>
<a href="#" onClick="{ajaxrequest request=$printrequest}">Printversie</a>
<p>
<table class="grid">
	<tr class="gridhead">
		<th>
			Dienst
		</th>
		<th>
			Bezet
		</th>
		<th>
			Totaal
		</th>
		<th>
			Bezettingsgraad
		</th>
		<th>
			Grafiek
		</th>
	</tr>
{foreach from=$diensten item=dienstarr name=diensten}
{cycle values="gridrow_A,gridrow_B" assign=rowcycle}
{math equation="(bezet / totaal) * 100 " bezet=$dienstarr.count totaal=$dienstarr.dienst->getAantalbedden() format="%.0f" assign="procent"}
{if $procent < 80}
 	{assign var="procentcolor1" value=""}
 	{assign var="procentcolor2" value=""}
 	{assign var="procentcolor3" value=""}
 {elseif $procent < 90}
 	{assign var="procentcolor1" value="border-top: 1px solid #9F6000;border-bottom: 1px solid #9F6000; background-color: #DCCD91;"}
 	{assign var="procentcolor2" value="border-left: 1px solid #9F6000; background-color: #DCCD91;"}
 	{assign var="procentcolor3" value="border-right: 1px solid #9F6000; background-color: #DCCD91;"}
 {else}
	{assign var="procentcolor1" value="border-top: 1px solid #D8000C;border-bottom: 1px solid #D8000C; background-color: #DD8787;"}
 	{assign var="procentcolor2" value="border-left: 1px solid #D8000C; background-color: #DD8787;"}
 	{assign var="procentcolor3" value="border-right: 1px solid #D8000C; background-color: #DD8787;"}
 {/if}
	<tr class="gridrow {$rowcycle}">
		<td style="padding-left: 5px; {$procentcolor1} {$procentcolor2}">
			{$dienstarr.dienst->getName()}
		</td>
		<td style="{$procentcolor1}">
		 	{$dienstarr.count}
		 </td>
		 <td style="{$procentcolor1}">
		 	{$dienstarr.dienst->getAantalbedden()}
		 </td>
		 <td style="padding: 3px; {$procentcolor1}">



		 	<span {$procentcolor}>
		 		{$procent}%
		 	</span>
		 </td>
		 <td style="{$procentcolor1} {$procentcolor3}">
		 	<a href="#" onClick="{ajaxrequest request=$dienstarr.graphtoday}" >Vandaag</a> |
		 	<a href="#" onClick="{ajaxrequest request=$dienstarr.graphyesterday}" >Gisteren</a> |
		 	<a href="#" onClick="{ajaxrequest request=$dienstarr.graphtm}" >Deze maand</a> |
		 	<a href="#" onClick="{ajaxrequest request=$dienstarr.graphlm}" >vorige maand</a> |
		 	<a href="#" onClick="{ajaxrequest request=$dienstarr.graphty}" >Dit jaar</a> |
		 	<a href="#" onClick="{ajaxrequest request=$dienstarr.graphly}" >Vorig jaar</a>
		 </td>
	</tr>
{/foreach}
	<tr class="gridfoot">
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
		<td style="padding-left: 0px;">
			<a href="#" onClick="{ajaxrequest request=$totalgraph.graphtoday}" >Vandaag</a> |
		 	<a href="#" onClick="{ajaxrequest request=$totalgraph.graphyesterday}" >Gisteren</a> |
		 	<a href="#" onClick="{ajaxrequest request=$totalgraph.graphtm}" >Deze maand</a> |
		 	<a href="#" onClick="{ajaxrequest request=$totalgraph.graphlm}" >vorige maand</a> |
		 	<a href="#" onClick="{ajaxrequest request=$totalgraph.graphty}" >Dit jaar</a> |
		 	<a href="#" onClick="{ajaxrequest request=$totalgraph.graphly}" >Vorig jaar</a>
		</td>
	</tr>
</table>
</p>
<p>
	<div id="bedbezgraph"></div>
</p>