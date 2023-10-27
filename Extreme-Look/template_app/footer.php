    <div id="footerLine" class="fix-footer">
        <ul class="footer-menu">
            <li class="footer-menu-element">
                <a href="#" id="goToHome">
                    <i class="ex-app-icon icon-home"></i>
                </a>
            </li>
            <li class="footer-menu-element">
                <i class="ex-app-icon icon-menu panel-open"></i>
            </li>
            <li class="footer-menu-element">
                <a href="/page-personal.cart/basket=Y/">
                    <i class="ex-app-icon icon-shop"><span></span></i>
                </a>
            </li>
            <li class="footer-menu-element">
                <?if(!$USER->IsAuthorized()){?>
                    <a href="#" data-login-screen="#login-screen" class="link login-screen-open panel-close ripple-color-red">
                        <i class="ex-app-icon icon-basket"></i>
                    </a>
                <?}else{?>
                    <a href="/?page=personal/orders&<?=MOBILE_GET?>=Y">
                        <i class="ex-app-icon icon-basket"></i>
                    </a>
                <?}?>
            </li>
            <li class="footer-menu-element">
                <?if(!$USER->IsAuthorized()){?>
                    <a href="#" data-login-screen="#login-screen" class="link login-screen-open panel-close ripple-color-red">
                        <i class="ex-app-icon icon-menu-second"></i>
                    </a>
                <?}else{?>
                    <a href="/page-personal.index/">
                        <i class="ex-app-icon icon-menu-second"></i>
                    </a>
                <?}?>
            </li>
        </ul>
    </div>
</div>
<?if(!$USER->IsAuthorized()){?>
    <div class="login-screen" id="login-screen">
        <div class="view">
            <div class="page">
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="title">Авторизация</div>
                        <div class="right">
                            <a href="#" class="link login-screen-close ripple-color-red">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="page-content login-screen-content">
                    <div class="list">
                        <form id="authForm">
                            <ul>
                                <li class="item-content item-input">
                                    <div class="item-inner">
                                        <div class="item-title item-label"></div>
                                        <div class="item-input-wrap">
                                            <input type="text" class="form-control" name="username" placeholder="Введите имя пользователя">
                                        </div>
                                    </div>
                                </li>
                                <li class="item-content item-input">
                                    <div class="item-inner">
                                        <div class="item-title item-label"></div>
                                        <div class="item-input-wrap">
                                            <input type="password" class="form-control" name="password" placeholder="Ваш пароль">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                    </div>
                            <div class="block">
                                <button type="submit" class="btn btn-buy">Войти</button>
                            </div>
                        </form>
                    <div class="block">
                        <div class="block-footer">
                            Нет аккаунта? Присоединяйтесь по ссылке ниже!
                        </div>
                        <a href="/page-register/register=yes/" data-view=".view-main"  style="width: 100%; padding: 6px 0 6px 0;" class="btn btn-buy login-screen-close">
                            Регистрация
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?}?>
</div>
<script src="<?=SITE_TEMPLATE_PATH?>/js/scripts.js?m=<?=filemtime($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/js/scripts.js')?>"></script>
<script>
setTimeout(function(){
    app.methods.updateMessage();
}, 10000);
</script>

    <!-- POPUP VLAD START -->
    <div class="popup-window popup-window-content-white popup-window-with-titlebar bx-catalog-subscribe-popup-window" id="popup-window-app">
        <div class="popup-window-titlebar" id="popup-window-titlebar">
            <span class="popup-window-titlebar-text-ex-sub"></span>
        </div>
        <div id="popup-window-content" class="popup-window-content">
            <div class="popup-ex-content">
                <div class="img-sub-ex">

                </div>
                <div class="ex-sub-info">
                    <span class="alert-ex-suc-sub"></span>
                </div>
            </div>
        </div>
        <div id="form-subscribe-email" class="form-group" style="display: none">
            <input data-id="1" placeholder="Email адрес" id="userContact" class="form-control" type="text" name="contact[1][user]">
        </div>
        <span class="popup-window-close-icon popup-window-titlebar-close-icon">
            <svg focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>
        </span>
        <div class="popup-window-buttons">
            <button class="btn btn-buy" data-item><span>Ок</span></button>
        </div>
    </div>
    <div class="popup-window-overlay" id="popup-window-overlay"></div>
    <!-- POPUP VLAD END -->

    <!-- POPUP OFFERS START -->
    <div class="popup-window popup-window-content-white popup-window-with-titlebar bx-catalog-subscribe-popup-window" id="popup-window-offers-app">
        <div class="popup-window-offers-titlebar" id="popup-window-offers-titlebar">
            <span class="popup-window-titlebar-text-ex-sub"></span>
        </div>
        <div class="popup-window-content" id="popup-window-offers-content">
            <div class="popup-ex-offers-block">

            </div>
        </div>
        <span class="popup-window-offers-close-icon popup-window-titlebar-close-icon">
            <svg focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>
        </span>
    </div>
    <div class="popup-window-overlay" id="popup-window-offers-overlay"></div>
    <!-- POPUP OFFERS END -->

    <div class="stori" id="storis-block" onclick="$(this).removeClass('active-storis')"></div>
    <script>
        (function(w,d,u){
            var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
            var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn-ru.bitrix24.ru/b5123257/crm/site_button/loader_5_te72mz.js');
    </script>
</body>
</html>