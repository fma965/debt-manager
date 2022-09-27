// Messy as fuck, needs to be rescripted

$( "#edit" ).click(function() {
		$( "#placeholder" ).show();
		$( ".datesuffix" ).hide();
		$( "#currencytext" ).hide();
		$( "#currencyselect" ).show();
        $('.editablenum').each(function() {
           var itemData = $.trim($(this).text()).replace(/,/g,'');
		   itemData = itemData.replace(",","");
           var itemClass = $(this).attr('class');
		   var itemName = $(this).attr('name');
           var input = $('<input></input>');
           input.attr('step','0.01').addClass(itemClass).val(itemData).attr('name',itemName).attr('type','number').attr('class','form-control');
           $(this).replaceWith(input);
        });
		 $('.editable').each(function() {
           var itemData = $.trim($(this).text());
           var itemClass = $(this).attr('class');
		   var itemName = $(this).attr('name');
           var input = $('<input></input>');
           input.addClass(itemClass).val(itemData).attr('name',itemName).attr('type','text').attr('class','form-control');
           $(this).replaceWith(input);
        });
		$('.editabletextarea').each(function() {
           var itemData = $.trim($(this).text());
           var itemClass = $(this).attr('class');
		   var itemName = $(this).attr('name');
           var input = $('<textarea></textarea>');
           input.addClass(itemClass).val(itemData).attr('name',itemName).attr('class','form-control').css('height','90%').css('width','100%');
           $(this).replaceWith(input);
        });
		$('.editablecheckbox').each(function() {
           var itemData = $.trim($(this).text());
           var itemClass = $(this).attr('class');
		   var itemName = $(this).attr('name');
           var input = $('<input></input>');
           input.addClass(itemClass).val(itemData).attr('name',itemName).attr('type','checkbox').attr('class','form-control').attr('checked','true');
           $(this).replaceWith(input);
        });
		$('.debt-name').each(function() {
           $(this).hide();
      });
      $('.debt-name[data-id]').each(function() {
         $("option[value="+$(this).attr("data-id")+"]").attr('selected', 'selected');
      });
		$("option[value="+$('.debt-name[data-id]').attr("data-id")+"]").attr('selected', 'selected');
		$( "#cancel" ).show();
		$( "#edit" ).hide();
		$( "#submit" ).show();
		$( "#status" ).hide();
}); 

$( "#cancel" ).click(function() {
		location.reload();
}); 

$( "#toggleview" ).click(function() {	
		$( ".active-0" ).toggle();
		
}); 