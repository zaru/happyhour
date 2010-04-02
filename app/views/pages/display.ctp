
            <h2><img src="<?php echo $html->url('/img/ttl_logoin.gif'); ?>" alt="ログイン" /></h2>
            <div class="loginImg"><img src="<?php echo $html->url('/img/top_img.jpg'); ?>" alt="プリウスが乗れちゃう" /></div>
            <form action="<?php echo $html->url('/auth/user_login'); ?>" method="post" name="user_login">
                <div class="loginBox clearfix">
                <?php
                    if(isset($error) && $error != ''){
                        echo '<p style="color:#ff0000;text-align:center;padding:0 0 10px 0;">' . $error . '</p>';
                    }
                ?>
                    <dl>
                        <dt><img src="<?php echo $html->url('/img/txt_id.gif'); ?>" alt="会員ID" /></dt>
                        <dd><input name="login_id" type="text" class="txt200" /></dd>
                        <dt><img src="<?php echo $html->url('/img/txt_pass.gif'); ?>" alt="パスワード" /></dt>
                        <dd><input name="password" type="password" class="txt200" /></dd>
                    </dl>
                    <div class="loginBtn"><input type="image" src="<?php echo $html->url('/img/btn_login.gif'); ?>" value="ログイン" /></div>
                </div>
            </form>