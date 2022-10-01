$(function() {
    $('#table').bootstrapTable({
        classes: "table table-striped table-hover"
      })
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
   $("#updatebtn").show();
});

$("#cancel").click(function() {
   location.reload();
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();

    $(".action_delete").click(function() {
        var id = $(this).attr("data-id")
        var type = $(this).attr("data-type");
        var reference = $(this).attr("data-reference");
        var amount = $(this).attr("data-amount");
        $.ajax({ 
            type: 'POST', 
            url: '/admin/actions/delete.php', 
            data: { id:  id, type: type, reference: reference, amount: amount}, 
            dataType: 'json',
            success: function (data) {                 
                $("tr[data-id='"+id+"'").remove();
                if(type == "transaction") {
                    $('<form action="" method="POST">'+
                    '<input type="hidden" name="status" value="' + data['status'] + '">'+
                    '<input type="hidden" name="message" value="' + data['message'] + '">'+
                    '</form>').appendTo($(document.body)).submit();
                } else {
                    $('<form action="/admin/" method="POST">'+
                    '<input type="hidden" name="status" value="' + data['status'] + '">'+
                    '<input type="hidden" name="message" value="' + data['message'] + '">'+
                    '</form>').appendTo($(document.body)).submit();
                }
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
                if(type == "transaction") {
                    $('<form action="" method="POST">'+
                    '<input type="hidden" name="status" value="' + data['status'] + '">'+
                    '<input type="hidden" name="message" value="' + data['message'] + '">'+
                    '</form>').appendTo($(document.body)).submit();
                } else {
                    $('<form action="/'+type+'.php?id='+data['id']+'" method="POST">'+
                    '<input type="hidden" name="status" value="' + data['status'] + '">'+
                    '<input type="hidden" name="message" value="' + data['message'] + '">'+
                    '</form>').appendTo($(document.body)).submit();
                }
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
                if(type == "transaction") {
                    // 
                } else {
                    $('<form action="" method="POST">'+
                    '<input type="hidden" name="status" value="' + data['status'] + '">'+
                    '<input type="hidden" name="message" value="' + data['message'] + '">'+
                    '</form>').appendTo($(document.body)).submit();
                }
            },
        });        
    });
});

function buttons () {
    return {
      btnAdd: {
        text: 'Add Transaction',
        event: function () {
            var myModal = new bootstrap.Modal(document.getElementById('newTransaction'))
            myModal.show();
        },
        icon: 'bi bi-file-earmark-plus',
        attributes: {
          title: 'Add a a new transaction to this debt',
          id: 'new',
        }
      }
    }
  }