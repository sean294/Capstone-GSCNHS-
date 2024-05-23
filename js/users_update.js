
$(document).ready(function () {
    $('.personal').on('click', function (e) {
        e.preventDefault();
        var username = $(this).data('user-data');
        console.log(username);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'user_update': true,
                'user_name': username,
            },
            success:function(response){
                console.log(response);
                $('.display').html(response);
                $('.modal-update').css({
                    display:'flex'
                })
            },
            error: function (error) {
                console.error('Error updating data:', error);
            }
        });
    });

    $('.close').on('click', function () {
        $('.modal-update').css({
            display: 'none'
        });
    });
});


$(document).ready(function () {
    $('.account').on('click', function (e) {
        e.preventDefault();
        var username = $(this).data('account-user');
        console.log(username);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'user_account': true,
                'user_name': username,
            },
            success:function(response){
                console.log(response);
                $('.display').html(response);
                $('.modal-update').css({
                    display:'flex'
                })
            },
            error: function (error) {
                console.error('Error updating data:', error);
            }
        });
    });

    $('.close').on('click', function () {
        $('.modal-update').css({
            display: 'none'
        });
    });
});

// for profile
$(document).ready(function () {
    $('.user_profile').on('click', function (e) {
        e.preventDefault();
        var username = $(this).data('profile-user');
        console.log(username);

        $.ajax ({
            method:"post",
            url:"../control/controller.php",
            data:{
                'users_profile': true,
                'user_fullname': username,
            },
            success:function(response){
                console.log(response);
                $('.display').html(response);
                $('.modal-update').css({
                    display:'flex'
                })
            },
            error: function (error) {
                console.error('Error updating data:', error);
            }
        });
    });

    $('.close').on('click', function () {
        $('.modal-update').css({
            display: 'none'
        });
    });
});