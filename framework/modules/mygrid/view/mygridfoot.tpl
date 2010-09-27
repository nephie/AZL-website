<tr class="gridfoot">
	<td colspan="999" style="padding: 0px;">
		<table class="gridfoottable">
			<tr>
				<td id="prevpage">
					{if $grid->getTotalpages() > 1 && $grid->getPage() != 1}
						<a href="#" onClick="{ajaxrequest request=$grid->getGotofirstpagerequest()}" >First</a> &nbsp;|&nbsp; <a href="#" onClick="{ajaxrequest request=$grid->getGotopreviouspagerequest()}" >Previous</a>
					{/if}&nbsp;
				</td>
				<td id="jumppage">
					{if $grid->getTotalpages() > 1}
						Page <strong>{$grid->getPage()}</strong> of <strong>{$grid->getTotalpages()}</strong> &nbsp;&nbsp;&nbsp;
						{include file="inlineform.tpl" form=$grid->getGotopageform()}
					{/if}
				</td>
				<td id="search">
					{include file="inlineform.tpl" form=$grid->getSearchform()}{if $grid->getConditions() != $grid->getDefaultconditions()} <a href="#" onClick="{ajaxrequest request=$grid->getClearsearchrequest()}">Clear search</a>{/if}
				</td>
				<td id="nextpage">
					{if $grid->getTotalpages() > 1 && $grid->getPage() != $grid->getTotalpages()}
						<a href="#" onClick="{ajaxrequest request=$grid->getGotonextpagerequest()}" >Next</a> &nbsp;|&nbsp; <a href="#" onClick="{ajaxrequest request=$grid->getGotolastpagerequest()}" >Last</a>
					{/if}&nbsp;
				</td>
			</tr>
		</table>
	</td>
</tr>