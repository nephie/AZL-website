<tbody>
{if count($grid->getrow()) > 0}
	{foreach from=$grid->getRow() item=row name=rows}
		{cycle values="gridrow_A,gridrow_B" assign=rowcycle}
		<tr class="gridrow {$rowcycle}" id="gridrow_{$grid->getId()}_{$row->getId()}">
			{if $grid->isEditAllowed() || $grid->isAddAllowed() || $grid->isDeleteAllowed()}
				{assign var="editcol" value="true"}
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
			{else}
				{assign var="editcol" value="false"}
			{/if}


			{foreach from=$grid->getColumn() item=column name="colforeach"}
					{if $smarty.foreach.colforeach.first && $editcol == "false"}
						{assign var="padding" value='style= "padding-left: 3px;"'}
					{else}
						{assign var="padding" value=""}
					{/if}

					{if is_array($column)}
						{if isset($column.width)}
				<td {$padding} width="{$column.width}">
						{else}
				<td {$padding}>
						{/if}
						{if $row->_get($column.column) != ''}
							{capture assign="colwithmod"}
								{ldelim}"{$row->_get($column.column)}"|{$column.modifier}{rdelim}
							{/capture}
							{eval var=$colwithmod}
						{/if}
					{else}
				<td {$padding}>
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