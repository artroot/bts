$('body').on('touchstart', function (e) {
    start = e.originalEvent.touches[0].pageX;

    var leftNav = $('.left-nav').removeClass('hidden-xs');
    var rightContainer = $('.right-container');
    var touchLeft = true;
    var touchRight = true;
    $(this).on('touchmove', function (e) {
        var move = e.originalEvent.touches[0].pageX;
        var moveleft = move-start;
        var moveright = start-move;

        if(move > start){
            if (rightContainer.offset().left >= 0 && rightContainer.offset().left < 200 && moveleft <= 200){

                leftNav.css('left', (-200)+moveleft + 'px');
                rightContainer.css('position', 'relative').css('margin-left', moveleft + 'px');

                touchLeft = true;
            }
        }else{
            if (rightContainer.offset().left <= 200 && rightContainer.offset().left > 0 && moveright <= 200){

                leftNav.css('left', (-15)-moveright+'px');
                rightContainer.css('position', 'relative').css('margin-left', (200)-moveright+'px');

                touchRight = true;
            }
        }
    }).on('touchend', function (e) {
        if (rightContainer.offset().left < 180) {
            leftNav.css('left', '-200px');
            rightContainer.css('margin-left', '0');
        }
        else if (rightContainer.offset().left > 20){
            leftNav.css('left', '-15px');
            rightContainer.css('margin-left', '200px');
        }
    });
});