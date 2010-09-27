{if $saved && $mailed}
	Uw melding is goed bewaard en doorgegeven.
{elseif $saved}
	Uw melding is goed bewaard maar is niet doorgegeven! Contacteer de informaticadienst!
{elseif $mailed}
	Uw melding is goed doorgegeven maar is niet bewaard! U doet er best aan om de informaticadienst te contacteren!
{else}
	Uw melding is niet bewaard en niet doorgegeven! Contacteer de informaticadienst!
{/if}
