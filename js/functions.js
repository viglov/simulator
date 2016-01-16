function update(data) {
    var ajax = jQuery.ajax({
        type: "post",
        url: "./inc/ajax.php",
        dataType: "json",
        data: data,
        success: function(result) {
            console.log(result);
            if (result.error) {
                jQuery.each(result.error, function(index, val) {
                    alertify.error('INTERLOCK - ' + val);
                });
            } else {
                updateEl(result);
            }
            jQuery('#load').fadeOut('fast');
        },
        error: function(result, textStatus, errorThrown) {
//            console.log('Неуспешно инициализиране');
            console.log(result);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
    //** Periodichno obnovqva informaciqta **
//    var timer = setTimeout('update()', 1000);
}

function updateEl(result) {
    jQuery.each(result['obj'], function(index, val) {
        var elem = document.getElementById(index);

//        console.log(index);
//        console.log(val.sust);
        if (val.sust === 1) {
            elem.classList.remove("off");
            elem.classList.add('on');
            if (val.source) {
                elem.classList.add('source');
            }
        } else if (val.sust === 0) {
            elem.classList.remove("on");
            elem.classList.remove("source");
            elem.classList.add('off');
        } else {
            elem.classList.remove("off");
            elem.classList.remove("on");
        }
        if (val.sust === val.poz) {
            elem.classList.remove('conflict');
        } else {
            elem.classList.add('conflict');
        }
    });
}
(function($) {
    $(document).ready(function() {
        var elem;

        update();

        $('.q').click(function() {
            elem = this;

            alertify.defaults.transition = "zoom";
            alertify.alert('<div class="custom-buttons"><h2>' + $(elem).attr("id") + '</h2><p><a class="ajs-button ajs-on" href="javascript:showAlert(true);">ON</a><a class="ajs-button ajs-off" href="javascript:showAlert(false);">OFF</a></p></div>').set({
                'frameless': true,
                'movable': false,
                'overflow': true,
                'padding': false,
                'autoReset': true
            });
        });

        $('.reset').click(function() {
            update({val: 'reset'});
        });

        $('.izvod').click(function() {
            elem = this.parentElement;
            update({
                name: $(elem).attr('id'),
                com: elem.classList.contains("on") ? 0 : 1
            });
        });

        window.showAlert = function(a) {
            var txt, com;
            if (a === true) {
                txt = "<h2>ON?</h2>";
                com = 1;
            } else if (a === false) {
                txt = "<h2>OFF?</h2>";
                com = 0;
            } else {
                return false;
            }

            alertify.confirm(txt).set({
                'title': $(elem).attr("id"),
                'labels': {
                    ok: 'OK',
                    cancel: 'CANSEL'
                },
                'onok': function(e) {
                    update({name: $(elem).attr("id"), com: com});
                },
                'defaultFocus': 'cancel',
                'oncancel': function(e) {
                }
            }).closeOthers();
        };
    });
})(jQuery);