$(document).ready(function(){
        $('.show-password').click(function() {
            if($(this).prev('input').prop('type') == 'password') {
                //Si c'est un input type password
                $(this).prev('input').prop('type','text');
                $(this).text('cacher');
            } else {
                //Sinon
                $(this).prev('input').prop('type','password');
                $(this).text('afficher');
            }
        });

    });