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