<tbody>
{if count($grid->getrow()) > 0}
	{foreach from=$grid->getRow() item=row name=rows}
		{cycle values="gridrow_A,gridrow_B" assign=rowcycle}
		<tr class="gridrow {$rowcycle}" id="gridrow_{$grid->getId()}_{$row->getId()}">
			{if $grid->isEditAllowed() || $grid->isAddAllowed() || $grid->isDeleteAllowed()}
				<td  class="gridedit"  style="text-align: left;">
					{if $grid->isEditAllowed()}
						{assign var=editrequest value=$grid->getRequest('-edit-',$row)}
						{if $editrequest instanceof ajaxrequest}
							<a href="#" onClick="{ajaxrequest request=$editrequest}">
						<img src="files/images/edit_{$rowcycle}.png" title="Aanpassen"/>
							</a>
						{/if}
					{/if}
					{if $grid->isDeleteAllowed()}
					{assign var=deleterequest value=$grid->getRequest('-delete-',$row)}
						{if $deleterequest instanceof ajaxrequest}
							<a href="#" onClick="{ajaxrequest request=$deleterequest}">

						<img src="files/images/delete_{$rowcycle}.png" title="Verwijderen"/>

							</a>
						{/if}
					{/if}
				</td>
			{/if}
			<td>
				{assign var="page" value=$grid->getPage()}
				{assign var="pagesize" value=$grid->getPagesize()}
				{assign var="iteration" value=$smarty.foreach.rows.iteration}
				{assign var="nr" value="`$iteration-$pagesize+$page*$pagesize`"}

				{$nr}
			</td>
			{foreach from=$grid->getColumn() item=column}

					{if is_array($column)}
						{if isset($column.width)}
				<td width="{$column.width}">
						{else}
				<td>
						{/if}
						{if $row->_get($column.column) != ''}
							{capture assign="colwithmod"}
								{ldelim}"{$row->_get($column.column)}"|{$column.modifier}{rdelim}
							{/capture}
							{eval var=$colwithmod}
						{/if}
					{else}
				<td>
						{assign var=colrequest value=$grid->getRequest($column,$row)}
						{if $colrequest instanceof ajaxrequest}
						<a href="#" onClick="{ajaxrequest request=$colrequest}">
						{/if}

						{$row->_get($column)}

						{if $colrequest instanceof ajaxrequest}
						</a>
						{/if}
					{/if}
				</td>
			{/foreach}
			{if $grid->getOrderfield() != ''}
				<td>
					{assign var="setobjectorderrequest" value=$grid->getSetobjectorderrequest($row->getId())}
					<a href="#" onClick="{ajaxrequest request=$setobjectorderrequest}">
						{$row->_get($grid->getOrderfield())}
					</a>
				</td>
			{/if}

		</tr>
	{/foreach}
{else}
<tr>
	<td colspan="999">
		Deze lijst bevat geen items.
	</td>
</tr>
{/if}
</tbody>