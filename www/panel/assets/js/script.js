$(document).ready(function(){
    $('#table').bootstrapTable({
        classes: "table table-striped table-hover"
    })

    $(".action_delete").click(function() {
        var id = $(this).attr("data-id")
        var type = $(this).attr("data-type");
        var reference = $(this).attr("data-reference");
        var amount = $(this).attr("data-amount");
        var url = (type == "transaction" ? '' : '/admin/') ;

        $.ajax({ 
            type: 'POST', 
            url: '/admin/actions/delete.php', 
            data: { id:  id, type: type, reference: reference, amount: amount}, 
            dataType: 'json',
            success: function (data) {                 
                $('<form action="'+url+'" method="POST">'+
                '<input type="hidden" name="status" value="' + data['status'] + '">'+
                '<input type="hidden" name="message" value="' + data['message'] + '">'+
                '</form>').appendTo($(document.body)).submit();
            },
        });        
    });

    $(".action_create").click(function() {
        var type = $(this).attr("data-type");
        $.ajax({ 
            type: 'POST', 
            url: '/admin/actions/create.php', 
            data: $("form#new").serialize() + "&type="+ type, 
            dataType: 'json',
            success: function (data) { 
                var url = (type == 'transaction' ? '' : '/'+type+'.php?id='+data['id']);
                $('<form action="'+url+'" method="POST">'+
                '<input type="hidden" name="status" value="' + data['status'] + '">'+
                '<input type="hidden" name="message" value="' + data['message'] + '">'+
                '</form>').appendTo($(document.body)).submit();
            },
        });        
    });

    $(".action_update").click(function() {
        var type = $(this).attr("data-type");
        $.ajax({ 
            type: 'POST', 
            url: '/admin/actions/update.php', 
            data: $("form#update").serialize() + "&type="+ type, 
            dataType: 'json',
            success: function (data) { 
                $('<form action="" method="POST">'+
                '<input type="hidden" name="status" value="' + data['status'] + '">'+
                '<input type="hidden" name="message" value="' + data['message'] + '">'+
                '</form>').appendTo($(document.body)).submit();
            },
        });        
    });
});

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
   
   $('.item-name').each(function() {
       $(this).hide();
   });

   $('.item-name[data-id]').each(function() {
       $("option[value=" + $(this).attr("data-id") + "]").attr('selected', 'selected');
   });

   $("option[value=" + $('.item-name[data-id]').attr("data-id") + "]").attr('selected', 'selected');
   
   $("#edit").hide();
   $("#cancel").show();
   $("#updatebtn").show();
});

$("#cancel").click(function() {
   location.reload();
});

function buttons () {
    return {
      btnAdd: {
        text: 'Add Transaction',
        event: function () {
            new bootstrap.Modal(document.getElementById('newTransaction')).show();
        },
        icon: 'bi bi-file-earmark-plus',
        attributes: {id: 'new'}
      }
    }
  }