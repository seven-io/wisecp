<?php
if (!defined('CORE_FOLDER')) die();

$LANG = $module->lang;
$CONFIG = $module->config;
?>
<form action='<?= Controllers::$init->getData('links')['controller'] ?>'
      method='post' id='sms77Settings'>
    <input type='hidden' name='operation' value='module_controller'>
    <input type='hidden' name='module' value='sms77'>
    <input type='hidden' name='controller' value='settings'>

    <div class='formcon'>
        <div class='yuzde30'>
            <label for='api-key'><?= $LANG['api-key'] ?></label>
        </div>
        <div class='yuzde70'>
            <input name='api-key' id='api-key' value='<?= $CONFIG['api-key'] ?>'>
            <span class='kinfo'><?= $LANG['api-key-desc'] ?></span>
        </div>
    </div>

    <div class='formcon'>
        <div class='yuzde30'>
            <label for='origin'><?= $LANG['origin-name'] ?></label>
        </div>
        <div class='yuzde70'>
            <input id='origin' name='origin' maxlength='16'
                   value='<?= $CONFIG['origin'] ?>'>
            <span class='kinfo'><?= $LANG['origin-name-desc'] ?></span>
        </div>
    </div>

    <div class='formcon'>
        <div class='yuzde30'><?= $LANG['balance-info']; ?></div>
        <div class='yuzde70' id='sms77_get_credit'><?= $LANG['balance-info-desc'] ?></div>
    </div>

    <div style='float:right;' class='guncellebtn yuzde30'>
        <a id='sms77_submit' href='javascript:void(0);' class='yesilbtn gonderbtn'>
            <?= $LANG['save-button'] ?>
        </a>
    </div>
</form>
<script>
    const $credit = $('#sms77_get_credit')
    const initialCredits = $credit.html()
    let loadBalanceSms77 = false

    $(document).ready(function() {
        setInterval(function() {
            const display = $('#module-sms77').css('display')

            if (loadBalanceSms77 || display === 'none') return

            const request = MioAjax({
                action: window.location.href,
                data: {
                    controller: 'get-credit',
                    module: 'sms77',
                    operation: 'module_controller',
                },
                method: 'POST',
            }, true, true)

            request.done(sms77_get_credit)

            loadBalanceSms77 = true
        }, 300)

        $credit.html(
            initialCredits.replace('{credit}', '<?= ___('needs/loading-element') ?>'))

        $('#sms77_submit').click(function() {
            MioAjaxElement($(this), {
                progress_text: progress_text,
                result: 'sms77Settings_handler',
                waiting_text: waiting_text,
            })
        })
    })

    function sms77_get_credit(result) {
        console.log('sms77_get_credit', result) // TODO: remove

        if (result === '') return

        const solve = getJson(result)

        console.log('sms77_get_credit', solve) // TODO: remove

        if (solve === false) console.log(result)
        else $credit.html(initialCredits.replace('{credit}', solve.credit))
    }

    function sms77Settings_handler(result) {
        console.log('sms77Settings_handler', result) // TODO: remove

        if (result === '') return

        const solve = getJson(result)

        console.log('sms77Settings_handler', solve) // TODO: remove

        if (solve === false) return console.log(result)

        if (solve.status === 'error') {
            if (solve.for !== undefined && solve.for !== '') {
                const el = $('#sms77Settings ' + solve.for)

                el.focus()
                el.attr('style', 'border-bottom:2px solid red; color:red;')
                el.change(function() {
                    $(this).removeAttr('style')
                })
            }
            if (solve.message !== undefined && solve.message !== '')
                alert_error(solve.message, {timer: 5000})
        } else if (solve.status === 'successful')
            alert_success(solve.message, {timer: 2500})
    }
</script>
