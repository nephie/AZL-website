<?php /* Smarty version 2.6.18, created on 2010-04-28 12:37:41
         compiled from myticket_saveflash.tpl */ ?>
<?php if ($this->_tpl_vars['saved'] && $this->_tpl_vars['mailed']): ?>
	Uw melding is goed bewaard en doorgegeven.
<?php elseif ($this->_tpl_vars['saved']): ?>
	Uw melding is goed bewaard maar is niet doorgegeven! Contacteer de informaticadienst!
<?php elseif ($this->_tpl_vars['mailed']): ?>
	Uw melding is goed doorgegeven maar is niet bewaard! U doet er best aan om de informaticadienst te contacteren!
<?php else: ?>
	Uw melding is niet bewaard en niet doorgegeven! Contacteer de informaticadienst!
<?php endif; ?>