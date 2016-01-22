<?php ?>
<div class="reset" rel="reset-log">CLEAR LOG</div>
<div class="log-table">
    <?php
    global $interact;

    $log = $interact->get_log();
    if ( is_array( $log ) ) {
        echo '<table>';

        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Object</th>';
        echo '<th>Command</th>';
        echo '<th>Date</th>';
        echo '<th>Reason</th>';
        echo '</tr>';

        foreach ( $log as $id => $values ) {
            echo '<tr>';
            echo '<td>' . $id . '</td>';

            foreach ( $values as $value ) {
                echo '<td>' . $value . '</td>';
            }
            echo '</tr>';
        }
        echo '<table>';
    } elseif ( is_string( $log ) ) {
        echo $log;
    }
    ?>
</div>
<div id="load">
    <img src="../images/loader.gif"/>
</div>
<script type="text/javascript">
    function update(data) {
        var ajax = jQuery.ajax({
            type: "post",
            url: "./inc/ajax.php",
            dataType: "json",
            data: data,
            success: function(result) {
                if (result.error) {
                    jQuery.each(result.error, function(index, val) {
                        alertify.error(val, 10);
                    });
                }
                $('.log-table').text( "The Log is empty." );
            },
            error: function(result, textStatus, errorThrown) {
                console.log(result);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }
    (function($) {
        $(document).ready(function() {
            $('#load').fadeOut('fast');
            $('.reset').click(function() {
                console.log($(this).attr('rel'));
                update({
                    action: $(this).attr('rel'),
                    val: 'reset',
                    lang: '<?php echo $locale_lang; ?>'
                });
            });
        });
    })(jQuery);
</script>