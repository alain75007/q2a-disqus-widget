<?php
class qa_disqus_widget 
{
    function allow_template($template)
    {
        return true;
    }

    function allow_region($region)
    {
        return $region == 'main';
    }

    function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
    {
        function dsq_hmacsha1($data, $key) {
            $blocksize=64;
            $hashfunc='sha1';
            if (strlen($key)>$blocksize)
                $key=pack('H*', $hashfunc($key));
            $key=str_pad($key,$blocksize,chr(0x00));
            $ipad=str_repeat(chr(0x36),$blocksize);
            $opad=str_repeat(chr(0x5c),$blocksize);
            $hmac = pack(
                'H*',$hashfunc(
                    ($key^$opad).pack(
                        'H*',$hashfunc(
                            ($key^$ipad).$data
                        )
                    )
                )
            );
            return bin2hex($hmac);
        }

        $disqus_secret_key = qa_opt('disqus_secret_key');
        $disqus_public_key = qa_opt('disqus_public_key');

        define('DISQUS_SECRET_KEY', $disqus_secret_key);
        define('DISQUS_PUBLIC_KEY', $disqus_public_key);

        if (!empty($disqus_secret_key) && !empty($disqus_public_key)) 
        {
            // SSO CONFIGURATION

            $data = array(
                "id" => qa_get_logged_in_userid(),
                "username" => qa_get_logged_in_handle(),
                "email" => qa_get_logged_in_email(),
            );

            # Get avatar (absolute) URL
            $userid = qa_get_logged_in_userid();
            $user = qa_db_single_select(qa_db_user_account_selectspec($userid, true));
            $avatarHTML = qa_get_user_avatar_html($user['flags'], $user['email'], $user['handle'], $user['avatarblobid'], $user['avatarwidth'], $user['avatarheight'], qa_opt('avatar_profile_size'));

            if (isset($avatarHTML))
            {
                $matches = array();
                preg_match('/ src="([^"]*)" /', $avatarHTML, $matches);
                if (sizeof($matches) > 1)  {
                    if (strpos($matches[1], 'http') === false)
                    {
                        $imageURL =  qa_opt('site_url').$matches[1];
                    }
                    else 
                    {
                        $imageURL =  $matches[1];
                    }
                    $data['avatar'] = $imageURL;
                }
            }


            $message = base64_encode(json_encode($data));
            $timestamp = time();
            $hmac = dsq_hmacsha1($message . ' ' . $timestamp, DISQUS_SECRET_KEY);

        }
        echo '<div id="disqus_thread"></div>';
        echo '<script type="text/javascript">';
        if (!empty($disqus_secret_key) && !empty($disqus_public_key)) 
        {
            echo 'var disqus_config = function() {';
            echo 'this.page.remote_auth_s3 = "'.$message.' '.$hmac.' '.$timestamp.'";';
            echo 'this.page.api_key = "'.DISQUS_PUBLIC_KEY.'";';
            echo '};';
        }
        echo 'var disqus_shortname = \'questioncode\';';
        echo '(function() {';
        echo 'var dsq = document.createElement(\'script\'); dsq.type = \'text/javascript\'; dsq.async = true;';
        echo 'dsq.src = \'//\' + disqus_shortname + \'.disqus.com/embed.js\';';
        echo '(document.getElementsByTagName(\'head\')[0] || document.getElementsByTagName(\'body\')[0]).appendChild(dsq);';
        echo '})();';
        echo '</script>';
    }
};
