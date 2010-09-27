<p>
	Bent u zeker dat u dit wilt verwijderen?
</p>
<p>
	<table>
		<tr>
			<td>
				<strong>Aanvrager: </strong>
			</td>
			<td>
				{$acl->getRequester()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Item: </strong>
			</td>
			<td>
				{$acl->getObject()}
			</td>
		</tr>
		<tr>
			<td>
				<strong>Recht: </strong>
			</td>
			<td>
				{$acl->getRightdesc()}
			</td>
		</tr>
	</table>
</p>
<br />
{if count($dependant) > 0}
<p>
	Dit recht verwijderen zal voor deze aanvrager ook de volgende rechten verwijderen:
	<ul>
		{foreach from=$dependant item=depacl}
			<li>{$depacl->getRightdesc()}</li>
		{/foreach}
	</ul>
</p>
<br />
{/if}