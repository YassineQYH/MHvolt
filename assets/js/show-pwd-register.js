$(document).ready(function(){
    var showBtn = document.createElement('span');
    showBtn.innerText = 'Afficher';
    showBtn.classList.add('show-password');

    var showBtn2 = document.createElement('span');
    showBtn2.innerText = 'Afficher';
    showBtn2.classList.add('show-password');
    
    var inputPassword = document.querySelector('#register_password_first');
    var inputPassword2 = document.querySelector('#register_password_second');
    inputPassword.after(showBtn);
    inputPassword2.after(showBtn2);

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
