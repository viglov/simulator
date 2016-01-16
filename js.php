<?php ?>

<div id="load">
    <img src="images/loader.gif"/>
</div>
<script type="text/javascript">
    var obj = {}, mode = 0, ex, exsteps, position, msg, time = 5 * 60, timeIndex = true,
            rmMessage = exHelpindex = exhints = false, hintTimer, deb = 0, debin = 0;
    alertify.defaults.transition = "zoom";

<?php
if ( isset( $_GET['p'] ) && $_GET['p'] === 'ex' ) {
    echo 'ex = "ex";';
}
$m = 1;
if ( isset( $_GET['m'] ) ) {
    if ( $_GET['m'] == '2' ) {
        $m = 2;
    } elseif ( $_GET['m'] == '3' ) {
        $m = 3;
    } elseif ( $_GET['m'] == '4' ) {
        $m = 4;
    } elseif ( $_GET['m'] == 'r' ) {
        echo 'mode = 1;';
    }
}
?>

    function update(data) {
        loadObj();
        data.obj = obj;
        data.mode = mode;
        if (ex === 'ex') {
            data.ex = '<?php echo $m; ?>';
        }
        var ajax = jQuery.ajax({
            type: "post",
            url: "./inc/ajax.php",
            dataType: "json",
            data: data,
            success: function(result) {
                if (result.error) {
                    jQuery.each(result.error, function(index, val) {
                        document.getElementById(data.name).classList.add('conflict');
                        alertify.error('<?php _e( 'INTERLOCK' ); ?> - ' + val, 10);
                    });
                } else if (result.obj) {
                    updateEl(result['obj']);
                    if (exHelpindex) {
                        if (result.end) {
                            updateEl(result["end"], true);

                            if (result.steps) {
                                exsteps = result["steps"];
                            }
                        }
                        exHelpindex = false;
                    }
                    if (exhints && exsteps) {
                        steps(result);
                    }
                }
                if (result.success) {
                    alertify.dismissAll();
                    for (var i = 0; i < Object.keys(result.success).length; i++) {
                        alertify.success(result.success[i], 10);
                    }
                }
                jQuery('#load').fadeOut('fast');

                if (mode === 1) {
                    position = parseInt(result.position);
                    if (position > 0) {
                        document.getElementById("message").innerHTML = "You are #" + position + " no the list";
                    } else if (position == 0) {
                        if (rmMessage) {
                            alertify.alert("", "Now it is Your turn.<br/><br/>Enjoy!").set({onshow: null, onclose: function() {
                                }});
                            rmMessage = false;
                        }
                        if (timeIndex) {
                            document.getElementById("message").innerHTML = "Time Remaining - <span id=\"min\"></span>:<span id=\"sec\"></span>";
                            cdtd(time);
                            timeIndex = false;
                        }
                    }
                }
            },
            error: function(result, textStatus, errorThrown) {
                console.log(result);
                console.log(textStatus);
                console.log(errorThrown);
                alertify.error(result, 0);
                alertify.error(textStatus, 0);
                alertify.error(errorThrown, 0);
            }
        });

        //** Periodichno obnovqva informaciqta **
        if (mode === 1) {
            var timer = setTimeout('update({action: "stend"})', 2000);
        }
    }
    
    function steps(result) {

        if (exsteps[0]) {

            var blink = document.getElementById("blur");
            clearTimeout(hintTimer);
            blink.classList.remove("blink");

            if (result.obj[exsteps[0][0]]["status"] == exsteps[0][1]) {
                exsteps.splice(0, 1);
                steps(result);
            } else {

                var txt = 'Turn';
                if (exsteps[0][1]) {
                    txt = txt + " ON ";
                } else {
                    txt = txt + " OFF ";
                }
                txt = txt + "<span style=\"text-transform: uppercase;\">" + exsteps[0][0] + "</span>";
                alertify.notify(txt, 'custom', 0, function() {
                    clearTimeout(hintTimer);
                }).dismissOthers();

                hintTimer = setTimeout(function() {
                    var el = document.getElementById(exsteps[0][0]);
                    var clone = jQuery(el).clone();
                    clone[0].id = "blur-el";
                    jQuery("#blur").html(clone);

                    blink.classList.toggle("blink");
                    hintTimer = setTimeout(function() {
                        blink.classList.toggle("blink");
                        hintTimer = setTimeout(function() {
                            blink.classList.toggle("blink");
                            hintTimer = setTimeout(function() {
                                blink.classList.toggle("blink");
                                hintTimer = setTimeout(function() {
                                    blink.classList.toggle("blink");
                                }, 150);
                            }, 150);
                        }, 150);
                    }, 150);
                }, 10000);
            }
        }
    }

    function updateEl(result, exm) {
        jQuery.each(result, function(index, val) {
            if (exm) {
                index = "help-" + index;
            }
            var elem = document.getElementById(index);

            if (val.status == 1) {
                elem.classList.remove("off");
                elem.classList.add('on');
            } else if (val.status == 0) {
                elem.classList.remove("on");
                elem.classList.add('off');
            } else if (val.status == 'e') {

                elem.classList.remove("off");
                elem.classList.remove("on");
            } else {

                console.log('status ' + val.status + ' | updateEl(), line-102');

                elem.classList.add('error');
            }

            if (val.source) {
                elem.classList.add('source');
            } else {
                elem.classList.remove("source");
            }

            if (val.status === val.position) {
                elem.classList.remove('conflict');
            } else {
                elem.classList.add('conflict');
            }
        });
    }

    function loadObj() {
        if (mode === 0) {
            var name, p, s;
            obj = {};
            jQuery.each(jQuery('#svg-container .q.on'), function() {

                name = jQuery(this).attr('id');
                obj[name] = {name: name, status: 1, position: 1};
            });
            jQuery.each(jQuery('#svg-container .q.conflict'), function(index, val) {

                name = jQuery(this).attr('id');
                if (val.classList.contains('on')) {
                    p = 0;
                    s = 1;
                } else if (val.classList.contains('off')) {
                    p = 1;
                    s = 0;
                }
                if (typeof p !== 'undefined') {
                    obj[name] = {name: name, status: s, position: p};
                }
            });
            jQuery.each(jQuery('#svg-container .source'), function(index, val) {

                name = jQuery(this).attr('id');
                if (val.classList.contains('on')) {
                    s = 1;
                } else if (val.classList.contains('off')) {
                    s = 0;
                }
                obj[name] = {name: name, status: s, position: 1, source: 1};
            });
        }
    }

    function cdtd(time) {

        var seconds = Math.floor(time);
        var minutes = Math.floor(seconds / 60);
        minutes %= 60;
        seconds %= 60;
        if (seconds < 10) {
            seconds = "0" + seconds;
        }
        document.getElementById("min").innerHTML = minutes;
        document.getElementById("sec").innerHTML = seconds;
        if (time <= 0) {
            clearTimeout(timer);

//            Redirect
            window.location.assign(window.location.protocol + '//' + window.location.hostname);
        } else {
            time = time - 1;
            var timer = setTimeout('cdtd( ' + time + ')', 1000);
        }
    }

    function changeId(e) {
        for (var i = 0; i < e.children.length; i++) {
            var id = e.children[i].id;
            if (id) {
                e.children[i].id = "help-" + id;
            }
            changeId(e.children[i]);
        }
    }

    jQuery(document).ready(function($) {

        var elem;

        if (mode === 1) {
            $("body").append("<div class=\"mode\">REAL MODE</div>");

            alertify.alert("", "Because of technical reasons the access to the real stand is restricted to 5 minutes.<br/>After this time You will be redirected to the virtual mod.<br/><br/>Maybe there are other users before You on the list.<br/>Please be patient.<br/><br/>Thank You for Your understanding!").set({
                onshow: function() {
                }, onclose: function() {
                    rmMessage = true;
                }});
            var notification = alertify.warning("<span id=\"message\"></span>", 0);
            notification.ondismiss = function() {
                return false;
            };
            deb = 'REAL MODE';
            update({action: 'stend'});
        } else if (ex === 'ex') {
            alertify.confirm("It will show hint on each step.").set({
                title: 'Show hints',
                labels: {ok: 'YES', cancel: 'NO'},
                onok: function(e) {
                    exhints = true;
                    deb = 'EX MODE ok';
                    update({action: 'stend'});
                },
                oncancel: function(e) {
                    exhints = false;
                    deb = 'EX MODE cancel';
                    update({action: 'stend'});
                }
            });

            $("body").append("<div class=\"mode\">EXERSISE <?php echo $m; ?></div>");
            $("body").append("<div class=\"tasck-help\"><div class=\"help-button\">TASK</div><div class=\"help-box\"></div></div>");
            $("#svg-container .izvod").css("cursor", "default");
            $("#svg-container .izvod .constant").css("stroke", "#000000");
            $(".cont-reset").css("display", "none");
            var clone = $("#svg-container").clone().appendTo(".help-box");
            $('.help-button').click(function() {
                $(".help-box").slideToggle("fast");
            });
            $(".help-box").each(function(index, value) {
                changeId(this);
                exHelpindex = true;
            });
        } else {
            deb = 'else MODE';
            update({action: 'stend'});
        }


        $('#svg .q').click(function() {
            if (mode === 0 || mode === 'ex' || (mode === 1 && position == 0)) {
                elem = this;
                alertify.alert('<h2>' + $(elem).attr("id") + '</h2><div class="ajs-buttons"><p><a class="ajs-button ajs-on" href="javascript:showAlert(true);"><?php _e( 'ON' ); ?></a><a class="ajs-button ajs-off" href="javascript:showAlert(false);"><?php _e( 'OFF' ); ?></a></p></div>').set({
                    'frameless': true,
                    'movable': false,
                    'overflow': true,
                    'padding': false,
                    'autoReset': true
                });
            }
        });
        $('.reset').click(function() {
            var data = $(this).attr('data');
            if (mode === 0) {
                $(".conflict").each(function(index, val) {
                    val.classList.remove("conflict");
                });
                if (data === 'stend') {
                    $(".source").each(function(index, val) {
                        val.classList.remove("source");
                        val.classList.remove("on");
                        val.classList.add("off");
                    });
                    $(".line").each(function(index, val) {
                        val.classList.remove("on");
                        val.classList.add("off");
                    });
                    $(".on").each(function(index, val) {
                        val.classList.remove("on");
                        val.classList.add("off");
                    });
                }
                loadObj();
            } else if ((mode === 1 && position === 0)) {

                deb = 'reset';
                update({
                    action: $(this).attr('rel'),
                    val: data
                });
            }
        });
        $('#svg .izvod').click(function() {

            if (ex !== 'ex' && (mode === 0 || (mode === 1 && position === 0))) {
                elem = this.parentElement;
                deb = '.izvod';
                update({
                    action: 'stend',
                    name: $(elem).attr('id'),
                    com: elem.classList.contains("source") ? 0 : 1
                });
            }
        });
        window.showAlert = function(a) {
            var txt, com;
            if (a === true) {
                if (elem.classList.contains("off") || elem.classList.contains("conflict")) {
                    txt = "<h2><?php _e( 'ON' ); ?></h2>";
                    com = 1;
                } else {
                    alertify.error('<span class="head">' + $(elem).attr("id") + '</span> is <?php _e( 'ON' ); ?>', 10);
                    return;
                }
            } else if (a === false) {
                if (elem.classList.contains("on") || elem.classList.contains("conflict")) {
                    txt = "<h2><?php _e( 'OFF' ); ?></h2>";
                    com = 0;
                } else {
                    alertify.error('<span class="head">' + $(elem).attr("id") + '</span> is <?php _e( 'OFF' ); ?>', 10);
                    return;
                }
            } else {
                return false;
            }

            alertify.confirm(txt).set({
                'title': $(elem).attr("id"),
                'labels': {
                    ok: '<?php _e( 'OK' ); ?>',
                    cancel: '<?php _e( 'CANCEL' ); ?>'
                },
                'onok': function(e) {
                    deb = 'showAlert';
                    update({
                        action: 'stend',
                        name: $(elem).attr("id"),
                        com: com
                    });
                },
                'defaultFocus': 'cancel',
                'oncancel': function(e) {
                    elem.classList.remove('conflict');
                }
            }).closeOthers();
        };


    });
</script>

