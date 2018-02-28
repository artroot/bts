var rightContainer = $('.right-container').on('touchstart', function (e) {
    var start = e.originalEvent.touches[0].pageX;
    var leftNav = $('.left-nav').removeClass('hidden-xs');
    rightContainer.css('margin-left', '0');
    $(this).on('touchmove', function (e) {
        var move = e.originalEvent.touches[0].pageX;
        if(move > start){
            if((move-start)*2 >= 100){
                leftNav.css('left', '-15px');
                rightContainer.css('position', 'relative').css('margin-left', '200px');
            }else{
                if(leftNav.position().left < -150) {
                    leftNav.css('left', '-200px');
                    rightContainer.css('margin-left', '0');
                }
                else {
                    leftNav.css('left', '-15px');
                    rightContainer.css('margin-left', '200px');
                }
            }
        }else{
            leftNav.css('left', '-200px');
            rightContainer.css('margin-left', '0');
        }
    });
    $(this).on('touchend', function (e) {
        if (leftNav.position().left < (-100)) {
            leftNav.css('left', '-200px');
            rightContainer.css('margin-left', '0');
        }
        else {
            leftNav.css('left', '-15px');
            rightContainer.css('margin-left', '200px');
        }
    });
});