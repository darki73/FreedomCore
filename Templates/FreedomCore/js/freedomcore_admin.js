function RenewSecurityList()
{
    $.ajax({
        url: '/admin/renewsecuritylist',
        type: 'GET',
        data: '',
        success: function(response) {
            if(response == 1)
                location.reload();
        }
    });
}

var Administrator = {
    get_item_data: function () {
        var ItemID = $('#item_id').val();
        $.ajax({
            type: 'POST',
            url: '/admin/shop/add-item/get-data',
            data: { itemid: ItemID },
            cache: false,
            success: function(data){
                if(data != 'false'){
                    var ItemIconSpan = $('#item_icon_span'),
                        ItemName = $('#item_name'),
                        ItemNameInput = $('#item_name_in'),
                        ItemType = $('#item_type'),
                        ItemPrice = $('#item_price'),
                        ItemClass = $('#item_class'),
                        ItemSubClass = $('#item_subclass'),
                        AddItem = $('#additem-row'),
                        Json = $.parseJSON(data);
                    ItemIconSpan.show();
                    ItemIconSpan.attr('style', 'background-image: url("/Templates/FreedomCore/images/icons/large/'+Json.icon+'.jpg");');
                    ItemName.addClass('color-q'+Json.Quality);
                    ItemClass.val(Json.class.class);
                    ItemSubClass.val(Json.subclass.subclass);
                    ItemName.html(Json.name);
                    ItemNameInput.val(Json.name);
                    ItemType.html(Json.subclass.translation);
                    AddItem.show();
                    ItemPrice.show();
                } else {
                    $('#item_name').html('Not found!');
                }
                //console.log(Json);
            }
        });
        return false;
    },

    tmp_article_image: function(form) {
        $.ajax({
            url: '/admin/articles/tmp_image',
            type: 'POST',
            data:  new FormData(form),
            contentType: false,
            cache: false,
            processData:false,
            success: function(result) {
                $('#imagePreviewDiv').show();
                $('#TMPImageForm').hide();
                var ImageData = JSON.parse(result);
                $('#imagePreviewDiv').html("<img src='"+ImageData.url+"' alt='"+ImageData.name+"' width='310'>");
                $('#imageName').val(ImageData.name);
            }
        });
        return false;
    }
};