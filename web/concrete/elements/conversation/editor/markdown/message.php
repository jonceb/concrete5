<?php defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
print $form->textarea($editor->getConversationEditorInputName(),array('class'=>'unbound markdown_conversation_editor_'.$editor->cnvObject->cnvID));
?>
<script type="text/javascript">
	var me = $('textarea.unbound.markdown_conversation_editor_<?=$editor->cnvObject->cnvID?>').first().removeClass('unbound');
	(function($,window,me){
		var obj = window.obj;
		ccm_event.bind('conversationSubmitForm',function(){
			me.val('');
		});
		me.keydown(function(e) {
			ccm_event.fire('conversationsTextareaKeydown', e, obj.$element.get(0));
			if (e.keyCode == 40) {
				ccm_event.fire('conversationsTextareaKeydownDown',e,obj.$element.get(0));
				if (obj.dropdown.active) return false;
			} else if (e.keyCode == 38) {
				ccm_event.fire('conversationsTextareaKeydownUp',e,obj.$element.get(0));
				if (obj.dropdown.active) return false;
			} else if (e.keyCode == 13) {
				ccm_event.fire('conversationsTextareaKeydownEnter',e,obj.$element.get(0));
				if (obj.dropdown.active) return false;
			}
		}).keyup(function(e) {
			if (e.keyCode == 40) return;
			var caret = obj.tool.getCaretPosition(me.get(0)),
				string = me.val().substr(0,caret).split(' ').pop(),
				matches = [];
			// If this is a mention string
			if (obj.tool.testMentionString(string)) {
				matches = obj.tool.getMentionMatches(string.substr(1),obj.options.activeUsers);
			}
			matches.map(function(u,p){
				matches[p] = new obj.tool.MentionUser(u); // Convert strings to objects
				matches[p].caretPos = caret;
				matches[p].string = string;
				matches[p].textarea = me;
			});
			// Fire mention event regardless of whether there is data or not.
			ccm_event.fire('conversationsMention',{
					obj: obj,
					items: matches,
					bindTo: me.get(0),
					coordinates: {
						x: me.offset().left,
						y: me.offset().top + me.height() + 10
					}
				},
				obj.$element.get(0)
			);
		});
		// Bind to item selection event
		ccm_event.bind('conversationsMentionSelect',function(e){
				var selected = e.eventData.item;
				if (!selected.textarea.is(me)) return;
				var start = me.val();
				var fin = start.substr(0,selected.caretPos - selected.string.length + 1) + selected.getName() + start.substr(selected.caretPos);
				me.val(fin);
				obj.tool.setCaretPosition(me.get(0),selected.caretPos - selected.string.length + 1 + selected.getName().length);
			},
			me.get(0)
		);
	})(jQuery,window,me)
</script>