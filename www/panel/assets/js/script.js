$(function() {
    $('#table').bootstrapTable()
  })

$("#edit").click(function() {
   $("#placeholder").show();
   
   $('.editablenum').each(function() {
       var itemData = $.trim($(this).text()).replace(/,/g, '');
       itemData = itemData.replace(",", "");
       var itemClass = $(this).attr('class');
       var itemName = $(this).attr('name');
       var input = $('<input></input>');
       input.attr('step', '0.01').addClass(itemClass).val(itemData).attr('name', itemName).attr('type', 'number').addClass('form-control');
       $(this).replaceWith(input);
   });

   $('.editable').each(function() {
       var itemData = $.trim($(this).text());
       var itemClass = $(this).attr('class');
       var itemName = $(this).attr('name');
       var input = $('<input></input>');
       input.addClass(itemClass).val(itemData).attr('name', itemName).attr('type', 'text').addClass('form-control');
       $(this).replaceWith(input);
   });

   $('.editabletextarea').each(function() {
       var itemData = $.trim($(this).text());
       var itemClass = $(this).attr('class');
       var itemName = $(this).attr('name');
       var input = $('<textarea></textarea>');
       input.addClass(itemClass).val(itemData).attr('name', itemName).addClass('form-control').css('height', '200px').css('width', '100%');
       $(this).replaceWith(input);
   });
   
   $('.debt-name').each(function() {
       $(this).hide();
   });

   $('.debt-name[data-id]').each(function() {
       $("option[value=" + $(this).attr("data-id") + "]").attr('selected', 'selected');
   });

   $("option[value=" + $('.debt-name[data-id]').attr("data-id") + "]").attr('selected', 'selected');
   $("#cancel").show();
   $("#edit").hide();
   $("#submit").show();
});

$("#cancel").click(function() {
   location.reload();
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

$(".delete").click(function() {
    var id = $(this).attr("data-id")
    var type = $(this).attr("data-type");
    $.ajax({ 
        type: 'POST', 
        url: '/admin/delete.php', 
        data: { id:  id, type: type}, 
        dataType: 'json',
        success: function (data) { 
            $("tr[data-id='"+id+"'").remove();
            if(type == "transaction") {
                var status = data['status'] == 'error' ? 'danger' : data['status']; 
                $( "#result" ).empty().append('<div id="status" class="alert alert-' + status +'" role="alert">' + data['message'] + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            } else {
                $('<form action="/admin/" method="POST">'+
                '<input type="hidden" name="status" value="' + data['status'] + '">'+
                '<input type="hidden" name="message" value="' + data['message'] + '">'+
                '</form>').appendTo($(document.body)).submit();
            }
        },
    });        
});