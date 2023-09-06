 $(document).ready(function() {

        rsquare = 0;
        rparallelogram = 0;
        
        $('.block').draggable({
            containment:'window',
            stack: '.block',
		    snap: false,
		    snapMode: 'outer',
		    snapTolerance: 13,
        });

    	$('.block').on('mousedown', function () {
            if (!$(this).hasClass('submed') )  {
                var status = $(this).attr('id');
                var elemx = document.getElementById(status).offsetTop;
                $(this).css('position','relative');
            }
	    });
 
        $('.block').on('mouseup', function () {
            if (!$(this).hasClass('submed') )  {
                var statusup = $(this).attr('id');
                var elemxup = document.getElementById(statusup).offsetLeft;
                var elemyup = document.getElementById(statusup).offsetTop;

                valpos_s(statusup, elemxup, elemyup,$(this));
                
                $(this).css('position','absolute');
                angulo = getRotationDegrees($(this));

                // console.log(statusup+" "+elemxup+" "+elemyup+" "+angulo);
            }
        });


        function valpos_s(s,y,z,lm)
        {
            rposl = 0;
            rpost = 0;
            var variant=216
            var aposl = parseInt(y)-variant,apost = parseInt(z),tolerance=15;
            
                //console.log(shapeTurtle[s].rx,shapeTurtle[s].ry); 
                rposl = parseInt(shapeTurtle[s].rx);
                rpost = parseInt(shapeTurtle[s].ry);
                
                torposx = rposl - aposl;
                torposy = rpost - apost;
                if (torposy<0) {
                    torposy =4;
                    //rpost=shapeTurtle[s].alt;
                }
                
                var rota=getRotationDegrees($(lm));
                //console.log(rota);
                if (torposx > -tolerance && torposx < tolerance && torposy > -tolerance && torposy < tolerance
                    &&!$(lm).hasClass('submed')&&rota==shapeTurtle[s].rot ) { 
            // console.log(rposl+" - "+aposl+' = '+torposx+"__"+ rpost+" - "+apost+' = '+torposy);
                    $(lm).css({top:rpost,left:rposl+variant});
                    $(lm).draggable( "destroy" );
                    //$(lm).off( "dblclick");
                    $(lm).off( "click");
                    $(lm).off( "mousedown");
                    $(lm).off( "mouseup");
                    $(lm).addClass('submed');
                }                        
        }

            $('#tangram').css({'width':''});
    // Make blocks rotate 90 deg on each click
    var angle = 0,options;
        $('.block').mousedown(function(event) {
            if (!$(this).hasClass('submed')) {
                $(this).bind('contextmenu', function(e){return false;}); 
                switch (event.which) {
                    case 3:{

                            angle=getRotationDegrees($(this));

                            // alert(angle);

                            angle+=45;

                            if ($(this).attr('id')=='parallelogram') {

                                if (angle>=180) {
                                    angle=0;
                                }

                                options={
                                    '-webkit-transform': 'rotate(' + angle + 'deg) skew(45deg)',
                                    '-moz-transform': 'rotate(' + angle + 'deg) skew(45deg)',
                                    '-o-transform': 'rotate(' + angle + 'deg) skew(45deg)',
                                    '-ms-transform': 'rotate(' + angle + 'deg) skew(45deg)'
                           
                                };                    
                            }else if ($(this).attr('id')=='square') {

                                if (angle>45) {
                                    angle=0;
                                }

                                options={
                                    '-webkit-transform': 'rotate(' + angle + 'deg)',
                                    '-moz-transform': 'rotate(' + angle + 'deg)',
                                    '-o-transform': 'rotate(' + angle + 'deg)',
                                    '-ms-transform': 'rotate(' + angle + 'deg)'
                                };
                            } else {

                                if (angle>=360) {
                                    angle=0;
                                }

                                options={
                                    '-webkit-transform': 'rotate(' + angle + 'deg)',
                                    '-moz-transform': 'rotate(' + angle + 'deg)',
                                    '-o-transform': 'rotate(' + angle + 'deg)',
                                    '-ms-transform': 'rotate(' + angle + 'deg)'
                                };
                            }

                            $(this).css(options);

                    } break;
                }
            }
            
        });
});

function getRotationDegrees(obj) {
    var matrix = obj.css("-webkit-transform") ||
    obj.css("-moz-transform")    ||
    obj.css("-ms-transform")     ||
    obj.css("-o-transform")      ||
    obj.css("transform");
    if(matrix !== 'none') {
        var values = matrix.split('(')[1].split(')')[0].split(',');
        var a = values[0];
        var b = values[1];
        var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
    } else { var angle = 0; }
    return (angle < 0) ? angle + 360 : angle;
}

/*
-webkit-transform: rotate(45deg);
-moz-transform: rotate(45deg);
-o-transform: rotate(45deg);
-ms-transform: rotate(45deg);
*/